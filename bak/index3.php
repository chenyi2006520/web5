<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
$se = 0;
$url = $_SERVER["HTTP_REFERER"]; //获取完整的来路URL 
$str = str_replace("https://", "", $url); //去掉https:// 
$str = str_replace("http://", "", $str); //去掉http:// 
$strdomain = explode("/", $str); // 以“/”分开成数组 
$domain = $strdomain[0]; //取第一个“/”以前的字符 
if (strstr($domain, 'baidu.com')) {
    $se = 1;
} else if (strstr($domain, 'haosou.com')) {
    $se = 1;
} else if (strstr($domain, 'sogou.com')) {
    $se = 1;
} else if (strstr($domain, 'bing.com')) {
    $se = 1;
}

if ($se == 1) {
    include "adportal.html";#header("location: adportal.html");
    break;
}





$url = explode(".", $_SERVER['HTTP_HOST']);
$root = $url[1].'.'.$url[2];
if ($url[2]) {
    $sub = $url[0];
} else {
    $sub = "";
}
require 'conn.php';
$haosou = 'http://m.news.haosou.com/ns?q='.rawurlencode($sub);
/*
if (is_null($n[1])) {
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");
    header("location: 404.php");
}
*/
#echo $cnkey;#寻找数据库关键字
$keyid = mysql_fetch_row(mysql_query("SELECT kid FROM keyword where kstring='$sub'"));
$kid = $keyid[0];#$kamont = $keyid[1];
if (is_null($kid)) {
    $kidmax = mysql_fetch_row(mysql_query('SELECT kid FROM keyword order by kid desc limit 1'));
    if (is_null($kidmax[0])) {
        $kid = 1;
    } else {
        $kid = $kidmax[0] + 1;
    }

}#echo 'kid='.$kid.'<br>';


#寻找某关键词下最大的文章id和标题
$maxakey = mysql_fetch_row(mysql_query("SELECT aid_key, atitle FROM article where kid='$kid' order by aid_key desc limit 1"));
$maxakeyid = $maxakey[0];
if (is_null($maxakeyid)) {
    $maxakeyid = 0;
}
$maxtitle = $maxakey[1];#echo $maxtitle.'<br>';#echo $maxakeyid.'<br>';#echo $maxkey.'<br>';#好搜手机版关键词新闻搜索返回页#$haosou = 'http://m.news.haosou.com/ns?q=%E8%B6%B3%E7%90%83';#$haosou = 'http://m.news.haosou.com/ns?q=%E6%9C%BA%E7%A5%A8';
$num = 0;
$lock = mysql_fetch_row(mysql_query("SELECT lswitch FROM locker where lid = 1"));
$lswitch = $lock[0];#echo 'lswitch'.$lswitch;
$suo = 0;#
if ($lswitch == 0) {
    if ($lswitch == 0 && $maxakeyid == 0) {#echo "抓了";
        mysql_query("UPDATE locker SET lswitch = 1 WHERE lid=1");
        $suo = 1;#抓取所有内容至$news
        $news = file_get_contents($haosou);#匹配文章列表部分
        preg_match('/<div id=main>(.*?)相关搜索/si', $news, $m);#匹配所有文章URL
        preg_match_all('/<a href="(.*?)">/si', $m[1], $url);#匹配所有文章标题
        preg_match_all('/<h3 class=title>(.*?)<\/h3>/si', $m[1], $raw_title);#echo strip_tags($raw_title[1][0]).'<br>';
        for ($key_num = 0; $key_num < count($url[1]); $key_num++) {#
            for ($key_num = 0; $key_num < 5; $key_num++) {
                if ($maxtitle != strip_tags($raw_title[1][$key_num])) {} else {
                    break;
                }
            }

            #echo $key_num.'<br>';#echo $maxaid;

            #可以采集的URL $link[$num]



            for ($i = 0; $i < $key_num; $i++) {
                $link_raw = htmlspecialchars_decode($url[1][$i]);
                $article_raw = file_get_contents('http://m.news.haosou.com'.$link_raw);
                preg_match('/<h1 id="news-title">(.*?)<\/h1>/si', $article_raw, $title_raw);

                if (isset($title_raw[1])) {
                    $article_raw = preg_replace('/http:\/\/m.haosou.com(.*?) class="qkw">/si', '/">', $article_raw);
                    preg_match('/<span id=source>(.*?)<\/span>/si', $article_raw, $author_raw);
                    preg_match('/<time id=time>(.*?)<\/time>/si', $article_raw, $time_raw);
                    preg_match('/<div id="news-body">(.*?)<\/div>/si', $article_raw, $content_raw);#$link[$num] = $link_raw;
                    $title[$num] = strip_tags($raw_title[1][$i]);
                    $author[$num] = $author_raw[1];
                    $time[$num] = $time_raw[1];
                    $content[$num] = $content_raw[1];
                    $num++;
                }
            }
        }

        #判断要不要404
        if ($num == 0 && $maxakeyid == 0 && $lswitch != 1) {
            mysql_query("UPDATE locker SET lswitch = 0 WHERE lid=1 ");
            echo '不刷新';
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");#header("location: 404.php");
            require "404.php";
            die();
        }

        #判断要不要写入
        if ($num != 0) {


            if (is_null($keyid[0])) {
                $cn_search = 'http://news.haosou.com/ns?&rank=pdate&q='.$sub;
                $cn_contents = file_get_contents($cn_search);
                preg_match('/已自动为您给出<em> "(.*?)"<\/em>的搜索结果/', $cn_contents, $n);
                $cnkey = trim(strip_tags($n[1], ''));
                mysql_query("INSERT INTO keyword (kid, kstring, kname) 
        VALUES ('$kid', '$sub', '$cnkey')");
            }

            #文章内容写入数据库#$maxaid最新文章的id
            $maxaid = mysql_fetch_row(mysql_query("SELECT aid FROM article order by aid desc limit 1"));
            $maxaid = $maxaid[0];
            if (is_null($maxaid)) {
                $maxaid = 0;
            }#echo 'T0'.$title[0].'<br>';#echo 'T1'.$title[1].'<br>';#echo '新文章数量：'.$num;

            for ($i = 0; $i < $num; $i++) {
                $article_num = $num - $i - 1;
                $content[$article_num] = mysql_real_escape_string($content[$article_num]);
                mysql_query("INSERT IGNORE INTO article (aid, kid, aid_key, atitle, aauthor, atime, acontent) 
        VALUES ('$i'+1+'$maxaid', '$kid','$i'+'$maxakeyid'+1, '$title[$article_num]', '$author[$article_num]', '$time[$article_num]', '$content[$article_num]')");
                mysql_query("UPDATE keyword SET kamont = kamont+1 WHERE kid='$kid'");
            }
            if ($suo == 1) {
                mysql_query("UPDATE locker SET lswitch = 0 WHERE lid=1 ");
            }
        }

        if ($num == 0 && $maxakeyid == 0 && $lswitch == 1) {
            echo '<a href="">页面有点儿害羞 戳这儿刷它一下</a>';#sleep(2);#echo "<script language=JavaScript> location.replace(location.href);</script>";
        }
        $kname = mysql_fetch_row(mysql_query("SELECT kname FROM keyword where kstring='$sub'"));
        $kname = $kname[0];
        if (empty($kname)) {
            $kname = $sub;
        }

        #echo '循环次数'.$i;#mysql_query("UPDATE keyword SET kamont = kamont+'$num' WHERE kid='$kid'");
        ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $kname, $sitename; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?php echo $kname.','.$kname, $sitename; ?>" />
    <meta name="description" content="<?php echo $kname.','.$kname, $sitename; ?>" />
    <meta name="author" content="<?php echo $sub.' '.$url[1]; ?>" />
    <meta name="copyright" content="<?php echo $root; ?>" />
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/templatemo-style.css">
    <script src="js/vendor/modernizr-2.6.2.min.js"></script>

    <style type="text/css">
        body {
            background-color: #CCC;
        }
        
        <!-- .STYLE1 {
            font-family: Microsoft YaHei, SimHei, SimSun, Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #999999;
        }
        
        #a {
            font-size: 40px;
            font-weight: bold;
            color: #366;
        }
        
        #b {
            font-size: 28px;
            color: #C33;
            font-family: Microsoft YaHei, SimHei, SimSun, Arial, Helvetica, sans-serif;
        }
        
        #c {
            font-size: 16px;
            font-family: Microsoft YaHei, SimHei, SimSun, Arial, Helvetica, sans-serif;
            color: #777;
        }
        
        #s {
            font-size: 14px;
            color: #555;
        }
        
        -->
    </style>
</head>

<body>
    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- SIDEBAR -->
    <div class="sidebar-menu hidden-xs hidden-sm">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- 自适应001 -->
        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="6966625743" data-ad-format="auto"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    <!-- .sidebar-menu -->

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <div class="fluid-container">

            <div class="content-wrapper">
                <div align="left">
                    <br>
                    <a id="a"><?php echo $kname, $sitename; ?></a>
                    <br>
                    <br>
                    <div align="left">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- 自适应su1 -->
                        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="4454837343" data-ad-format="auto"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                    <br> <?php 
                    #输出文章列表
                    $i = 0;
                    $result = mysql_query("SELECT aid_key, atitle, acontent FROM article where kid='$kid' order by aid_key desc limit 0,20");
                    while ($row = mysql_fetch_array($result)) {
                        $showtitle[] = $row;
                        echo '<a href="'.$showtitle[$i][0].'.html" id="b" target="blank">'.$showtitle[$i][1].'</a><br>';
                        echo '<a id="c">'.mb_substr(strip_tags($showtitle[$i][2]), 0, 60, 'utf-8').'</a><br><br>';
                        $i++;
                    }

                    #echo '$suo:'.$suo;
                    mysql_close($con);
                    ?>
                    <a id="s">Copyright &copy; <?php echo $root; ?></a>
                </div>
            </div>
        </div>

    </div>

    </div>
    </div>

    <script src="js/vendor/jquery-1.10.2.min.js"></script>
    <script src="js/min/plugins.min.js"></script>
    <script src="js/min/main.min.js"></script>

</body>

</html>