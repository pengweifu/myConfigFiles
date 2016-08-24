<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $data);
$data = array_merge($_GET, $_POST, $data);
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
if (empty($data)) {
	$data=$redis->lRange('commit', 0, -1);
  $html='<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Commit Lists</title></head><body><link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css"><script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script><script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script><div class="container-fluid"><div class="table-responsive"><table class="table table-striped"><thead><tr><th>#</th><th>Author</th><th>Email</th><th>Branch</th><th>Time</th><th>Description</th></tr></thead><tbody>';
  if ($data) {
    foreach ($data as $key => $value) {
      $value=json_decode($value,1);
      $html.='<tr>';
      $html.='<td>'.$key.'</td>';
      isset($value['author']) && $html.='<td>'.$value['author'].'</td>';
      isset($value['mail']) && $html.='<td>'.$value['mail'].'</td>';
      isset($value['branch']) && $html.='<td>'.$value['branch'].'</td>';
      isset($value['time']) && $html.='<td>'.$value['time'].'</td>';
      isset($value['description']) && $html.='<td>'.$value['description'].'</td>';
      $html.='</tr>';
    }
  }
  $html.='</tbody></table></div></div></body></html>';
  echo $html;
} else {
	$redis->lPush('commit', json_encode($data));
	if ($data['branch'] == 'master') {
		shell_exec('sudo ./pull.sh');//shell_exec('sudo /home/william/Git/weike/hook/pull.sh');
	}
}


/*
* contents of pull.sh
*
* #!/bin/sh
* # sudo -Hu www-data pwd && cd /home/william/Git/weike && /usr/bin/git pull >>/home/william/Git/weike/hook/pull.log 2>&1
* cd /home/william/workspace/PHP/wapacom
* /usr/bin/sudo -Hu www-data /usr/bin/git pull >>/home/william/workspace/PHP/wapacom/hook/pull.log 2>&1
*/
