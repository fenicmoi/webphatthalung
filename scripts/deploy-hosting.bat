@echo off
rem ------------------------------------------------------------
rem 1-Click Hosting Auto Deployment for webphatthalung
rem อัปเดตไฟล์เว็บไซต์ขึ้น Hosting อัตโนมัติในคลิกเดียว
rem ------------------------------------------------------------
setlocal EnableDelayedExpansion
chcp 65001 >nul
cd /d "%~dp0.."

echo ========================================================
echo   Web Phatthalung - 1-Click Hosting Deployer
echo ========================================================
set "PHP_CMD=php"
where php >nul 2>&1
if errorlevel 1 (
    for /d %%D in ("C:\wamp64\bin\php\php8*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" for /d %%D in ("C:\wamp64\bin\php\php7*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" if exist "C:\xampp\php\php.exe" set "PHP_CMD=C:\xampp\php\php.exe"
)

"!PHP_CMD!" scripts\deploy_hosting.php

echo.
pause
