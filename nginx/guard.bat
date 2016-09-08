@echo off
title guard
set logFile=D:\Server\nginx\logs\php-exit.log
:guard
TASKLIST | FIND /I "php-cgi.exe" 1>nul 2>nul
if "0"=="%errorlevel%" (
	netstat -ano | find "9000" 1>nul 2>nul
	if "0"=="%errorlevel%" (
		echo php is running
	) else (
		cd /D D:\Server\nginx
		echo [%date% %time%] [notice] php-cgi is running, but it cann't to work ....>>%logFile%
		cd /D D:\Server\nginx
		RunHiddenConsole D:/Server/PHP7.0/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/PHP7.0/php.ini
	)
) else (
	cd /D D:\Server\nginx
	echo [%date% %time%] [error] php-cgi is stoped.>>%logFile%
	cd /D D:\Server\nginx
	RunHiddenConsole D:/Server/PHP7.0/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/PHP7.0/php.ini
)
choice /t 10 /d y /n >nul
goto guard