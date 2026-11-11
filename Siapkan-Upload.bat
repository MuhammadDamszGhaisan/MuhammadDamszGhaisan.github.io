@echo off
chcp 65001 >nul
title Siapkan Upload Hosting - MAGSTORE
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0Siapkan-Upload-Hosting.ps1"
