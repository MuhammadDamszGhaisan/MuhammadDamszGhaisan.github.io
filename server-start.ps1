# Server Website MAGSTORE
# Mengaktifkan akses dari: laptop, HP (Wi-Fi sama), dan Internet (HP kuota / Wi-Fi beda)
param([switch]$Quiet)

$ErrorActionPreference = 'SilentlyContinue'

$dir  = if ($PSScriptRoot) { $PSScriptRoot } else { 'C:\Users\ASUS\Documents\kasir' }
$php  = 'D:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe'
$cfl  = Join-Path $dir 'tools\cloudflared.exe'
$port = 8000
$urlFile = Join-Path $dir 'URL-Publik.txt'
$cfLog   = Join-Path $env:TEMP 'cf_tunnel.log'

Set-Location $dir

function Get-LanIp {
    Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue |
        Where-Object { $_.IPAddress -notlike '127.*' -and $_.IPAddress -notlike '169.254.*' } |
        Select-Object -First 1 -ExpandProperty IPAddress
}

# ---------- 1. Server Laravel ----------
$serverUp = $false
try {
    $req = [System.Net.WebRequest]::Create("http://127.0.0.1:$port/login"); $req.Timeout = 1500
    $resp = $req.GetResponse(); if ([int]$resp.StatusCode -eq 200) { $serverUp = $true }; $resp.Close()
} catch { $serverUp = $false }

if (-not $serverUp) {
    Start-Process -FilePath $php `
        -ArgumentList @('artisan', 'serve', '--host=0.0.0.0', "--port=$port") `
        -WorkingDirectory $dir -WindowStyle Hidden
    Start-Sleep -Seconds 3
}

# ---------- 2. Tunnel Cloudflare (akses internet) ----------
$public = $null
$cfUp = [bool](Get-Process cloudflared -ErrorAction SilentlyContinue)

if ($cfUp -and (Test-Path $urlFile)) {
    $public = (Get-Content $urlFile -Raw).Trim()
}

if (-not $cfUp -and (Test-Path $cfl)) {
    Remove-Item $cfLog -ErrorAction SilentlyContinue
    Start-Process -FilePath $cfl `
        -ArgumentList @('tunnel', '--url', "http://127.0.0.1:$port", '--no-autoupdate', '--logfile', $cfLog) `
        -WindowStyle Hidden | Out-Null

    for ($i = 0; $i -lt 40 -and -not $public; $i++) {
        Start-Sleep -Seconds 2
        if (Test-Path $cfLog) {
            $m = [regex]::Match((Get-Content $cfLog -Raw), 'https://[a-z0-9-]+\.trycloudflare\.com')
            if ($m.Success) { $public = $m.Value }
        }
    }
    if ($public) { $public | Set-Content $urlFile }
}

# ---------- 3. Tampilkan alamat ----------
$ip = Get-LanIp

Write-Host ""
Write-Host "  SERVER WEBSITE MAGSTORE" -ForegroundColor Yellow
Write-Host "  ==============================" -ForegroundColor Yellow
Write-Host ("  Di laptop          : http://127.0.0.1:{0}" -f $port) -ForegroundColor Cyan
if ($ip) {
    Write-Host ("  Di HP (Wi-Fi sama) : http://{0}:{1}" -f $ip, $port) -ForegroundColor Green
}
if ($public) {
    Write-Host "  Di Internet (HP pakai kuota / Wi-Fi beda) :" -ForegroundColor Magenta
    Write-Host ("      {0}" -f $public) -ForegroundColor Magenta
    Write-Host "      ^ bagikan alamat ini ke pelanggan" -ForegroundColor DarkGray
    Write-Host ("      (tersimpan di: {0})" -f $urlFile) -ForegroundColor DarkGray
} else {
    Write-Host "  Di Internet : tunnel belum aktif. Pastikan ada koneksi internet." -ForegroundColor Red
}
Write-Host ""

if (-not $Quiet) {
    Start-Process "http://127.0.0.1:$port"
    Read-Host "  Tekan Enter untuk menutup jendela ini (server & tunnel tetap berjalan)"
}
