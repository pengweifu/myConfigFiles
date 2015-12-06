@echo off
:o
cls
echo a.初始化安装并启动 s.仅启动服务 e.仅停止服务 d.卸载所有服务并删除 q.退出
choice /c asedq /n
if %errorlevel%==1 goto :install
if %errorlevel%==2 goto :start
if %errorlevel%==3 goto :stop
if %errorlevel%==4 goto :uninstall
if %errorlevel%==5 goto :ed
goto o


:install
echo 注册mysql服务...
cd D:\Server\mysql\bin
mysqld-nt.exe --install mysql --defaults-file=D:/Server/mysql/my.ini
echo 注册memcached服务器...
cd D:\Server\memcached
memcached -d install
memcached -d start
echo 注册ngrok服务...
cd D:\Server\nginx
ngrok authtoken token
goto start


:uninstall
echo 停止netbox服务器...  
taskkill /F /IM netbox.dll > nul
echo 停止 PHP FastCGI...
taskkill /F /IM xxfpm.exe > nul
echo 停止redis服务器...  
taskkill /F /IM redis-server.exe > nul
echo 停止Memcached服务...
cd D:\Server\memcached
memcached -d stop
echo 停止nginx...  
taskkill /F /IM nginx.exe > nul
echo 停止mysql服务程序...
net stop mysql
echo 停止ngrok...  
taskkill /F /IM ngrok.exe > nul
echo 卸载mysql服务...
cd D:\Server\mysql\bin
mysqld-nt.exe --remove mysql
echo 卸载Memcached服务...
cd D:\Server\memcached
memcached -d stop
memcached -d remove
sc delete "Memcached Server"
pause
goto o


:start
rem net start|findstr /i /c:"mysql">nul && net stop mysql
rem net start|findstr /i /c:"Memcached Server">nul && net stop mysql
rem echo 启动mysql服务程序...
rem net start mysql
rem echo StartingMemcached服务器...
rem net start "Memcached Server"
TASKLIST | FIND /I "ngrok.exe" && taskkill /F /IM ngrok.exe > nul
TASKLIST | FIND /I "nginx.exe" && taskkill /F /IM nginx.exe > nul
TASKLIST | FIND /I "php-cgi.exe" && taskkill /F /IM php-cgi.exe > nul
TASKLIST | FIND /I "redis-server.exe" && taskkill /F /IM redis-server.exe > nul
cd D:\Server\nginx
echo Starting Redis服务器...
RunHiddenConsole D:/Server/redis/redis-server.exe D:/Server/redis/redis.conf
echo Starting PHP和FastCGI服务器...
RunHiddenConsole D:/Server/php5.4/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/php5.4/php.ini
echo Starting nginx服务器...
RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
rem echo Starting ngrok...
rem RunHiddenConsole D:/Server/nginx/ngrok.exe -subdomain=pengweifu -config ngrok.cfg 80
rem echo 开始开启netbox服务器
rem cd D:\Server\web\
rem start Asp.exe
mshta vbscript:createobject("wscript.shell").run("cmd /c D:\Server\nginx\guard.bat",0)(window.close)
mshta vbscript:createobject("wscript.shell").run("cmd /c D:\Server\nginx\node.bat",0)(window.close)
rem pause
rem explorer http://localhost/index.php
goto o

:stop
rem net start|findstr /i /c:"mysql">nul && net stop mysql
net start|findstr /i /c:"Memcached Server">nul && net stop mysql
TASKLIST | FIND /I "ngrok.exe" && taskkill /F /IM ngrok.exe > nul
TASKLIST | FIND /I "nginx.exe" && taskkill /F /IM nginx.exe > nul
TASKLIST | FIND /I "php-cgi.exe" && taskkill /F /IM php-cgi.exe > nul
TASKLIST | FIND /I "redis-server.exe" && taskkill /F /IM redis-server.exe > nul
TASKLIST | FIND /I "netbox.dll" && taskkill /F /IM netbox.dll > nul
TASKLIST | FIND /I "node.exe" && taskkill /F /IM node.exe > nul
pause
goto o

:ed