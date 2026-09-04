@echo off
rem ------------------------------------------------------------
rem Export MySQL database and seed data for webphatthalung
rem ------------------------------------------------------------
setlocal
cd /d "%~dp0.."

echo ========================================================
echo   Web Phatthalung - Database and Seed Exporter
echo ========================================================
echo.

php scripts\export_db.php

if errorlevel 1 (
    echo.
    echo [ERROR] Export failed! Make sure PHP and MySQL are running.
) else (
    echo.
    echo [SUCCESS] Export finished! All tables and seed files are up to date.
)

echo.
pause
