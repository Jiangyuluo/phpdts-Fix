@echo off
chcp 65001 >nul
title Stopping phpdts Services...

echo ========================================
echo   phpdts Game Server - Stopping...
echo ========================================
echo.

echo Stopping PHP processes...
taskkill /F /IM php.exe >nul 2>&1
echo        PHP stopped

echo Stopping MySQL...
taskkill /F /IM mysqld.exe >nul 2>&1
echo        MySQL stopped

echo Stopping Management Panel...
taskkill /F /FI "WINDOWTITLE eq phpdts*" >nul 2>&1
echo        Manager stopped

echo.
echo ========================================
echo   All services stopped.
echo ========================================
echo.

pause
