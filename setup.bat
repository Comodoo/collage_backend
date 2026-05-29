@echo off
chcp 65001 >nul
echo ==========================================
echo  College Student Management System
echo  Laravel Backend Setup Script
echo ==========================================
echo.

:: Check if PHP is installed
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP 8.1+ from https://windows.php.net/download/
    pause
    exit /b 1
)

:: Check if Composer is installed
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer is not installed or not in PATH
    echo Please install Composer from https://getcomposer.org/download/
    pause
    exit /b 1
)

echo [1/6] Installing dependencies...
composer install --no-interaction
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install dependencies
    pause
    exit /b 1
)

echo [2/6] Creating environment file...
if not exist .env (
    copy .env.example .env
    echo [OK] Created .env file
) else (
    echo [SKIP] .env already exists
)

echo [3/6] Generating application key...
php artisan key:generate

echo.
echo ==========================================
echo  Database Configuration Required
echo ==========================================
echo.
echo Please edit the .env file and set your database credentials:
echo.
echo DB_CONNECTION=mysql
echo DB_HOST=127.0.0.1
echo DB_PORT=3306
echo DB_DATABASE=college_db
echo DB_USERNAME=root
echo DB_PASSWORD=your_password
echo.
echo Also set your frontend URL:
echo FRONTEND_URL=http://localhost:3000
echo.

set /p dbready="Have you configured the database in .env? (y/n): "
if /i not "%dbready%"=="y" (
    echo.
    echo Please edit .env file and run this script again.
    pause
    exit /b 1
)

echo.
echo [4/6] Creating database and running migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo [ERROR] Migration failed. Please check your database configuration.
    pause
    exit /b 1
)

echo [5/6] Seeding database with sample data...
php artisan db:seed --force

echo [6/6] Creating storage link...
php artisan storage:link 2>nul || echo [SKIP] Storage link already exists

echo.
echo ==========================================
echo  Setup Complete!
echo ==========================================
echo.
echo To start the server, run:
echo   php artisan serve
echo.
echo Default login credentials:
echo   Admin:      admin@college.edu / password123
echo   Instructor: instructor@college.edu / password123
echo   Student:    student@college.edu / password123
echo.
echo API URL: http://localhost:8000/api
echo.
pause
