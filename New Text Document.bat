@echo off
title Laravel Auto Starter
echo Starting Laravel Development Server...

:: Set environment variables
set APP_ENV=local
set APP_DEBUG=true

:: Check if Node.js is installed and install dependencies
echo Checking Node.js dependencies...
if exist package.json (
    echo Installing npm dependencies...
    call npm install
    echo Building assets...
    call npm run build
)

:: Check if Composer dependencies are installed
echo Checking Composer dependencies...
if not exist vendor (
    echo Installing Composer dependencies...
    call composer install --no-dev
)

:: Generate application key if not exists
if not exist .env (
    copy .env.example .env
    echo Generated .env file
)
php artisan key:generate

:: Clear cache and optimize
echo Optimizing application...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

:: Get local IP address
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr "IPv4"') do (
    set IP=%%i
    goto :found_ip
)
:found_ip
set IP=%IP:~1%
echo Local IP Address: %IP%

:: Start Laravel development server
echo Starting Laravel development server...
echo.
echo ========================================
echo Laravel Server is starting...
echo Local: http://localhost:8000
echo Network: http://%IP%:8000
echo ========================================
echo.
php artisan serve --host=0.0.0.0 --port=8000

pause