@echo off
chcp 65001 >nul
title phpdts Game Server

echo ========================================
echo   phpdts Game Server - Starting...
echo ========================================
echo.

set PHP=D:\phpstudy_pro\Extensions\php\php7.3.4nts\php.exe
set MYSQLD=D:\phpstudy_pro\Extensions\MySQL8.0.12\bin\mysqld.exe
set MYSQL_INI=D:\phpstudy_pro\Extensions\MySQL8.0.12\my.ini
set GAME_DIR=%~dp0..

echo [1/3] Starting MySQL...
start "" /B "%MYSQLD%" --defaults-file="%MYSQL_INI%" --console
timeout /t 4 /nobreak >nul
echo        MySQL started (port 3306)

echo [2/3] Starting PHP dev server...
start "" /B "%PHP%" -S localhost:8080 -t "%GAME_DIR%"
timeout /t 2 /nobreak >nul
echo        PHP server started (port 8080)

echo [3/3] Starting Management Panel...
start "" /B python "%~dp0app.py"
timeout /t 2 /nobreak >nul
echo        Manager started (port 5099)

echo.
echo ========================================
echo   All services started!
echo   Game:    http://localhost:8080/
echo   Manager: http://localhost:5099/
echo ========================================
echo.
echo Press any key to STOP all services...
pause >nul

call "%~dp0stop.bat"
