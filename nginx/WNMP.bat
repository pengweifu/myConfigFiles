@echo off
:o
cls
echo a.初始化安装并启动 s.仅启动服务 e.仅停止服务 d.卸载所有服务并删除 x.启动MONO(ASP.NET可能不兼容) o.打开站点主目录 n.重启Nginx q.退出
choice /c asedxonq /n
if %errorlevel%==1 goto :install
if %errorlevel%==2 goto :start
if %errorlevel%==3 goto :stop
if %errorlevel%==4 goto :uninstall
if %errorlevel%==5 goto :mono
if %errorlevel%==6 goto :root
if %errorlevel%==7 goto :nginx
if %errorlevel%==8 goto :ed
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
ngrok authtoken ngroksignal
goto start


:uninstall
echo 停止netbox服务器...  
taskkill /F /IM netbox.dll > nul
echo 停止 PHP FastCGI...
taskkill /F /IM php-cgi.exe > nul
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
echo 停止aspx的mono服务...
taskkill /F /IM fastcgi-mono-server2.exe > nul
taskkill /F /IM fastcgi-mono-server4.exe > nul
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
echo 启动mysql服务程序...
net start mysql
echo StartingMemcached服务器...
net start "Memcached Server"
echo Starting Redis服务器...
cd D:\Server\redis
RunHiddenConsole D:/Server/redis/redis-server.exe
echo Starting PHP和FastCGI服务器...
cd D:\Server\nginx
RunHiddenConsole D:/Server/php5.4/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/php5.4/php.ini
echo Starting nginx服务器...
RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
echo Starting ngrok...
rem RunHiddenConsole D:/Server/nginx/ngrok.exe -subdomain=pengweifu -config ngrok.cfg 80
echo 开始开启netbox服务器
cd D:\Server\web\
start Asp.exe
pause
explorer http://localhost/index.php
goto o


:stop
echo 停止netbox服务器...  
taskkill /F /IM netbox.dll > nul
echo 停止 PHP FastCGI...
taskkill /F /IM php-cgi.exe > nul
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
echo 停止aspx的mono服务...
taskkill /F /IM fastcgi-mono-server2.exe > nul
taskkill /F /IM fastcgi-mono-server4.exe > nul
pause
goto o


:mono
echo 注册aspx的mono服务...
cd D:\Server\nginx
RunHiddenConsole D:/Server/mono/4.0/fastcgi-mono-server4 /socket=tcp:127.0.0.1:9001 /root="D:\Server\web\wwwroot" /applications=/:. /multiplex=True
pause
goto o


:root
explorer /select, D:\Server\web\wwwroot\index.php
pause
goto o

:nginx
echo 停止nginx...  
taskkill /F /IM nginx.exe > nul
echo Starting nginx服务器...
cd D:\Server\nginx
RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
pause
goto o

:ed