@echo off
title Payroll App
cd /d "%~dp0"

echo ========================================
echo   Payroll App - Automation System
echo ========================================
echo.
echo   Login: admin@payroll.com
echo   Pass:  password
echo.
echo   Press Ctrl+C to stop all services
echo ========================================
echo.

start "Payroll Server" cmd /c "php artisan serve --port=8000"
timeout /t 2 /nobreak >nul
start "Queue Default" cmd /c "php artisan queue:listen --tries=1 --timeout=0"
timeout /t 1 /nobreak >nul
start "Queue Email" cmd /c "php artisan queue:listen --queue=email --tries=3 --timeout=300"
timeout /t 1 /nobreak >nul
start "Vite Dev" cmd /c "npm run dev"

echo.
echo   Semua services berjalan di window terpisah.
echo   Tutup window ini untuk menghentikan semua service.
echo.

pause
taskkill /f /im php.exe 2>nul
taskkill /f /im node.exe 2>nul
