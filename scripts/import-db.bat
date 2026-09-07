@echo off
rem ------------------------------------------------------------
rem Import MySQL database for webphatthalung
rem ------------------------------------------------------------
setlocal EnableDelayedExpansion
cd /d "%~dp0.."

echo ========================================================
echo   Web Phatthalung - Database Importer
echo ========================================================
set "PHP_CMD=php"
where php >nul 2>&1
if errorlevel 1 (
    for /d %%D in ("C:\wamp64\bin\php\php8*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" for /d %%D in ("C:\wamp64\bin\php\php7*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" if exist "C:\xampp\php\php.exe" set "PHP_CMD=C:\xampp\php\php.exe"
)

"!PHP_CMD!" scripts\import_db.php

if errorlevel 1 (
    echo.
    echo [ERROR] Import failed! Please check your database connection in .env.
) else (
    echo.
    echo [SUCCESS] Database imported successfully!
)

echo.
pause
