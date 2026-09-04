@echo off
rem ------------------------------------------------------------
rem Start Local Development Server for webphatthalung
rem ------------------------------------------------------------
setlocal
cd /d "%~dp0.."

echo ========================================================
echo   Starting Web Phatthalung Development Server
echo   URL: http://localhost:8080
echo   Press Ctrl+C to stop the server
echo ========================================================
echo.

php spark serve --port=8080
pause
