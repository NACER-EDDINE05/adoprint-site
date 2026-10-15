@echo off
REM Run install_xampp_vhost.ps1 with elevation (UAC prompt)
pushd "%~dp0"
powershell -NoProfile -ExecutionPolicy Bypass -Command "Start-Process powershell -ArgumentList '-NoProfile -ExecutionPolicy Bypass -File \"%~dp0install_xampp_vhost.ps1\"' -Verb RunAs"
popd
pause
