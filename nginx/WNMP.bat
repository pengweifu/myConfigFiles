@ echo off
%1 %2
ver|find "5.">nul&&goto :o
mshta vbscript:createobject("shell.application").shellexecute("%~s0","goto :o","","runas",1)(window.close)&goto :eof

:o
cls
echo a.初始化安装并启动 s.仅启动服务 e.仅停止服务 d.卸载所有服务并删除 n.重启nginx q.退出
choice /c asednq /n
if %errorlevel%==1 goto :install
if %errorlevel%==2 goto :start
if %errorlevel%==3 goto :stop
if %errorlevel%==4 goto :uninstall
if %errorlevel%==5 goto :restart
if %errorlevel%==6 goto :ed
goto o


:install
echo 注册mysql服务...
cd /D D:\Server\mysql\bin
mysqld-nt.exe --install mysql --defaults-file=D:/Server/mysql/my.ini
echo 注册memcached服务器...
cd /D D:\Server\memcached
memcached -d install
echo 注册Redis服务器...
cd /D D:\Server\REDIS
redis-server --service-install redis.windows-service.conf
echo 注册ngrok服务...
cd /D D:\Server\nginx
RunHiddenConsole D:/Server/nginx/ngrok.exe authtoken 7ot9xUgfL8ZXDmLa9m5y2_5XkYFdLSMQyRV4q2JSGBW
goto start


:uninstall
echo 停止netbox服务器...  
taskkill /F /IM netbox.dll > nul
echo 停止 PHP FastCGI...
taskkill /F /IM php-cgi.exe > nul
echo 停止redis服务器...  
taskkill /F /IM redis-server.exe > nul
echo 停止Memcached服务...
cd /D D:\Server\memcached
memcached -d stop
echo 停止nginx...  
taskkill /F /IM nginx.exe > nul
echo 停止mysql服务程序...
net stop mysql
echo 停止ngrok...  
taskkill /F /IM ngrok.exe > nul
echo 卸载mysql服务...
cd /D D:\Server\mysql\bin
mysqld-nt.exe --remove mysql
echo 卸载Memcached服务...
cd /D D:\Server\memcached
memcached -d stop
memcached -d remove
sc delete "Memcached Server"
echo 卸载Redis服务...
cd /D D:\Server\REDIS
redis-server --service-uninstall
pause
goto o


:start
echo 启动mysql服务程序...
rem start|findstr /i /c:"Memcached Server">nul || net start "Memcached Server"
rem RunHiddenConsole D:/Server/PHP7.0/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/PHP7.0/php.ini
rem cd /D D:\Server\web\
rem start Asp.exe
net start|findstr /i /c:"mysql">nul || net start mysql
cd /D D:\Server\nginx
echo 启动ngrok内网穿透程序...
TASKLIST | FIND /I "ngrok.exe" || RunHiddenConsole D:/Server/nginx/ngrok.exe http 80
echo 启动nginx Web服务 ...
TASKLIST | FIND /I "nginx.exe" || RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
echo 启动redis 数据库 ...
cd /D D:\Server\REDIS
TASKLIST | FIND /I "redis-server.exe" || redis-server --service-start
echo 启动PHP7 守护程序 ..
tasklist /FI "WINDOWTITLE eq 管理员:  guard" | find /I "cmd.exe" || mshta vbscript:createobject("wscript.shell").run("cmd /c D:\Server\nginx\guard.bat",0)(window.close)
rem mshta vbscript:createobject("wscript.shell").run("cmd /c D:\Server\nginx\node.bat",0)(window.close)
rem pause
rem explorer http://localhost/index.php
goto o

:restart
TASKLIST | FIND /I "nginx.exe" && taskkill /F /IM nginx.exe > nul
TASKLIST | FIND /I "xxfpm.exe" && taskkill /F /IM xxfpm.exe > nul
TASKLIST | FIND /I "php-cgi.exe" && taskkill /F /IM php-cgi.exe > nul
cd /D D:\Server\nginx
RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
goto o

:stop
rem net start|findstr /i /c:"mysql">nul && net stop mysql
tasklist /FI "WINDOWTITLE eq 管理员:  guard" | find /I "cmd.exe" && taskkill /F /T /FI "WINDOWTITLE eq 管理员:  guard" > nul
tasklist /FI "WINDOWTITLE eq guard" | find /I "cmd.exe" && taskkill /F /T /FI "WINDOWTITLE eq guard" > nul
net start|findstr /i /c:"Memcached Server">nul && net stop "Memcached Server"
TASKLIST | FIND /I "choice.exe" && taskkill /F /IM choice.exe > nul
TASKLIST | FIND /I "ngrok.exe" && taskkill /F /IM ngrok.exe > nul
TASKLIST | FIND /I "nginx.exe" && taskkill /F /IM nginx.exe > nul
TASKLIST | FIND /I "redis-server.exe" && cd /D D:\Server\REDIS && redis-server --service-stop > nul
TASKLIST | FIND /I "netbox.dll" && taskkill /F /IM netbox.dll > nul
TASKLIST | FIND /I "node.exe" && taskkill /F /IM node.exe > nul
TASKLIST | FIND /I "php-cgi.exe" && taskkill /F /IM php-cgi.exe > nul
pause
goto o

:ed