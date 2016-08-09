# 部署公钥
`sudo -Hu www-data ssh-keygen -t rsa`

# 准备钩子文件
```
mkdir /home/william/workspace/PHP/wapacom/hook
sudo chown -R www-data:www-data /home/william/workspace/PHP/wapacom
sudo -Hu www-data touch /home/william/workspace/PHP/wapacom/hook/index.php
```

```
<?php
// content of /home/william/workspace/PHP/wapacom/hook/index.php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
shell_exec('sudo ./pull.sh');
```

```
#!/bin/sh
# content of /home/william/workspace/PHP/wapacom/hook/pull.sh
cd /home/william/workspace/PHP/wapacom
/usr/bin/git pull >>/home/william/workspace/PHP/wapacom/hook/pull.log 2>&1
```

# 修改用户权限
`sudo vim /etc/sudoers`

add these code into file

`%www-data ALL=(ALL:ALL) NOPASSWD:/home/william/workspace/PHP/wapacom/hook/pull.sh`

save and exit

`:wq!`

# 修改git配置和保存git用户名密码
```
sudo -Hu www-data git config --global credential.helper store
sudo -Hu www-data git config --global user.name "pengweifu"
sudo -Hu www-data git config --global user.email "987528260@qq.com"
```

# 添加webhook
粘贴你的hook/index.php所在的网址,比如:http://www.pengwf.com/hook/index.php

# 初始化
```
sudo mv hook ../
sudo -Hu www-data git clone http://pengweifu@localhost:8080/scm/git/testAuto /home/william/workspace/PHP/wapacom/  --depth=1
sudo mv ../hook ./
```
这个时候应该会要求你输入一次git的帐号和密码，因为上面我们设置了永久保存用户名和密码，所以之后webhook再git pull就不会要求输入用户名和密码了。

