<?php
/**
 * @Author: william
 * @Date:   2016-11-06 23:11:33
 * @Last Modified by:   pengweifu
 * @Last Modified time: 2016-11-23 11:41:12
 */
error_reporting(0);
include 'functions.php';
include 'Db.class.php';
include 'Model.class.php';
include 'Pdo.class.php';

$db = new Model('mail', 'pre_', array('db_type' => 'pdo','db_dsn' => 'sqlite:data.db','db_prefix' => 'pre_','db_charset' => 'utf8'));

$data=$db->where(array('status'=>0))->order('id')->limit(20)->select();

foreach ($data as $key => $value) {
    $to[]=$value['mail'];
}
$html='<style type="text/css">.container{width:960px;max-width:100%;margin:0 auto;font-size:14px}.container h2{background:#6caedd;line-height:200%;text-align:center;color:#fff;font-size:200%}.container a{text-decoration:none!important;color:#999}.container a img{border:0}.container img{max-width:100%}.footer{background:#6caedd;text-align:center;color:#fff;padding:20px}.footer a{color:#fff}@media screen and (max-width:767px){.company li{width:99%;list-style:none;margin-bottom:20px}.hr{background:0 0}.left,.right{width:49%;margin:0 .5%}.info img{display:block;margin:0 auto;float:none;max-width:100%}}</style><div class="container"><p><a href="http://www.clbmcl.com/index.php?m=user&c=user&a=register&referer=youxiang"><img src="http://www.clbmcl.com/Uploads/mail/img/youxiang.jpg"></a></p><div class="footer"><p><a href="http://www.clbmcl.com/index.php?m=user&c=user&a=register&referer=mail"><img src="http://www.clbmcl.com/Uploads/mail/img/footer.png" width="200"><br>Thanks for reading our leter.</a></p></div></div>';
$subject='没有套路，只有服务丨表面处理问题一键攻克！';
$arr=array(
  array('ygaaao@163.com','oakq0042','465', 'smtp.163.com','王材料'),
  array('wgmego@163.com','mqey8668','465', 'smtp.163.com','黄电镀'),
  array('siuaou@163.com','iyec4666','465', 'smtp.163.com','陈氧化'),
  array('mwwgqq@163.com','geya0888','465', 'smtp.163.com','李喷涂'),
  array('ewegcs@163.com','easi8022','465', 'smtp.163.com','刘水镀'),
  );
$num=file_get_contents('num.txt');
if (!is_numeric($num) || $num<0) {
    $num=4;
}
if (!empty($to)) {
    $status=send_mail($to, $subject, $html, '', '', $arr[$num][0], $arr[$num][1], $arr[$num][2], $arr[$num][3], $arr[$num][4]);
    if (stripos($status, ':')===false) {
        $time=time();
        foreach ($data as $value) {
            $value['status']=1;
            $value['updateTime']=$time;
            $db->save($value);
        }
        echo "success";
    } else {
        file_put_contents('error.txt', $status.date('Y-m-d H:i:s', $time).$num, FILE_APPEND);
        file_put_contents('num.txt', ($num-1));
        echo 'error';
    }
}
