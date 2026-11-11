# ============================================================
#  kasir-server.ps1
#  Supervisor: menjaga SERVER WEBSITE + TUNNEL tetap hidup.
#  - Kalau server Laravel (port 8000) mati -> dinyalakan lagi.
#  - Kalau tunnel Cloudflare mati -> dinyalakan lagi + update link.
#  Dijalankan otomatis saat login Windows (lihat kasir-autostart.vbs).
#  Biarkan berjalan; jangan ditutup.
# ============================================================
$ErrorActionPreference = 'SilentlyContinue'

$dir  = 'C:\Users\ASUS\Documents\kasir'
$php  = 'D:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe'
$cfl  = Join-Path $dir 'tools\cloudflared.exe'
$port = 8000
$urlFile = Join-Path $dir 'URL-Publik.txt'
$cfLog   = Join-Path $env:TEMP 'cf_tunnel.log'

Set-Location $dir

while ($true) {
    # ----- 1) Server Laravel -----
    $listen = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue
    if (-not $listen) {
        Start-Process -FilePath $php `
            -ArgumentList @('artisan', 'serve', '--host=0.0.0.0', "--port=$port") `
            -WorkingDirectory $dir -WindowStyle Hidden
        Start-Sleep -Seconds 3
    }

    # ----- 2) Tunnel Cloudflare -----
    if (-not (Get-Process cloudflared -ErrorAction SilentlyContinue)) {
        if (Test-Path $cfl) {
            Remove-Item $cfLog -ErrorAction SilentlyContinue
            Start-Process -FilePath $cfl `
                -ArgumentList @('tunnel', '--url', "http://127.0.0.1:$port", '--no-autoupdate', '--logfile', $cfLog) `
                -WindowStyle Hidden | Out-Null

            for ($i = 0; $i -lt 40; $i++) {
                Start-Sleep -Seconds 2
                if (Test-Path $cfLog) {
                    $m = [regex]::Match((Get-Content $cfLog -Raw), 'https://[a-z0-9-]+\.trycloudflare\.com')
                    if ($m.Success) { $m.Value | Set-Content $urlFile; break }
                }
            }
        }
    }

    Start-Sleep -Seconds 15
}
