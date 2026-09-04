@echo off
rem ------------------------------------------------------------
rem Import MySQL database for webphatthalung
rem ------------------------------------------------------------
setlocal
cd /d "%~dp0.."

echo ========================================================
echo   Web Phatthalung - Database Importer
echo ========================================================
echo.

php scripts\import_db.php

if errorlevel 1 (
    echo.
    echo [ERROR] Import failed! Please check your database connection in .env.
) else (
    echo.
    echo [SUCCESS] Database imported successfully!
)

echo.
pause
