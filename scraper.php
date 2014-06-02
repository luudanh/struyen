<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';

scraperwiki::save_var('last_id', 1);

//var_dump(json_decode($json));
//exit();
$id= scraperwiki::get_var('last_id');
for($i=$id;$i<3200;$i++){
 $i--;
$api="https://api.morph.io/luudanh/s-in-s/data.json?key=g7c0INT8tWZAeziAaS3U&query=select%20*%20from%20%27data%27%20limit%20$i,1";
$json = scraperwiki::scrape($api);
$src = json_decode($json);
foreach($src as $val)
{
 //var_dump($src);
 $url = base64_decode($val->url);

//exit;
 $url = str_replace("vietphrase.com/go/","",$url);
//exit();
 $html_content = scraperwiki::scrape($url);
$html = str_get_html($html_content);
$data = array();
$tr =  $html->find("div.postmessage div.t_msgfont");
$j = 0;
foreach($tr as $trr){
 
$noidung = $trr->innertext;
//$noidung = utf8_encode($noidung);
if(mb_strlen($noidung) >1000){
    $j++;
  scraperwiki::save_sqlite(array('id'),array('id'=> $j.$val->id, 'title'=>$val->title,'url'=> $val->url,'content'=>base64_encode($noidung),'order'=> $j,'num'=>$val->num,'reply'=>$val->reply,'type'=>$val->type));
}
   
}
$html->clear();
unset($html);
$i++;
scraperwiki::save_var('last_id', $i);
}
} 
?>
