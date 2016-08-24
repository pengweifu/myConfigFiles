<?php
$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $data);
$data = array_merge($_GET, $_POST, $data);
if (empty($data)) {
	echo phpinfo();
} else {
	if ($data['branch'] == 'master') {
		shell_exec('sudo ./pull.sh');//shell_exec('sudo /home/william/Git/weike/hook/pull.sh');
	} else {
		file_put_contents('pull.log', time() . "\n", FILE_APPEND);
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
