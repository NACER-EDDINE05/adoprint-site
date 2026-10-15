@echo off
REM Run the one-click PowerShell setup from project root (works with spaces)
pushd "%~dp0"

REM Ask user whether to create desktop shortcut
where choice >nul 2>nul
if %errorlevel%==0 (
	choice /M "Create desktop shortcut on your Desktop?"
	if errorlevel 2 goto SKIP_SHORTCUT
	if errorlevel 1 goto DO_SHORTCUT
) else (
	set /p _ans=Create desktop shortcut on your Desktop? [Y/n] : 
	if /I "%_ans%"=="n" goto SKIP_SHORTCUT
)

:DO_SHORTCUT
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\create_desktop_shortcut.ps1"
if %errorlevel% neq 0 echo Failed to create desktop shortcut (continuing)
goto AFTER_SHORTCUT

:SKIP_SHORTCUT
echo Skipping desktop shortcut creation.

:AFTER_SHORTCUT
REM Ask user whether to run the Docker setup now
where choice >nul 2>nul
if %errorlevel%==0 (
	choice /M "Run the Docker setup now?"
	if errorlevel 2 goto SKIP_SETUP
	if errorlevel 1 goto DO_SETUP
) else (
	set /p _runans=Run the Docker setup now? [Y/n] : 
	if /I "%_runans%"=="n" goto SKIP_SETUP
)

:DO_SETUP
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\one_click_setup.ps1"
goto AFTER_SETUP

:SKIP_SETUP
echo Skipping Docker setup. You can run scripts\one_click_setup.ps1 later to continue.
where choice >nul 2>nul
if %errorlevel%==0 (
	choice /M "Run local (non-Docker) setup instead?"
	if errorlevel 2 goto AFTER_SETUP
	if errorlevel 1 goto DO_LOCAL_SETUP
) else (
	set /p _localans=Run local (non-Docker) setup instead? [Y/n] : 
	if /I "%_localans%"=="n" goto AFTER_SETUP
)

:DO_LOCAL_SETUP
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\start_local_fallback.ps1"
if %errorlevel% neq 0 echo Local setup script exited with error (check output)
goto AFTER_SETUP

:AFTER_SETUP
popd
pause
