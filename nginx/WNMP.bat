@echo off
:o
cls
echo a.��ʼ����װ������ s.���������� e.��ֹͣ���� d.ж�����з���ɾ�� x.����MONO(ASP.NET���ܲ�����) o.��վ����Ŀ¼ n.����Nginx q.�˳�
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
echo ע��mysql����...
cd D:\Server\mysql\bin
mysqld-nt.exe --install mysql --defaults-file=D:/Server/mysql/my.ini
echo ע��memcached������...
cd D:\Server\memcached
memcached -d install
memcached -d start
echo ע��ngrok����...
cd D:\Server\nginx
ngrok authtoken ngroksignal
goto start


:uninstall
echo ֹͣnetbox������...  
taskkill /F /IM netbox.dll > nul
echo ֹͣ PHP FastCGI...
taskkill /F /IM php-cgi.exe > nul
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
echo ֹͣaspx��mono����...
taskkill /F /IM fastcgi-mono-server2.exe > nul
taskkill /F /IM fastcgi-mono-server4.exe > nul
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
echo ����mysql�������...
net start mysql
echo StartingMemcached������...
net start "Memcached Server"
echo Starting Redis������...
cd D:\Server\redis
RunHiddenConsole D:/Server/redis/redis-server.exe
echo Starting PHP��FastCGI������...
cd D:\Server\nginx
RunHiddenConsole D:/Server/php5.4/php-cgi.exe -b 127.0.0.1:9000 -c D:/Server/php5.4/php.ini
echo Starting nginx������...
RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
echo Starting ngrok...
rem RunHiddenConsole D:/Server/nginx/ngrok.exe -subdomain=pengweifu -config ngrok.cfg 80
echo ��ʼ����netbox������
cd D:\Server\web\
start Asp.exe
pause
explorer http://localhost/index.php
goto o


:stop
echo ֹͣnetbox������...  
taskkill /F /IM netbox.dll > nul
echo ֹͣ PHP FastCGI...
taskkill /F /IM php-cgi.exe > nul
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
echo ֹͣaspx��mono����...
taskkill /F /IM fastcgi-mono-server2.exe > nul
taskkill /F /IM fastcgi-mono-server4.exe > nul
pause
goto o


:mono
echo ע��aspx��mono����...
cd D:\Server\nginx
RunHiddenConsole D:/Server/mono/4.0/fastcgi-mono-server4 /socket=tcp:127.0.0.1:9001 /root="D:\Server\web\wwwroot" /applications=/:. /multiplex=True
pause
goto o


:root
explorer /select, D:\Server\web\wwwroot\index.php
pause
goto o

:nginx
echo ֹͣnginx...  
taskkill /F /IM nginx.exe > nul
echo Starting nginx������...
cd D:\Server\nginx
RunHiddenConsole D:/Server/nginx/nginx.exe -p D:/Server/nginx
pause
goto o

:ed