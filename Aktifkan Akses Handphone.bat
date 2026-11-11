@echo off
title Aktifkan Akses Handphone - MAGSTORE
cd /d "%~dp0"

net session >nul 2>&1
if %errorlevel% neq 0 (
    echo Membutuhkan izin Administrator. Bila muncul jendela konfirmasi, klik "Yes".
    powershell -NoProfile -Command "Start-Process -FilePath '%~f0' -Verb RunAs"
    exit /b
)

echo Menambahkan aturan firewall untuk port 8000 ...
netsh advfirewall firewall delete rule name="MAGSTORE 8000" >nul 2>&1
netsh advfirewall firewall add rule name="MAGSTORE 8000" dir=in action=allow protocol=TCP localport=8000 profile=any
echo.
echo Selesai! Sekarang HP dapat membuka website selama tersambung ke Wi-Fi yang sama.
echo.
pause
