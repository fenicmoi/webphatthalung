@echo off
rem ------------------------------------------------------------
rem Import MySQL database for webphatthalung project
rem Reads connection values from .env in the project root
rem ------------------------------------------------------------
setlocal EnableDelayedExpansion

rem Load .env file (key=value lines)
for /f "usebackq tokens=1,2 delims==" %%A in ("..\.env") do (
    set "key=%%A"
    set "value=%%B"
    if /i "!key!"=="database.default.hostname" set "DBHOST=!value!"
    if /i "!key!"=="database.default.username" set "DBUSER=!value!"
    if /i "!key!"=="database.default.password" set "DBPASS=!value!"
    if /i "!key!"=="database.default.database" set "DBNAME=!value!"
)

if "%DBHOST%"=="" (echo Missing DB host in .env & exit /b 1)
if "%DBUSER%"=="" (echo Missing DB user in .env & exit /b 1)
if "%DBPASS%"=="" (echo Missing DB password in .env & exit /b 1)
if "%DBNAME%"=="" (echo Missing DB name in .env & exit /b 1)

rem Run mysql import
mysql -h %DBHOST% -u %DBUSER% -p%DBPASS% %DBNAME% < "..\db\webphatthalung.sql"
if errorlevel 1 (
    echo.
    echo *** Import failed! Check your credentials and MySQL service. ***
) else (
    echo.
    echo *** Import completed successfully. ***
)
pause
