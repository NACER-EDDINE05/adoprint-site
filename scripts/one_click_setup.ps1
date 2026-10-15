# One-click setup for Docker-based local preview
# Usage: Open PowerShell as Administrator and run:
#   .\scripts\one_click_setup.ps1

param(
    [string]$AppUrl = 'http://localhost:8080'
)

function Write-Log($m) { Write-Host "[one-click] $m" }

Write-Log "Starting one-click setup..."

# Detect docker-compose or docker with compose plugin
function Get-DockerComposeMode {
    if (Get-Command docker-compose -ErrorAction SilentlyContinue) { return 'compose-v1' }
    if (Get-Command docker -ErrorAction SilentlyContinue) {
        try {
            docker compose version > $null 2>&1
            if ($LASTEXITCODE -eq 0) { return 'compose-v2' }
        } catch { }
    }
    return $null
}

$dockerMode = Get-DockerComposeMode
if (-not $dockerMode) {
    Write-Log "docker-compose not found. Ensure Docker Desktop is installed and docker-compose (v1) or docker compose (v2) is available on PATH.";
    exit 1
}

function Invoke-DockerCompose([string[]]$Args) {
    if ($dockerMode -eq 'compose-v1') {
        & docker-compose @Args
        return $LASTEXITCODE
    } else {
        & docker compose @Args
        return $LASTEXITCODE
    }
}

# Build and start containers
Write-Log "Building and starting containers (this may take a few minutes)..."
Invoke-DockerCompose -Args @('up','-d','--build') | Out-Null
if ($LASTEXITCODE -ne 0) { Write-Log "docker compose up failed"; exit 1 }

# Wait for DB to be ready
Write-Log "Waiting for database to be ready (up to 60s)..."
$max = 30
$ok = $false
for ($i=0; $i -lt $max; $i++) {
    try {
        Invoke-DockerCompose -Args @('exec','-T','db','mysql','-u','root','-proot','-e','"SELECT 1;"') > $null 2>&1
        if ($LASTEXITCODE -eq 0) { $ok = $true; break }
    } catch { }
    Start-Sleep -Seconds 2
}
if (-not $ok) { Write-Log "Database didn't come up in time. Check docker logs."; exit 1 }

# Copy env if missing and set APP_URL
if (-not (Test-Path .env)) {
    if (Test-Path .env.example) { Copy-Item .env.example .env; Write-Log "Copied .env.example to .env" }
}

# Update APP_URL in .env
(Get-Content .env) -replace 'APP_URL=.*', "APP_URL=$AppUrl" | Set-Content .env
Write-Log ".env updated with APP_URL=$AppUrl"

# Install PHP dependencies
Write-Log "Installing PHP dependencies via composer inside container..."
Invoke-DockerCompose -Args @('exec','-T','app','composer','install','--no-interaction','--prefer-dist','--optimize-autoloader') | Out-Null
if ($LASTEXITCODE -ne 0) { Write-Log "Composer install failed"; exit 1 }

# Generate app key
Write-Log "Generating application key..."
Invoke-DockerCompose -Args @('exec','-T','app','php','artisan','key:generate','--force') | Out-Null

# Run migrations
Write-Log "Running migrations..."
Invoke-DockerCompose -Args @('exec','-T','app','php','artisan','migrate','--force') | Out-Null

# Seed SuperAdmin
Write-Log "Seeding SuperAdmin..."
Invoke-DockerCompose -Args @('exec','-T','app','php','artisan','db:seed','--class=SuperAdminSeeder') | Out-Null

# Build assets with node
Write-Log "Building frontend assets (npm install && npm run build) inside node container..."
if ($dockerMode -eq 'compose-v1') {
    Invoke-DockerCompose -Args @('exec','-T','node','sh','-c','npm install --silent && npm run build') | Out-Null
} else {
    # For docker compose v2 the argument passing is similar
    Invoke-DockerCompose -Args @('exec','-T','node','sh','-c','npm install --silent && npm run build') | Out-Null
}

# Link storage
Write-Log "Creating storage symlink..."
docker-compose exec -T app php artisan storage:link
if ($LASTEXITCODE -ne 0) { Write-Log "storage:link may fail on some platforms; verify storage is accessible" }

Write-Log "Setup complete. Open $AppUrl in your browser."
Write-Log "Admin credentials seeded: admin@imprimerie.local / password123"

exit 0
