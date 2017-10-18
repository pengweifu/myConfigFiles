<?php
/**
 * @Author: pengweifu
 * @Date:   2017-10-14 21:30:13
 * @Last Modified by:   pengweifu
 * @Last Modified time: 2017-10-18 23:04:45
 */
/**
*
*/
session_start();
error_reporting(E_ALL &~ E_NOTICE);
class Oauth
{
    public function __construct($action)
    {
        switch ($action) {
            case 'authorize':
            case 'accessToken':
            case 'getTokenInfo':
            case 'revokeAuth':
                $this->$action();
                break;
            default:
                echo "unkown action";
                break;
        }
    }
    private function _generateCode($appId, $userId)
    {
        $code=$this->_randString();
        file_put_contents($appId.$code.'.txt', $userId);
        return $code;
    }
    private function _randString($length = 16)
    {
        $string = '';
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        return $string;
    }
    public function authorize()
    {
        $method=$_SERVER['REQUEST_METHOD'];
        if (strtolower($method)=='post') {
            $appId=$_GET['appId'];
            $responseType=$_GET['responseType'];
            $redirectUri=parse_url(urldecode($_GET['redirectUri']));
            parse_str($redirectUri['query'], $query);
            $query['code']=$this->_generateCode($appId, $_SESSION['uid']);
            header('location:'.$redirectUri['path'].'?'.http_build_query($query));
            exit();
        } else {
            if (empty($_SESSION['uid'])) {
                $_SESSION['refer']=$_SERVER['REQUEST_URI'];
                header('location:index.php?c=user&a=login');
                exit();
            }
            $html='<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><title>Document</title></head><body><form method="post"><button type="submit">Confirm</button><button type="button">Reject</button></form></body></html>';
            echo $html;
            exit();
        }
    }
    public function accessToken()
    {
        $method=$_SERVER['REQUEST_METHOD'];
        if (strtolower($method)=='post') {
            $post=isset($_POST['appId'])?$_POST:json_decode(file_get_contents('php://input'), 1);
            $appId=$post['appId'];
            $appSecret=$post['appSecret'];
            $redirectUri=$post['redirectUri'];
            $code=$post['code'];
            if (empty($appId)) {
                echo "invalid APPID";
                return;
            }
            if ($appSecret!='*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9') {
                echo "invalid APP SECRET";
                return;
            }
            if ($redirectUri!='http://www.b.com/index.php?c=client&a=callback') {
                echo "invalid Callback URL";
                return;
            }
            $userId=@file_get_contents($appId.$code.'.txt');
            @unlink($appId.$code.'.txt');
            @unlink($appId.'.txt');
            if (empty($userId)) {
                echo "invalid code";
                return;
            }
            $arr=[
                'access_token'=>$this->_randString(),
                'expire'=>7200,
                'openid'=>$userId,
            ];
            file_put_contents($appId.'.txt', time().$arr['access_token'].$userId);
            header('Content-type:application/json;charset=UTF-8');
            echo json_encode($arr);
            exit();
        } else {
            echo "invalid action";
            return;
        }
    }
    public function getTokenInfo($accessToken)
    {
        //
    }
    public function revokeAuth($accessToken)
    {
        //
    }
}
/**
*
*/
class User
{
    public function __construct($action)
    {
        switch ($action) {
            case 'login':
                $this->$action();
                break;
            default:
                echo "unkown action";
                break;
        }
    }
    public function login()
    {
        $method=$_SERVER['REQUEST_METHOD'];
        if (strtolower($method)=='post') {
            $_SESSION['uid']=$_POST['uid'];
            $url=!empty($_SESSION['refer'])?$_SESSION['refer']:'index.php';
            header('location:'.$url);
            exit();
        }
        $html='<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><title>Document</title></head><body><form method="post"><input name="uid"><button type="submit">Login</button><button type="reset">Reset</button></form></body></html>';
        echo $html;
        exit();
    }
}
/**
*
*/
class Client
{
    private $_appId='abc';
    private $_appSecret='*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9';
    private $_responseType='CODE';
    private $_redirectUri='http://www.b.com/index.php?c=client&a=callback';
    public function __construct($action)
    {
        switch ($action) {
            case 'prepare':
            case 'callback':
                $this->$action();
                break;
            default:
                echo "unkown action";
                break;
        }
    }
    public function prepare()
    {
        $query=[
            'c'=>'oauth',
            'a'=>'authorize',
            'appId'=>$this->_appId,
            'responseType'=>$this->_responseType,
            'redirectUri'=>$this->_redirectUri,
        ];
        header('location:http://m.b.com/index.php?'.http_build_query($query));
        exit();
    }
    public function callback()
    {
        $query=[
            'c'=>'oauth',
            'a'=>'accessToken',
            'appId'=>$this->_appId,
            'appSecret'=>$this->_appSecret,
            'redirectUri'=>$this->_redirectUri,
            'code'=>$_GET['code'],
        ];
        $result=$this->_getUrlContents('http://m.b.com/index.php', true, $query);
        if (is_array($result)) {
            print_r($result);
        } else {
            var_dump($result);
        }
    }
    private function _getUrlContents($url, $ifpost = 0, $data = '', $cookie = '', $outheader = false, $time = 30)
    {
        $header = array("Connection: Keep-Alive", "Accept: text/html, application/xhtml+xml, */*", "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3", "User-Agent: Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)", "Referer: http://developer.baidu.com/");
        $data = is_array($data) ? json_encode($data) : $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $outheader);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $ifpost && curl_setopt($ch, CURLOPT_POST, $ifpost);
        $ifpost && curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        $cookie && curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        $cookie && curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        $r = curl_exec($ch);
        if (curl_errno($ch)) {
            $r = 'Error(' . curl_errno($ch) . '):' . curl_error($ch);
        }
        curl_close($ch);
        if (preg_match('/^\xEF\xBB\xBF/', $r)) {
            $r = substr($r, 3);
        }
        if (is_null(json_decode($r, true))) {
            return $r;
        } else {
            return json_decode($r, true);
        }
    }
}
$controller=isset($_POST['c'])?ucfirst($_POST['c']):isset($_GET['c'])?ucfirst($_GET['c']):'Client';
$action=isset($_POST['a'])?$_POST['a']:isset($_GET['a'])?$_GET['a']:'prepare';
if (!isset($_POST['c']) && !isset($_GET['c'])) {
    $post=file_get_contents('php://input');
    $post=json_decode($post, 1);
    $controller=isset($post['c'])?$post['c']:$controller;
    $action=isset($post['a'])?$post['a']:$action;
}
new $controller($action);
