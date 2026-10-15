<#
Install Apache VirtualHost and hosts entry for the project (requires Administrator privileges).
This script will:
 - Backup existing httpd-vhosts.conf and hosts file
 - Append the VirtualHost from docs/xampp_vhost.conf to C:\xampp\apache\conf\extra\httpd-vhosts.conf if not already present
 - Append the hosts entry from docs/hosts_entry.txt to C:\Windows\System32\drivers\etc\hosts if not already present

Run as Administrator from project root:
  Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
  .\scripts\install_xampp_vhost.ps1
#>

function Write-Log($m) { Write-Host "[vhost-install] $m" }

$vhostSource = Join-Path -Path (Split-Path -Path $MyInvocation.MyCommand.Definition -Parent) -ChildPath "..\docs\xampp_vhost.conf"
$hostsSource = Join-Path -Path (Split-Path -Path $MyInvocation.MyCommand.Definition -Parent) -ChildPath "..\docs\hosts_entry.txt"

$vhostDest = 'C:\xampp\apache\conf\extra\httpd-vhosts.conf'
$hostsDest = 'C:\Windows\System32\drivers\etc\hosts'

if (-not (Test-Path $vhostSource)) { Write-Log "Missing $vhostSource"; exit 1 }
if (-not (Test-Path $hostsSource)) { Write-Log "Missing $hostsSource"; exit 1 }

try {
    # Backup destination files
    if (Test-Path $vhostDest) { Copy-Item $vhostDest "$vhostDest.bak_$(Get-Date -Format yyyyMMddHHmmss)" -Force; Write-Log "Backed up $vhostDest" }
    if (Test-Path $hostsDest) { Copy-Item $hostsDest "$hostsDest.bak_$(Get-Date -Format yyyyMMddHHmmss)" -Force; Write-Log "Backed up $hostsDest" }

    $vhostContent = Get-Content $vhostSource -Raw
    $hostsContent = Get-Content $hostsSource -Raw

    # Append vhost if not present
    $destVhostText = if (Test-Path $vhostDest) { Get-Content $vhostDest -Raw } else { '' }
    if ($destVhostText -notmatch 'adoprint\.local') {
        Add-Content -Path $vhostDest -Value "`n# --- ADO PRINT SITE vhost: start ---`n" -Encoding UTF8
        Add-Content -Path $vhostDest -Value $vhostContent -Encoding UTF8
        Add-Content -Path $vhostDest -Value "`n# --- ADO PRINT SITE vhost: end ---`n" -Encoding UTF8
        Write-Log "Appended VirtualHost to $vhostDest"
    } else { Write-Log "VirtualHost for adoprint.local already present in $vhostDest" }

    # Append hosts entry if not present
    $destHostsText = Get-Content $hostsDest -Raw
    if ($destHostsText -notmatch 'adoprint\.local') {
        Add-Content -Path $hostsDest -Value "`n# ADO PRINT SITE`n$hostsContent`n" -Encoding ASCII
        Write-Log "Appended hosts entry to $hostsDest"
    } else { Write-Log "Hosts entry for adoprint.local already present in $hostsDest" }

    Write-Log "Done. Restart Apache from XAMPP Control Panel to apply the new VirtualHost. Then open http://adoprint.local"
} catch {
    Write-Log "Error: $_"
    Write-Log "Run this script as Administrator. If it fails, copy the files from docs/ to the target locations manually.";
    exit 1
}

exit 0
