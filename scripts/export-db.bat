@echo off
rem ------------------------------------------------------------
rem Export MySQL database and seed data for webphatthalung
rem ------------------------------------------------------------
setlocal EnableDelayedExpansion
cd /d "%~dp0.."

echo ========================================================
echo   Web Phatthalung - Database and Seed Exporter
echo ========================================================
set "PHP_CMD=php"
where php >nul 2>&1
if errorlevel 1 (
    for /d %%D in ("C:\wamp64\bin\php\php8*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" for /d %%D in ("C:\wamp64\bin\php\php7*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" if exist "C:\xampp\php\php.exe" set "PHP_CMD=C:\xampp\php\php.exe"
)

"!PHP_CMD!" scripts\export_db.php

if errorlevel 1 (
    echo.
    echo [ERROR] Export failed! Make sure PHP and MySQL are running.
) else (
    echo.
    echo [SUCCESS] Export finished! All tables and seed files are up to date.
)

echo.
pause
