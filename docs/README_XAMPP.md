Short guide to run the project with XAMPP

1) Ensure XAMPP Apache and MySQL are running (XAMPP Control Panel).

2) Use the provided VirtualHost template `docs/xampp_vhost.conf`:
   - Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf` and append the content of `docs/xampp_vhost.conf`.
   - Edit `C:\Windows\System32\drivers\etc\hosts` and add the line:
     `127.0.0.1  adoprint.local`
   - Restart Apache from XAMPP Control Panel.
   - Open http://adoprint.local in your browser.

3) Alternatively, run the local fallback script which configures .env and starts the built-in server:
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
.\scripts\start_local_fallback.ps1 -UseXampp
```

4) If migrations fail, create the database `adoprint` manually via phpMyAdmin and re-run migrations:
```powershell
C:\xampp\php\php.exe artisan migrate --force
C:\xampp\php\php.exe artisan db:seed --class=SuperAdminSeeder
```
