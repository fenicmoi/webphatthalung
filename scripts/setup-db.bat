@echo off
rem ------------------------------------------------------------
rem Full Database Setup: Migration & Master Seeder
rem ------------------------------------------------------------
setlocal
cd /d "%~dp0.."

echo ========================================================
echo   Web Phatthalung - Migration & Database Auto Setup
echo ========================================================
echo.

if not exist ".env" (
    echo [.env not found] Copying from .env.example ...
    copy .env.example .env
    echo Please verify database settings in .env if needed.
    echo.
)

echo [1/3] Running Database Migrations...
php spark migrate
if errorlevel 1 (
    echo.
    echo [ERROR] Migration failed! Check your database credentials in .env.
    pause
    exit /b 1
)

echo.
echo [2/3] Seeding Master Database Data...
php spark db:seed MasterSeeder
if errorlevel 1 (
    echo.
    echo [ERROR] Seeding encountered issues.
    pause
    exit /b 1
)

echo.
echo [3/3] Clearing Cache...
php spark cache:clear

echo.
echo ========================================================
echo   [SUCCESS] Migration and Seeding Completed Successfully!
echo ========================================================
echo.
pause
