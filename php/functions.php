<?php
/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 *
 * @param string $name 字符串
 * @param int    $type 转换类型
 *
 * @return string
 */
function parse_name($name, $type = 0)
{
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {
            return strtoupper($match[1]);
        }, $name));
    } else {
        return strtolower(trim(preg_replace('/[A-Z]/', '_\\0', $name), '_'));
    }
}

/**
 * XML编码
 *
 * @param mixed  $data     数据
 * @param string $root     根节点名
 * @param string $item     数字索引的子节点名
 * @param string $attr     根节点属性
 * @param string $id       数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 *
 * @return string
 */
function xml_encode($data, $root = 'think', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
{
    if (is_array($attr)) {
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr = trim($attr);
    $attr = empty($attr) ? '' : " {$attr}";
    $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml .= "<{$root}{$attr}>";
    $xml .= data_to_xml($data, $item, $id);
    $xml .= "</{$root}>";

    return $xml;
}

/**
 * 获取客户端IP地址
 *
 * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 *
 * @return mixed
 */
function get_client_ip($type = 0)
{
    $type = $type ? 1 : 0;
    static $ip = null;
    if ($ip !== null) {
        return $ip[$type];
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) {
            unset($arr[$pos]);
        }
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if ($ip == '127.0.0.1') {
        $socket = socket_create(AF_INET, SOCK_STREAM, 6);
        $ret = socket_connect($socket, 'ns1.dnspod.net', 6666);
        $ip = socket_read($socket, 16);
        socket_close($socket);
    }
    // IP地址合法验证
    $long = sprintf('%u', ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);

    return $ip[$type];
}

/**
 * 根据PHP各种类型变量生成唯一标识号.
 *
 * @param mixed $mix 变量
 *
 * @return string
 */
function to_guid_string($mix)
{
    if (is_object($mix)) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix).strval($mix);
    } else {
        $mix = serialize($mix);
    }

    return md5($mix);
}


// 系统邮件发送函数 
// @param string $to 接收邮件者邮箱 
// @param string $name 接收邮件者名称 
// @param string $subject 邮件主题 
// @param string $body 邮件内容 
// @param string $attachment 附件列表 
// @茉莉清茶 57143976@qq.com

function send_mail($to = array(), $subject = '', $body = '', $name = '', $attachment = null, $from = '710627107@qq.com', $pwd = '123456Aa', $port = '465', $server = 'smtp.qq.com', $from_name = null)
{
    $from_email = $from;
    $from_name = $from_name?$from_name:$from;
    $reply_email = '';
    $reply_name = '';
    include 'Mail.class.php';
    $mail             = new PHPMailer();
    $mail->CharSet    = 'UTF-8';
    $mail->IsSMTP();
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host       = $server;
    $mail->Port       = $port;
    $mail->Username   = $from;
    $mail->Password   = $pwd;
    $mail->SetFrom($from_email, $from_name);
    $replyEmail       = $reply_email?$reply_email:$from_email;
    $replyName        = $reply_name?$reply_name:$from_name;
    if ($to == '') {
        $to = array($from);//邮件地址为空时，默认使用后台默认邮件测试地址
    }
    if ($subject == '') {
        $subject = $from;//邮件主题为空时，默认使用网站标题
    }
    if ($body == '') {
        $body = '测试邮件';//邮件内容为空时，默认使用网站描述
    }
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);//解析
    foreach ($to as $k => $v) {
        $mail->AddAddress($v);     //设置收件的地址
    }
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : 'error:'.$mail->ErrorInfo;
}
