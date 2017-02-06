<?php
set_time_limit(0);
error_reporting(0);
ini_set('display_errors','Off');
include 'Snoopy.class.php';
include 'sqlite.class.php';
$domain='http://cc.m-ty.com';
$id=$_GET['id'];
if ($id=='0') {
  getSiteMap($domain);
  die();
}elseif (!strpos($id, ',') && is_numeric($id)) {
  getArticle($id,$domain);
  die();
}
/**
 * 更新文章HTML页面
 * @param  [integer] $id
 * @return
 */
function getArticle($id,$domain)
{
  $db=new sqlite('./article.db');
  $snoopy= new Snoopy();
  $r = array('status' => 0, 'msg'=>'');
  $curPath=dirname(__FILE__);
  $curPath=preg_replace('/[\/\\\\]xiazai/i','',$curPath.'/');
  $status=$db->select('article','status',''," id=$id limit 1");
  if ($status) {
    $r = array('status' => 1, 'msg'=>'成功更新一篇文章');
  }else{
    $url=$db->select('article','url',''," id=$id limit 1");
    $url = ($url==$domain || substr($url,-1)=='/') ? $url.'/index.html':$url;
    $snoopy->fetch($url);
    $html=$snoopy->results;
    if (empty($html)) {
      $r['msg']='文章更新失败,请稍候再试...';
    }else{
      file_put_contents($curPath.basename($url), $html);
      $db->update('article','status',1,'',"id=$id");

      $reTag = '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i';
      preg_match_all($reTag, $html, $match);
      for ($i = 0, $len = count($match[2]); $i < $len; ++$i)
      {
        if (!strpos($match[2][$i],'data:image')) {
          if (!strpos($match[2][$i],':')) {
            $match[2][$i]=$domain.$match[2][$i];
          }
          saveFromURL($match[2][$i]);
        }else{
          unset($match[2][$i]);
        }
        
      }

      $r = array('status' => 1, 'msg'=>'成功更新一篇文章(含图片'.count($match[2]).'张),<a href="../'.basename($url).'" target="_blank">点击查看</a>');
    }
  }
  echo json_encode($r);
}

/**
 * 更新站点地图数据
 * @return
 */
function getSiteMap($domain){
  $db=new sqlite('./article.db');
  $snoopy= new Snoopy();
  $r = array('status' => 0, 'msg'=>'');
  $curPath=dirname(__FILE__);
  $curPath=preg_replace('/[\/\\\\]xiazai/i','',$curPath.'/');
  $lastmod=@$db->select('article','lastmod',''," 1=1 order by lastmod desc limit 1");
  $lastmod=empty($lastmod)?0:$lastmod;
  $snoopy->fetch($domain.'/sitemap.xml');
  $xml = simplest_xml_to_array($snoopy->results);
  if (empty($xml['url'])) {
    $r['msg']='站点数据同步失败,请稍候再试...';
  }else{
    foreach ($xml['url'] as $article) {
      $article['lastmod']=strtotime($article['lastmod']);
      if ($lastmod<$article['lastmod']) {
        $arr = array('status' => 0, 'lastmod'=>$article['lastmod'],'url'=>$article['loc']);
        $db->insert('article',$arr);
      }
    }
    $id=$db->select('article','id',''," status=0");
    $id=implode($id,',').',f';
    $r=array('status'=>1,'msg'=>'站点数据获取成功,即将更新网页...','id'=>$id);
  }
  echo json_encode($r);  
}

/**
 * 将XML数据转为数组
 * @param  [string] $xmlstring
 * @return
 */
function simplest_xml_to_array($xmlstring) {
  return json_decode(json_encode(simplexml_load_string($xmlstring)), true);
}

/**
 * 保存到本地
 * @param  [string] $name   远程地址
 * @return
 */
function saveFromURL($name) {
  $curPath=dirname(__FILE__);
  $curPath=preg_replace('/[\/\\\\]xiazai/i','',$curPath.'/');
  $folder=dirname(preg_replace('/[^:]+:\/\/[^\/]+/i', '', $name));
  $SavePath = $curPath.$folder."/".basename($name);
  if (!is_dir($curPath.$folder)) {
      mkdir($curPath.$folder, 0777, true);
  }
  if (!file_exists($SavePath)) {
    $File = file_get_contents($name);
    $flag = file_put_contents($SavePath,$File);
  }
  return '../'.$folder."/".basename($name);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>同步文件</title>
</head>
<body>
<div id="result"><img src="../Theme/wsyx/img/loading.gif" alt=""></div>
  <script src="/Theme/wsyx/js/jquery.min.js"></script>
  <script>
    var id=getUrlParam('id');
    $(function(){
      if (!id) {
        $.ajax({
          url: '?id=0',
          dataType: 'json',
          timeout: 60000,
        })
        .done(function(data) {
          $('#result').html(data.msg);
          if (data.status==1) {
            setTimeout(function(){
              window.location.href='?id='+data.id;
            },2000);
          }
        })
        .fail(function() {
          $('#result').html('网络错误,请稍候再试');
          setTimeout(function(){
            window.location.reload();
          },5000);
        });
        
      }else{
        var tmp=new Array();
        tmp=id.split(',');
        if (tmp[0] !='') {
          $.ajax({
            url: '?id='+tmp[0],
            dataType: 'json',
            timeout: 60000,
          })
          .done(function(data) {
            $('#result').html(data.msg);
            if (data.status==1) {
              tmp.remove(0);
              if (tmp.length>1) {
                id=tmp.join(',');
                setTimeout(function(){
                  window.location.href='?id='+id;
                },2000);
              }else{
                $('#result').html('所有网页更新完毕');
              }            
            }
          })
          .fail(function() {
            $('#result').html('网络错误,请稍候再试');
            setTimeout(function(){
              window.location.reload();
            },5000);
          });
          
        }else{
          $('#result').html('所有网页更新完毕');
        }
      }
      
    });
    function getUrlParam(name){
      var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
      var r = window.location.search.substr(1).match(reg);
      if(r!=null)return  unescape(r[2]); return null;
    }
    Array.prototype.remove=function(dx) 
    { 
      if(isNaN(dx)||dx>this.length){return false;} 
      for(var i=0,n=0;i<this.length;i++) 
      { 
        if(this[i]!=this[dx]) 
        { 
            this[n++]=this[i] 
        } 
      } 
      this.length-=1 
    }
  </script>
</body>
</html>
