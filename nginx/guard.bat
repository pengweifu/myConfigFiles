@echo off
set logFile=D:\Server\nginx\logs\php-cgi.log
:guard
ping 127.0.0.1 -n 4 >nul
tasklist | find /i "php-cgi.exe" 1>nul 2>nul
if "0"=="%errorlevel%" (
	netstat -ano | find "9000" 1>nul 2>nul
	if "0"=="%errorlevel%" (
		echo [%date:~0,10% %time:~0,8%] php-cgi is running ....
	) else (
		echo  php-cgi is running, but it cann't to work ....
		echo [%date% %time%] [notice] php-cgi is running, but it cann't to work ....>>%logFile%
		cd D:\Server\nginx
		RunHiddenConsole D:/Server/php5.4/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/php5.4/php.ini
	)
) else (
	echo [%date% %time%] php-cgi is stoped.
	echo [%date% %time%] [error] php-cgi is stoped.>>%logFile%
	cd D:\Server\nginx
	RunHiddenConsole D:/Server/php5.4/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/php5.4/php.ini
)
goto guard