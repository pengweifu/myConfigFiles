<?php
// this file is located in /home/william/workspace/PHP/wapacom/hook/
error_reporting(E_ALL);
ini_set('display_errors', 'On');
// sudo vim /etc/sudoers 
// add `%www-data ALL=(ALL:ALL) NOPASSWD:/home/william/workspace/PHP/wapacom/hook/pull.sh` to sudoers and save
shell_exec('sudo ./pull.sh');

/*
* contents of pull.sh
*
* #!/bin/sh
* cd /home/william/workspace/PHP/wapacom
* /usr/bin/git pull >>/home/william/workspace/PHP/wapacom/hook/pull.log 2>&1
*/
