<?
/**=======================================非对称加密解密==============================================**/
// 生成Resource类型的密钥，如果密钥文件内容被破坏，openssl_pkey_get_private函数返回false
$pkeyStr='-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAx404P7bSR/E3OwMoSAzFNJ62KPJTp2bld13lZU8KU+PCXF07
jpFPW6nFxEFkIRxI4zjaMPRQVwrYtanRD7/G5klX0RsyafCrlztVStu8lsNaReI0
ItYJuDEzaHfaDCxAr+2ufNKtx0/z3Pt0dygQwCu9xH2kUoqhKLpFMjS4/RYmmp8R
DfIhqBTVwmTxXWWyx1WXZ0naSDs32CxsAPk+X1LR327ENKjQReHARu+OPMTzsXwr
w91cxZTh8fcvRwJ9gtyObwr+NcPcEPFkiovruxxw6CjCeiYGt6I0GvsUaPegoESs
GRots4H6JMcoQ/8yOWDvMedGTc90NRIwUiRjuwIDAQABAoIBAEiDecg1YC6CaiaI
nC+qbFMVxW4VuO5hBsuclEp/MWqQnkVAH+9LwIG3rMUHWiJqC9Z+B1O4xCUNLPi+
r9jAQL/YMRAsiOJUcZYwGmtsdBh4/qncbEOocbm8L0ksHFFGF2+WuExlpn6CAETy
Hz1ZktSyUHBBk9/mvm91WMXIwNw+dgwBODizDSRIXMVaoGccScAFYEiSFAuuGqro
kPUUEpORrBLJ6OGLsmcdwuCbVTsUyKp/yPsrlkN8eQo4lomsOnJcb6GJpWGgyKaa
cij4kPIK+fp7VZuNIwQ1ccf72Yq6S1wrG8xNVl3WZDEzfCqywqHuY05Jgu5GaMac
wvteLyECgYEA/V5SWdkrLBmMto4PSlEKvSr8aV2pH0aD+sRnRpa/APWRGUkAhLJV
OwVacAVDzSGF2v1w5RWrYjhltzlvfrdT0tyYSwgDJ06y8LHrZX4Ok4/WQ8mTJNab
QvVVqK9Gyq5HeMf8JR3f3T9pADUy7FgZ7eUVMcxbIoC+VLSyPFuOgDECgYEAyZ/O
IHSl37XLWU8FeXObm8uMVWRnWohEGL5iU8KOWRCTcrtzxCHB3OvSDhbXsHuzqsaM
xT7xOAm7Kb7zldXLEMj/6DmqJb9YRZO6MXLnP+lb3lpOMeiGRqm7O4wQdaVG3PKr
Gc4DicojrrnNeKpCfndOjbycHH5gbrmp/UVbM6sCgYBnRAU+d4PgEn3Gffn/aS5+
UUHVVAY1KBFsqJYLMC+jHXNJIfbjHqgcSR9GHu82cR5UVg2oYP+cJa5XUkJ+HAw/
Gge8NQTMBYhrD6kIkasxO2Ox9ztQvWNElCeYe+/XddbNhHzhR97RFyIdopEhwPq4
ejD8PfU7B8wLTCaE8TAQUQKBgQCxa+Rw/vSwBUNp1XWVyDuYPufAhNfF/L8fnvjb
iArHKST3Ayj94E693u4647LtZA1YK9vX/mk5zKZcN+IVacqFfLpkWcn16Yj9wN9J
gDkqLmlfIsxIcrsmF1nhLoGVZNkUuJ4D2RuX9r6rlGjQ09Irg0UmyF//Wt300YBa
lNuKfQKBgCeR1R/D/MWqZa+WTSTkvVe5ZWEDTBJ+YRQOstPwT/8LetlJGEowa4dO
KJV/b0btLaxOKqmW89d8VAricDPUuosdJfHi6f3ijrQkJ4Ztaf/iVjPVXelGgxWg
9RgqRgB83ESbqsEIFZmxxQCn2HUynD4/XFALoOGx7lFRORbB1RXM
-----END RSA PRIVATE KEY-----';
$privateKey = openssl_get_privatekey($pkeyStr);
// 生成Resource类型的公钥，如果公钥文件内容被破坏，openssl_pkey_get_public函数返回false
$okeyStr='-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx404P7bSR/E3OwMoSAzF
NJ62KPJTp2bld13lZU8KU+PCXF07jpFPW6nFxEFkIRxI4zjaMPRQVwrYtanRD7/G
5klX0RsyafCrlztVStu8lsNaReI0ItYJuDEzaHfaDCxAr+2ufNKtx0/z3Pt0dygQ
wCu9xH2kUoqhKLpFMjS4/RYmmp8RDfIhqBTVwmTxXWWyx1WXZ0naSDs32CxsAPk+
X1LR327ENKjQReHARu+OPMTzsXwrw91cxZTh8fcvRwJ9gtyObwr+NcPcEPFkiovr
uxxw6CjCeiYGt6I0GvsUaPegoESsGRots4H6JMcoQ/8yOWDvMedGTc90NRIwUiRj
uwIDAQAB
-----END PUBLIC KEY-----';
// $keyData = openssl_pkey_get_details($privateKey);
// $publicKey = openssl_get_publickey($keyData['key']);
$publicKey = openssl_get_publickey($okeyStr);
($privateKey && $publicKey) or die('密钥或者公钥不可用');
$originalData = '我的帐号是:shiki,密码是:matata';
$encryptData = '';
if (openssl_private_encrypt($originalData, $encryptData, $privateKey)) {
    echo '加密成功，加密后数据(base64_encode后)为:', base64_encode($encryptData), PHP_EOL;
} else {
    die('加密失败');
}
$decryptData ='';
if (openssl_public_decrypt($encryptData, $decryptData, $publicKey)) {
    echo '解密成功，解密后数据为:', $decryptData, PHP_EOL;
} else {
    die('解密失败');
}

/**=======================================Websocket服务端==============================================**/
error_reporting(E_ALL);
set_time_limit(0);// 设置超时时间为无限,防止超时
date_default_timezone_set('Asia/shanghai');

class WebSocket {
    const LOG_PATH = './';
    const LISTEN_SOCKET_NUM = 9;

    /**
     * @var array $sockets
     *    [
     *      (int)$socket => [
     *                        info
     *                      ]
     *      ]
     *  todo 解释socket与file号对应
     */
    private $sockets = [];
    private $master;

    public function __construct($host, $port) {
        try {
            $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            // 设置IP和端口重用,在重启服务器后能重新使用此端口;
            socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);
            // 将IP和端口绑定在服务器socket上;
            socket_bind($this->master, $host, $port);
            // listen函数使用主动连接套接口变为被连接套接口，使得一个进程可以接受其它进程的请求，从而成为一个服务器进程。在TCP服务器编程中listen函数把进程变为一个服务器，并指定相应的套接字变为被动连接,其中的能存储的请求不明的socket数目。
            socket_listen($this->master, self::LISTEN_SOCKET_NUM);
        } catch (\Exception $e) {
            $err_code = socket_last_error();
            $err_msg = socket_strerror($err_code);

            $this->error([
                'error_init_server',
                $err_code,
                $err_msg
            ]);
        }

        $this->sockets[0] = ['resource' => $this->master];
        if (function_exists('posix_getpwuid')) {
        	$pid = posix_getpid();
        }else{
        	$pid = get_current_user();
        }
        $this->debug(["server: {$this->master} started,pid: {$pid}"]);

        while (true) {
            try {
                $this->doServer();
            } catch (\Exception $e) {
                $this->error([
                    'error_do_server',
                    $e->getCode(),
                    $e->getMessage()
                ]);
            }
        }
    }

    private function doServer() {
        $write = $except = NULL;
        $sockets = array_column($this->sockets, 'resource');
        $read_num = socket_select($sockets, $write, $except, NULL);
        // select作为监视函数,参数分别是(监视可读,可写,异常,超时时间),返回可操作数目,出错时返回false;
        if (false === $read_num) {
            $this->error([
                'error_select',
                $err_code = socket_last_error(),
                socket_strerror($err_code)
            ]);
            return;
        }

        foreach ($sockets as $socket) {
            // 如果可读的是服务器socket,则处理连接逻辑
            if ($socket == $this->master) {
                $client = socket_accept($this->master);
                // 创建,绑定,监听后accept函数将会接受socket要来的连接,一旦有一个连接成功,将会返回一个新的socket资源用以交互,如果是一个多个连接的队列,只会处理第一个,如果没有连接的话,进程将会被阻塞,直到连接上.如果用set_socket_blocking或socket_set_noblock()设置了阻塞,会返回false;返回资源后,将会持续等待连接。
                if (false === $client) {
                    $this->error([
                        'err_accept',
                        $err_code = socket_last_error(),
                        socket_strerror($err_code)
                    ]);
                    continue;
                } else {
                    self::connect($client);
                    continue;
                }
            } else {
                // 如果可读的是其他已连接socket,则读取其数据,并处理应答逻辑
                $bytes = @socket_recv($socket, $buffer, 2048, 0);
                if ($bytes < 9) {
                    $recv_msg = $this->disconnect($socket);
                } else {
                    if (!$this->sockets[(int)$socket]['handshake']) {
                        self::handShake($socket, $buffer);
                        continue;
                    } else {
                        $recv_msg = self::parse($buffer);
                    }
                }
                array_unshift($recv_msg, 'receive_msg');
                $msg = self::dealMsg($socket, $recv_msg);

                $this->broadcast($msg);
            }
        }
    }

    /**
     * 将socket添加到已连接列表,但握手状态留空;
     *
     * @param $socket
     */
    public function connect($socket) {
        socket_getpeername($socket, $ip, $port);
        $socket_info = [
            'resource' => $socket,
            'uname' => '',
            'handshake' => false,
            'ip' => $ip,
            'port' => $port,
        ];
        $this->sockets[(int)$socket] = $socket_info;
        $this->debug(array_merge(['socket_connect'], $socket_info));
    }

    /**
     * 客户端关闭连接
     *
     * @param $socket
     *
     * @return array
     */
    private function disconnect($socket) {
        $recv_msg = [
            'type' => 'logout',
            'content' => $this->sockets[(int)$socket]['uname'],
        ];
        unset($this->sockets[(int)$socket]);

        return $recv_msg;
    }

    /**
     * 用公共握手算法握手
     *
     * @param $socket
     * @param $buffer
     *
     * @return bool
     */
    public function handShake($socket, $buffer) {
        // 获取到客户端的升级密匙
        $line_with_key = substr($buffer, strpos($buffer, 'Sec-WebSocket-Key:') + 18);
        $key = trim(substr($line_with_key, 0, strpos($line_with_key, "\r\n")));

        // 生成升级密匙,并拼接websocket升级头
        $upgrade_key = base64_encode(sha1($key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));// 升级key的算法
        $upgrade_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $upgrade_message .= "Upgrade: websocket\r\n";
        $upgrade_message .= "Sec-WebSocket-Version: 13\r\n";
        $upgrade_message .= "Connection: Upgrade\r\n";
        $upgrade_message .= "Sec-WebSocket-Accept:" . $upgrade_key . "\r\n\r\n";

        socket_write($socket, $upgrade_message, strlen($upgrade_message));// 向socket里写入升级信息
        $this->sockets[(int)$socket]['handshake'] = true;

        socket_getpeername($socket, $ip, $port);
        $this->debug([
            'hand_shake',
            $socket,
            $ip,
            $port
        ]);

        // 向客户端发送握手成功消息,以触发客户端发送用户名动作;
        $msg = [
            'type' => 'handshake',
            'content' => 'done',
        ];
        $msg = $this->build(json_encode($msg));
        socket_write($socket, $msg, strlen($msg));
        return true;
    }

    /**
     * 解析数据
     *
     * @param $buffer
     *
     * @return bool|string
     */
    private function parse($buffer) {
        $decoded = '';
        $len = ord($buffer[1]) & 127;
        if ($len === 126) {
            $masks = substr($buffer, 4, 4);
            $data = substr($buffer, 8);
        } else if ($len === 127) {
            $masks = substr($buffer, 10, 4);
            $data = substr($buffer, 14);
        } else {
            $masks = substr($buffer, 2, 4);
            $data = substr($buffer, 6);
        }
        for ($index = 0; $index < strlen($data); $index++) {
            $decoded .= $data[$index] ^ $masks[$index % 4];
        }

        return json_decode($decoded, true);
    }

    /**
     * 将普通信息组装成websocket数据帧
     *
     * @param $msg
     *
     * @return string
     */
    private function build($msg) {
        $frame = [];
        $frame[0] = '81';
        $len = strlen($msg);
        if ($len < 126) {
            $frame[1] = $len < 16 ? '0' . dechex($len) : dechex($len);
        } else if ($len < 65025) {
            $s = dechex($len);
            $frame[1] = '7e' . str_repeat('0', 4 - strlen($s)) . $s;
        } else {
            $s = dechex($len);
            $frame[1] = '7f' . str_repeat('0', 16 - strlen($s)) . $s;
        }

        $data = '';
        $l = strlen($msg);
        for ($i = 0; $i < $l; $i++) {
            $data .= dechex(ord($msg{$i}));
        }
        $frame[2] = $data;

        $data = implode('', $frame);

        return pack("H*", $data);
    }

    /**
     * 拼装信息
     *
     * @param $socket
     * @param $recv_msg
     *          [
     *          'type'=>user/login
     *          'content'=>content
     *          ]
     *
     * @return string
     */
    private function dealMsg($socket, $recv_msg) {
        $msg_type = $recv_msg['type'];
        $msg_content = $recv_msg['content'];
        $response = [];

        switch ($msg_type) {
            case 'login':
                $this->sockets[(int)$socket]['uname'] = $msg_content;
                // 取得最新的名字记录
                $user_list = array_column($this->sockets, 'uname');
                $response['type'] = 'login';
                $response['content'] = $msg_content;
                $response['user_list'] = $user_list;
                break;
            case 'logout':
                $user_list = array_column($this->sockets, 'uname');
                $response['type'] = 'logout';
                $response['content'] = $msg_content;
                $response['user_list'] = $user_list;
                break;
            case 'user':
                $uname = $this->sockets[(int)$socket]['uname'];
                $response['type'] = 'user';
                $response['from'] = $uname;
                $response['content'] = $msg_content;
                break;
        }

        return $this->build(json_encode($response));
    }

    /**
     * 广播消息
     *
     * @param $data
     */
    private function broadcast($data) {
        foreach ($this->sockets as $socket) {
            if ($socket['resource'] == $this->master) {
                continue;
            }
            socket_write($socket['resource'], $data, strlen($data));
        }
    }

    /**
     * 记录debug信息
     *
     * @param array $info
     */
    private function debug(array $info) {
        $time = date('Y-m-d H:i:s');
        array_unshift($info, $time);

        $info = array_map('json_encode', $info);
        file_put_contents(self::LOG_PATH . 'websocket_debug.log', implode(' | ', $info) . "\r\n", FILE_APPEND);
    }

    /**
     * 记录错误信息
     *
     * @param array $info
     */
    private function error(array $info) {
        $time = date('Y-m-d H:i:s');
        array_unshift($info, $time);

        $info = array_map('json_encode', $info);
        file_put_contents(self::LOG_PATH . 'websocket_error.log', implode(' | ', $info) . "\r\n", FILE_APPEND);
    }
}

$ws = new WebSocket("127.0.0.1", "2000");

/**=======================================Websocket客户端==============================================**/
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <style>
        p {
            text-align: left;
            padding-left: 20px;
        }
    </style>
</head>
<body>
<div style="width: 800px;height: 600px;margin: 30px auto;text-align: center">
    <h1>websocket聊天室</h1>
    <div style="width: 800px;border: 1px solid gray;height: 300px;">
        <div style="width: 200px;height: 300px;float: left;text-align: left;">
            <p><span>当前在线:</span><span id="user_num">0</span></p>
            <div id="user_list" style="overflow: auto;">

            </div>
        </div>
        <div id="msg_list" style="width: 598px;border:  1px solid gray; height: 300px;overflow: scroll;float: left;">
        </div>
    </div>
    <br>
    <textarea id="msg_box" rows="6" cols="50" onkeydown="confirm(event)"></textarea><br>
    <input type="button" value="发送" onclick="send()">
</div>
</body>
</html>

<script type="text/javascript">
    // 存储用户名到全局变量,握手成功后发送给服务器
    var uname = prompt('请输入用户名', 'user' + uuid(8, 16));
    var ws = new WebSocket("ws://127.0.0.1:2000");
    ws.onopen = function () {
        var data = "系统消息：建立连接成功";
        listMsg(data);
    };

    /**
     * 分析服务器返回信息
     *
     * msg.type : user 普通信息;system 系统信息;handshake 握手信息;login 登陆信息; logout 退出信息;
     * msg.from : 消息来源
     * msg.content: 消息内容
     */
    ws.onmessage = function (e) {
        var msg = JSON.parse(e.data);
        var sender, user_name, name_list, change_type;
        console.log(msg)
        switch (msg.type) {
            case 'system':
                sender = '系统消息: ';
                break;
            case 'user':
                sender = msg.from + ': ';
                break;
            case 'handshake':
                var user_info = {'type': 'login', 'content': uname};
                sendMsg(user_info);
                return;
            case 'login':
            case 'logout':
                user_name = msg.content;
                name_list = msg.user_list;
                change_type = msg.type;
                dealUser(user_name, change_type, name_list);
                return;
        }

        var data = sender + msg.content;
        listMsg(data);
    };

    ws.onerror = function () {
        var data = "系统消息 : 出错了,请退出重试.";
        listMsg(data);
    };

    /**
     * 在输入框内按下回车键时发送消息
     *
     * @param event
     *
     * @returns {boolean}
     */
    function confirm(event) {
        var key_num = event.keyCode;
        if (13 == key_num) {
            send();
        } else {
            return false;
        }
    }

    /**
     * 发送并清空消息输入框内的消息
     */
    function send() {
        var msg_box = document.getElementById("msg_box");
        var content = msg_box.value;
        var reg = new RegExp("\r\n", "g");
        content = content.replace(reg, "");
        var msg = {'content': content.trim(), 'type': 'user'};
        sendMsg(msg);
        msg_box.value = '';
        // todo 清除换行符
    }

    /**
     * 将消息内容添加到输出框中,并将滚动条滚动到最下方
     */
    function listMsg(data) {
        var msg_list = document.getElementById("msg_list");
        var msg = document.createElement("p");

        msg.innerHTML = data;
        msg_list.appendChild(msg);
        msg_list.scrollTop = msg_list.scrollHeight;
    }

    /**
     * 处理用户登陆消息
     *
     * @param user_name 用户名
     * @param type  login/logout
     * @param name_list 用户列表
     */
    function dealUser(user_name, type, name_list) {
        var user_list = document.getElementById("user_list");
        var user_num = document.getElementById("user_num");
        while(user_list.hasChildNodes()) {
            user_list.removeChild(user_list.firstChild);
        }

        for (var index in name_list) {
            var user = document.createElement("p");
            user.innerHTML = name_list[index];
            user_list.appendChild(user);
        }
        user_num.innerHTML = name_list.length;
        user_list.scrollTop = user_list.scrollHeight;

        var change = type == 'login' ? '上线' : '下线';

        var data = '系统消息: ' + user_name + ' 已' + change;
        listMsg(data);
    }

    /**
     * 将数据转为json并发送
     * @param msg
     */
    function sendMsg(msg) {
        var data = JSON.stringify(msg);
        ws.send(data);
    }

    /**
     * 生产一个全局唯一ID作为用户名的默认值;
     *
     * @param len
     * @param radix
     * @returns {string}
     */
    function uuid(len, radix) {
        var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
        var uuid = [], i;
        radix = radix || chars.length;

        if (len) {
            for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random() * radix];
        } else {
            var r;

            uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
            uuid[14] = '4';

            for (i = 0; i < 36; i++) {
                if (!uuid[i]) {
                    r = 0 | Math.random() * 16;
                    uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
                }
            }
        }

        return uuid.join('');
    }
</script>
<?php

/**=======================================ajax跨域==============================================**/

header('Access-Control-Allow-Origin: http://www.b.com');
$callback = $_GET['callback'];
$result   = ['data'=>'data','errno'=>500];
$post=empty($_POST)?json_decode(file_get_contents('php://input'), 1):$_POST;
if (!empty($post)) {
    if ($post['email']=='1@qq.com' && $post['password']=='111111') {
        $result = [];
    } else {
        $result = [];
    }
}
if (!empty($callback)) {
    echo $callback . '(' . json_encode($result) . ')';
} else {
    echo json_encode($result);
}
