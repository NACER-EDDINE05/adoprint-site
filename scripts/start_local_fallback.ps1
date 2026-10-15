<#
Start local fallback setup when Docker is not available.
This will attempt to run Composer, Artisan migrations/seeds, build assets, and start `php artisan serve`.
If XAMPP is installed the script will try to configure `.env` for local MySQL and create the DB.
Run from project root with PowerShell:
  Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
  .\scripts\start_local_fallback.ps1 -UseXampp
#>

function Write-Log($m) { Write-Host "[local-setup] $m" }

param(
    [switch]$UseXampp
)

Write-Log "Starting local fallback setup..."

# Paths for common XAMPP binaries
$xamppPhp = 'C:\xampp\php\php.exe'
$xamppMysql = 'C:\xampp\mysql\bin\mysql.exe'

# Determine PHP executable to use
$phpCmd = $null
if (Get-Command php -ErrorAction SilentlyContinue) { $phpCmd = 'php' }
elseif (Test-Path $xamppPhp) { $phpCmd = $xamppPhp; Write-Log "Using XAMPP PHP at $xamppPhp" }
else { Write-Log "PHP not found on PATH and XAMPP PHP not found. Please install PHP or XAMPP."; exit 1 }

# Determine Composer
$composerCmd = $null
if (Get-Command composer -ErrorAction SilentlyContinue) { $composerCmd = 'composer' }
else { Write-Log "Composer not found on PATH. Please install Composer or make it available."; exit 1 }

# Check Node/NPM
if (-not (Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Log "npm not found on PATH. Please install Node.js and npm to build frontend assets."; exit 1
}

Push-Location (Split-Path -Path $MyInvocation.MyCommand.Definition -Parent) | Out-Null
Set-Location ..

# If user requested XAMPP or XAMPP appears installed, configure .env DB for XAMPP
if ($UseXampp -or (Test-Path $xamppPhp)) {
    if (-not (Test-Path .env)) {
        if (Test-Path .env.example) { Copy-Item .env.example .env; Write-Log "Copied .env.example to .env" }
        else { Write-Log ".env.example not found; create a .env before proceeding."; exit 1 }
    }

    # Update .env database settings for XAMPP (MySQL with root no password)
    $envText = Get-Content .env -Raw
    $envText = ($envText -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql')
    $envText = ($envText -replace 'DB_HOST=.*', 'DB_HOST=127.0.0.1')
    $envText = ($envText -replace 'DB_PORT=.*', 'DB_PORT=3306')
    if ($envText -match 'DB_DATABASE=') {
        $envText = ($envText -replace 'DB_DATABASE=.*', 'DB_DATABASE=adoprint')
    } else { $envText += "`nDB_DATABASE=adoprint" }
    $envText = ($envText -replace 'DB_USERNAME=.*', 'DB_USERNAME=root')
    $envText = ($envText -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=')
    Set-Content -Path .env -Value $envText -Encoding UTF8
    Write-Log ".env updated for XAMPP (DB_DATABASE=adoprint, DB_USERNAME=root)"

    # Create database using XAMPP mysql if available
    if (Test-Path $xamppMysql) {
        Write-Log "Creating database 'adoprint' using XAMPP mysql..."
        & $xamppMysql -u root -e "CREATE DATABASE IF NOT EXISTS adoprint CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>$null
        if ($LASTEXITCODE -ne 0) { Write-Log "Failed to create database via XAMPP mysql. Create it manually via phpMyAdmin or mysql." }
        else { Write-Log "Database 'adoprint' ensured." }
    } else {
        Write-Log "XAMPP mysql not found at $xamppMysql — create database 'adoprint' manually via phpMyAdmin or mysql." }
}

Write-Log "Installing PHP dependencies (composer install) ..."
& $composerCmd install --no-interaction
if ($LASTEXITCODE -ne 0) { Write-Log "composer install failed"; exit 1 }

Write-Log "Generating app key..."
& $phpCmd artisan key:generate --force

Write-Log "Running migrations..."
& $phpCmd artisan migrate --force
if ($LASTEXITCODE -ne 0) { Write-Log "migrate failed"; exit 1 }

Write-Log "Seeding SuperAdmin..."
& $phpCmd artisan db:seed --class=SuperAdminSeeder
if ($LASTEXITCODE -ne 0) { Write-Log "db:seed failed"; exit 1 }

Write-Log "Building frontend assets (npm install && npm run build)..."
npm install --silent
if ($LASTEXITCODE -ne 0) { Write-Log "npm install failed"; exit 1 }
npm run build
if ($LASTEXITCODE -ne 0) { Write-Log "npm run build failed"; exit 1 }

Write-Log "Starting Laravel built-in server on http://127.0.0.1:8000"
Write-Log "Use Ctrl+C to stop the server"
& $phpCmd artisan serve --host=127.0.0.1 --port=8000

Pop-Location | Out-Null

exit 0
