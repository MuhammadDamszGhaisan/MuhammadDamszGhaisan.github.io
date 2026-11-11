# ============================================================
#  vscode-kasir.ps1
#  "VERSI VISUAL STUDIO CODE" untuk Website MAGSTORE.
#
#  Sekali jalan, script ini:
#    1) Menyalakan server website (Laravel, port 8000) bila belum jalan.
#    2) Menyalakan tunnel Cloudflare (link publik) bila belum jalan.
#    3) Membuka proyek ini di Visual Studio Code.
#    4) Menampilkan semua alamat akses.
#
#  Cara pakai:
#    - Dobel-klik  : Buka Website + VS Code.bat
#    - Atau terminal VS Code (PowerShell):
#          .\vscode-kasir.ps1
#          .\vscode-kasir.ps1 -NoVSCode      (tanpa membuka VS Code)
#          .\vscode-kasir.ps1 -NoBrowser     (tanpa membuka browser)
#          .\vscode-kasir.ps1 -Supervisor    (jalankan penjaga otomatis, tahan proses ini)
# ============================================================
param(
    [switch]$NoVSCode,
    [switch]$NoBrowser,
    [switch]$Supervisor
)

$ErrorActionPreference = 'SilentlyContinue'

# Lokasi proyek (mengikuti lokasi script ini)
$dir = if ($PSScriptRoot) { $PSScriptRoot } else { 'C:\Users\ASUS\Documents\kasir' }

$php     = 'D:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe'
$code    = 'D:\laragon\www\Produk Kreatif PKK\Microsoft VS Code\Code.exe'
$port    = 8000
$urlFile = Join-Path $dir 'URL-Publik.txt'

Set-Location $dir

Write-Host ""
Write-Host "  MAGSTORE - Versi Visual Studio Code" -ForegroundColor Yellow
Write-Host "  ==========================================" -ForegroundColor Yellow

# ---------- 1 & 2) Nyalakan server + tunnel (dipakai bersama) ----------
$starter = Join-Path $dir 'server-start.ps1'
if (Test-Path $starter) {
    & $starter -Quiet *> $null
} else {
    # Fallback bila server-start.ps1 tidak ada
    $listen = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue
    if (-not $listen) {
        Start-Process -FilePath $php `
            -ArgumentList @('artisan', 'serve', '--host=0.0.0.0', "--port=$port") `
            -WorkingDirectory $dir -WindowStyle Hidden
        Start-Sleep -Seconds 3
    }
}

# ---------- 3) Buka proyek di Visual Studio Code ----------
if (-not $NoVSCode -and (Test-Path $code)) {
    $ws = Join-Path $dir 'MAGSTORE.code-workspace'
    $target = if (Test-Path $ws) { $ws } else { $dir }
    Start-Process -FilePath $code -ArgumentList "`"$target`""
    Write-Host "  Visual Studio Code : dibuka untuk proyek ini." -ForegroundColor Green
} elseif (-not $NoVSCode) {
    Write-Host "  VS Code tidak ditemukan di: $code" -ForegroundColor Red
}

# ---------- 4) Tampilkan alamat ----------
$ip = (Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue |
        Where-Object { $_.IPAddress -notlike '127.*' -and $_.IPAddress -notlike '169.254.*' } |
        Select-Object -First 1 -ExpandProperty IPAddress)

$public = $null
if (Test-Path $urlFile) { $public = (Get-Content $urlFile -Raw).Trim() }

Write-Host ""
Write-Host "  Di laptop          : http://127.0.0.1:$port" -ForegroundColor Cyan
if ($ip) { Write-Host "  Di HP (Wi-Fi sama) : http://${ip}:$port" -ForegroundColor Green }
if ($public) {
    Write-Host "  Di Internet        : $public" -ForegroundColor Magenta
    Write-Host "                       ^ bagikan link ini ke pelanggan" -ForegroundColor DarkGray
}
Write-Host ""
Write-Host "  Tips: tekan Ctrl+Shift+B di VS Code untuk menjalankan server" -ForegroundColor DarkGray
Write-Host "        (Tasks yang tersedia ada di .vscode\tasks.json)" -ForegroundColor DarkGray
Write-Host ""

# ---------- Opsional: jalankan penjaga otomatis di depan ----------
if ($Supervisor) {
    Write-Host "  Mode Supervisor: menjaga server & tunnel tetap hidup..." -ForegroundColor Yellow
    Write-Host "  (Biarkan jendela ini terbuka. Tekan Ctrl+C untuk berhenti.)" -ForegroundColor DarkGray
    & (Join-Path $dir 'kasir-server.ps1')
    exit
}

# ---------- Buka browser (opsional) ----------
if (-not $NoBrowser) {
    Start-Process "http://127.0.0.1:$port"
}
