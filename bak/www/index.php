<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="吧盟,ba.cc" />
<meta name="description" content="吧盟,ba.cc" />
<meta name="author" content="ba.cc" />
<meta name="copyright" content="ba.cc" />
<base target='_blank'>
<title>吧盟 ba.cc</title>

<style type="text/css">

body {padding:0; margin:0; font-size:14px; line-height:140%; background:url(bg.png);}
body,html{
	height: 100%;
	text-align: center;
	font-size: 24px;
}
a{color: #C33;}
a:hover{color: #C00;}
#outer {height: 100%; overflow: hidden; position: relative;width: 100%;}
#outer[id] {display: table; position: static;}
#middle {position: absolute; top: 50%;text-align:center;}
#middle[id] {display: table-cell; vertical-align: middle; position: static;}
#inner {position: relative; top: -50%;width: 750px;margin: 0 auto;text-align:left;}
p{margin: 1em;}

<!--
.STYLE1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	color: #999999;
}

#a {
	font-size: 50px;
	font-weight: bold;
	color: #F60;
}

-->
</style>
</head>

<body>
<div align="center">
<p>
<a id="a">
吧盟
</a>
</p>
</div>

<div align="center">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 自适应001 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-4730034842102789"
     data-ad-slot="6966625743"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>


<?php
include 'pinyin.php';
function unicode2utf8($str){
        if(!$str) return $str;
        $decode = json_decode($str);
        if($decode) return $decode;
        $str = '["' . $str . '"]';
        $decode = json_decode($str);
        if(count($decode) == 1){
                return $decode[0];
        }
        return $str;
}
$url=('http://s.weibo.com/top/summary?cate=realtimehot');
$html=file_get_contents ($url);
$final=strip_tags ($html, '<p>');
$final=str_replace(" ","",$final);
$final=str_replace("hot!","",$final);
$final=str_replace("new!","",$final);
preg_match_all ('/star_name\\\">(.*?)\\\n<pclass/',$final,$n);
for ($x=0; $x<count($n[1]); $x++) {
  $tag[$x]=unicode2utf8($n[1][$x]);
}
/*
for ($x=0; $x<5; $x++) {
  $sumtags1=$sumtags1.$tag[$x].',';
}
for ($x=5; $x<8; $x++) {
  $sumtags2=$sumtags2.$tag[$x].',';
}
for ($x=8; $x<12; $x++) {
  $sumtags3=$sumtags3.$tag[$x].',';
}
for ($x=12; $x<18; $x++) {
  $sumtags4=$sumtags4.$tag[$x].',';
}
for ($x=18; $x<24; $x++) {
  $sumtags5=$sumtags5.$tag[$x].',';
}
for ($x=24; $x<30; $x++) {
  $sumtags6=$sumtags6.$tag[$x].',';
}
for ($x=30; $x<35; $x++) {
  $sumtags7=$sumtags7.$tag[$x].',';
}
for ($x=35; $x<40; $x++) {
  $sumtags8=$sumtags8.$tag[$x].',';
}
for ($x=40; $x<50; $x++) {
  $sumtags9=$sumtags9.$tag[$x].',';
}
for ($x=50; $x<64; $x++) {
  $sumtags10=$sumtags10.$tag[$x].',';
}

$sumtagsall=pinyin($sumtags1).pinyin($sumtags2).pinyin($sumtags3).pinyin($sumtags4).pinyin($sumtags5).pinyin($sumtags6).pinyin($sumtags7).pinyin($sumtags8).pinyin($sumtags9).pinyin($sumtags10);
echo pinyin($sumtags2);
$tags_arr = explode(',',$sumtagsall);
*/
echo '';
for ($x=0; $x<count($tag); $x++) {
  echo '<a href="http://'.pinyin($tag[$x]).'.ba.cc">'.$tag[$x].'吧<br>';
}
?>



<div align="center">


</div>

<div>
</div>
<body>
<div align="center">

<p>
吧盟 ba.cc
</p>
</div>
<div style="display:none">
<script src="http://s5.cnzz.com/stat.php?id=5838328&web_id=5838328" language="JavaScript"></script>
</div>
</body>
</html>