' Auto-start + penjaga server + tunnel website MAGSTORE saat login Windows.
' Menjalankan supervisor (kasir-server.ps1) secara tersembunyi:
'  - menjaga server Laravel (port 8000) tetap hidup,
'  - menjaga tunnel Cloudflare tetap hidup + update link publik.
Option Explicit

Dim sh
Set sh = CreateObject("WScript.Shell")
sh.CurrentDirectory = "C:\Users\ASUS\Documents\kasir"
sh.Run "powershell -NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File ""C:\Users\ASUS\Documents\kasir\kasir-server.ps1""", 0, False
