@echo off
:o
cls
echo a.��ʼ����װ������ s.���������� e.��ֹͣ���� d.ж�����з���ɾ�� q.�˳�
choice /c asedq /n
if %errorlevel%==1 goto :install
if %errorlevel%==2 goto :start
if %errorlevel%==3 goto :stop
if %errorlevel%==4 goto :uninstall
if %errorlevel%==5 goto :ed
goto o


:install
echo ע��mysql����...
cd D:\Server\mysql\bin
mysqld-nt.exe --install mysql --defaults-file=D:/Server/mysql/my.ini
echo ע��memcached������...
cd D:\Server\memcached
memcached -d install
memcached -d start
echo ע��ngrok����...
cd D:\Server\nginx
ngrok authtoken token
goto start


:uninstall
echo ֹͣnetbox������...  
taskkill /F /IM netbox.dll > nul
echo ֹͣ PHP FastCGI...
taskkill /F /IM xxfpm.exe > nul
echo ֹͣredis������...  
taskkill /F /IM redis-server.exe > nul
echo ֹͣMemcached����...
cd D:\Server\memcached
memcached -d stop
echo ֹͣnginx...  
taskkill /F /IM nginx.exe > nul
echo ֹͣmysql�������...
net stop mysql
echo ֹͣngrok...  
taskkill /F /IM ngrok.exe > nul
echo ж��mysql����...
cd D:\Server\mysql\bin
mysqld-nt.exe --remove mysql
echo ж��Memcached����...
cd D:\Server\memcached
memcached -d stop
memcached -d remove
sc delete "Memcached Server"
pause
goto o


:start
rem net start|findstr /i /c:"mysql">nul && net stop mysql
rem net start|findstr /i /c:"Memcached Server">nul && net stop mysql
rem echo ����mysql�������...
rem net start mysql
rem echo StartingMemcached������...
rem net start "Memcached Server"
TASKLIST | FIND /I "ngrok.exe" && taskkill /F /IM ngrok.exe > nul
TASKLIST | FIND /I "nginx.exe" && taskkill /F /IM nginx.exe > nul
TASKLIST | FIND /I "php-cgi.exe" && taskkill /F /IM php-cgi.exe > nul
TASKLIST | FIND /I "redis-server.exe" && taskkill /F /IM redis-server.exe > nul
cd D:\Server\nginx
echo Starting Redis������...
RunHiddenConsole D:/Server/redis/redis-server.exe D:/Server/redis/redis.conf
echo Starting PHP��FastCGI������...
RunHiddenConsole D:/Server/php5.4/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/php5.4/php.ini
echo Starting nginx������...
RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
rem echo Starting ngrok...
rem RunHiddenConsole D:/Server/nginx/ngrok.exe -subdomain=pengweifu -config ngrok.cfg 80
rem echo ��ʼ����netbox������
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