<#
Script: run_migrate_seed.ps1
But: ajouter temporairement C:\xampp\php au PATH, vérifier/install Composer, exécuter
`php artisan migrate --force` puis `php artisan db:seed --force` depuis la racine du projet.

Usage: Ouvrir PowerShell en tant qu'utilisateur (ou admin si nécessaire) depuis la racine
du projet et exécuter :
    Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
    .\scripts\run_migrate_seed.ps1
#>

function Write-Info($msg) { Write-Host "[INFO] $msg" -ForegroundColor Cyan }
function Write-Warn($msg) { Write-Host "[WARN] $msg" -ForegroundColor Yellow }
function Write-Err($msg) { Write-Host "[ERROR] $msg" -ForegroundColor Red }

Write-Info "Vérification de PHP..."
$php = Get-Command php -ErrorAction SilentlyContinue
if (-not $php) {
    $expected = 'C:\\xampp\\php\\php.exe'
    if (Test-Path $expected) {
        Write-Info "Ajout temporaire de C:\\xampp\\php au PATH pour cette session."
        $env:Path = 'C:\\xampp\\php;' + $env:Path
    } else {
        Write-Err "php introuvable. Veuillez installer PHP (ou XAMPP) et s'assurer que C:\\xampp\\php\\php.exe existe."
        exit 1
    }
}

Write-Info "PHP version : $(php -v 2>$null | Select-Object -First 1)"

Write-Info "Vérification de Composer..."
$composer = Get-Command composer -ErrorAction SilentlyContinue
if (-not $composer) {
    if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
        Write-Err "php non disponible pour installer Composer. Abandon."
        exit 1
    }

    $tmp = Join-Path $env:TEMP "composer-setup.php"
    Write-Info "Téléchargement de l'installateur Composer..."
    try {
        Invoke-WebRequest -Uri 'https://getcomposer.org/installer' -OutFile $tmp -UseBasicParsing -ErrorAction Stop
    } catch {
        Write-Err "Échec du téléchargement de Composer: $($_.Exception.Message)"
        exit 1
    }

    Write-Info "Installation de Composer dans C:\\xampp\\php (requiert écriture dans ce dossier)."
    try {
        php $tmp --install-dir="C:\\xampp\\php" --filename=composer
        Remove-Item $tmp -Force
    } catch {
        Write-Warn "Impossible d'installer Composer automatiquement. Si vous avez des erreurs de permissions, exécutez cette partie en administrateur."
    }

    $composer = Get-Command composer -ErrorAction SilentlyContinue
    if (-not $composer) {
        Write-Warn "Composer introuvable après installation automatique. Vous pouvez l'installer manuellement: https://getcomposer.org/download/"
    }
}

Write-Info "Composer version : $(try { composer -V } catch { 'inconnu' })"

Write-Info "Exécution des migrations (php artisan migrate --force)..."
try {
    php artisan migrate --force 2>&1 | ForEach-Object { Write-Host $_ }
} catch {
    Write-Err "Erreur lors des migrations: $($_.Exception.Message)"
    exit 1
}

Write-Info "Exécution des seeders (php artisan db:seed --force)..."
try {
    php artisan db:seed --force 2>&1 | ForEach-Object { Write-Host $_ }
} catch {
    Write-Err "Erreur lors des seeders: $($_.Exception.Message)"
    exit 1
}

Write-Info "Migrations et seeders terminés. Vérifiez la sortie ci-dessus pour les éventuelles erreurs." 
