<#
Create a desktop shortcut to run_one_click.bat for the current user.
Usage (from project root):
  powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts\create_desktop_shortcut.ps1
#>

$ErrorActionPreference = 'Stop'

# Resolve project root and target
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$projectRoot = (Resolve-Path (Join-Path $scriptDir '..'))
$targetBat = Join-Path $projectRoot 'run_one_click.bat'

if (-not (Test-Path $targetBat)) {
    Write-Error "Target batch file not found: $targetBat"
    exit 1
}

$desktop = [Environment]::GetFolderPath('Desktop')
$shortcutPath = Join-Path $desktop 'ADO Print - Lancer le site.lnk'

$wsh = New-Object -ComObject WScript.Shell
$shortcut = $wsh.CreateShortcut($shortcutPath)
$shortcut.TargetPath = $targetBat
$shortcut.WorkingDirectory = (Split-Path $targetBat)
$shortcut.WindowStyle = 1
$shortcut.Description = 'Lance le setup Docker et démarre le site ADO Print'
# Optionally set an icon if you have one in the project, fall back to cmd.exe icon
$possibleIcon = Join-Path $projectRoot 'public\favicon.ico'
if (Test-Path $possibleIcon) {
    $shortcut.IconLocation = $possibleIcon
} else {
    $shortcut.IconLocation = "$env:SystemRoot\system32\shell32.dll, 1"
}
$shortcut.Save()

Write-Host "Shortcut created:" $shortcutPath
