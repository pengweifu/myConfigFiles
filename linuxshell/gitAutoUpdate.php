<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
// sudo vim /etc/sudoers 
// add `%www-data ALL=(ALL) ALL` to sudoers and save
$target = '/home/william/workspace/PHP/wapacom';
exec("cd $target");
$cmd = "echo '992210' | sudo -Hu www-data git pull 2>$target/log.txt";
shell_exec($cmd);
