@echo off
chcp 65001
title Laravel Auto Starter

echo ========================================
echo    LARAVEL AUTO START SERVER
echo ========================================

:: Set PHP path untuk XAMPP
set PHP_PATH=C:\xampp\php\php.exe
set ARTISAN=artisan

:: Check if artisan exists
if not exist %ARTISAN% (
    echo ERROR: File artisan tidak ditemukan!
    echo Pastikan file ini berada di folder project Laravel yang benar
    pause
    exit
)

:: Check if PHP exists
if not exist "%PHP_PATH%" (
    echo ERROR: PHP tidak ditemukan di %PHP_PATH%
    echo Pastikan XAMPP terinstall di C:\xampp
    pause
    exit
)

:: Get IP address
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr "IPv4"') do (
    set IP=%%i
    goto :found_ip
)
:found_ip
set IP=%IP:~1%

echo.
echo Menginstall dependencies...
"%PHP_PATH%" "%ARTISAN%" install --no-dev

echo.
echo Generating key...
"%PHP_PATH%" "%ARTISAN%" key:generate

echo.
echo Clearing cache...
"%PHP_PATH%" "%ARTISAN%" config:clear
"%PHP_PATH%" "%ARTISAN%" cache:clear
"%PHP_PATH%" "%ARTISAN%" view:clear

echo.
echo ========================================
echo SERVER READY!
echo Local:  http://localhost:8000
echo Network: http://%IP%:8000
echo ========================================
echo.

"%PHP_PATH%" "%ARTISAN%" serve --host=0.0.0.0 --port=8000

pause