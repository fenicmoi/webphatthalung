@echo off
rem ------------------------------------------------------------
rem Start Local Development Server for webphatthalung
rem ------------------------------------------------------------
setlocal EnableDelayedExpansion
cd /d "%~dp0.."

echo ========================================================
echo   Starting Web Phatthalung Development Server
echo   URL: http://localhost:8080
echo   Press Ctrl+C to stop the server
echo ========================================================
echo.

set "PHP_CMD=php"
where php >nul 2>&1
if errorlevel 1 (
    for /d %%D in ("C:\wamp64\bin\php\php8*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" for /d %%D in ("C:\wamp64\bin\php\php7*") do if exist "%%D\php.exe" set "PHP_CMD=%%D\php.exe"
    if "!PHP_CMD!"=="php" if exist "C:\xampp\php\php.exe" set "PHP_CMD=C:\xampp\php\php.exe"
)

"!PHP_CMD!" spark serve --port=8080
pause
