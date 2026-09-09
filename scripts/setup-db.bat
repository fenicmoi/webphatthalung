@echo off
rem ------------------------------------------------------------
rem Full Database Setup: Migration & Master Seeder
rem ------------------------------------------------------------
setlocal EnableDelayedExpansion
cd /d "%~dp0.."

echo ========================================================
echo   Web Phatthalung - Migration & Database Auto Setup
echo ========================================================
echo.

set "PHP_CMD=php"
where php >nul 2>&1
if errorlevel 1 (
    for /d %%D in ("C:\wamp64\bin\php\php8*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" for /d %%D in ("C:\wamp64\bin\php\php7*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" if exist "C:\xampp\php\php.exe" set "PHP_CMD=C:\xampp\php\php.exe"
)

if not exist ".env" (
    echo [.env not found] Copying from .env.example ...
    copy .env.example .env
    echo Please verify database settings in .env if needed.
    echo.
)

echo [1/3] Running Database Migrations...
"!PHP_CMD!" spark migrate
if errorlevel 1 (
    echo.
    echo [ERROR] Migration failed! Check your database credentials in .env.
    pause
    exit /b 1
)

echo.
echo [2/3] Seeding Master Database Data...
"!PHP_CMD!" spark db:seed MasterSeeder
if errorlevel 1 (
    echo.
    echo [ERROR] Seeding encountered issues.
    pause
    exit /b 1
)

echo.
echo [3/3] Clearing Cache...
"!PHP_CMD!" spark cache:clear

echo.
echo ========================================================
echo   [SUCCESS] Migration and Seeding Completed Successfully!
echo ========================================================
echo.
pause
