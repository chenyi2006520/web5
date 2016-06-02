<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
$url = explode(".", $_SERVER['HTTP_HOST']);
$root = $url[1].'.'.$url[2];
if ($url[2]) {
    $sub = $url[0];
} else {
    $sub = "";
}
require 'conn.php';
include 'pinyin.php';
$keyword = mysql_fetch_array(mysql_query("SELECT * FROM keyword where kstring='$sub'"));
$kid = $keyword[kid];
$kstring = $keyword[kstring];
$kname = $keyword[kname];
if (empty($kname)) {
    $kname = $kstring;
}
$id = $_GET['id'];
$article = mysql_fetch_array(mysql_query("SELECT * FROM article WHERE kid=$kid and aid_key=$id"));
$title = $article['atitle'];
if (is_null($title)) {
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");
    require "404.php";
    break;
}
$content = $article['acontent'];
preg_match_all('/<a href="\/">(.*?)<\/a>/si', $content, $k);
$kw = $k[1];
$len = count($kw);
for ($i = 0; $i < $len; $i++) {
    $pinyin = pinyin($kw[$i]);
    if ($pinyin) {
        $content = preg_replace('<a href="\/">', 'a href="http://'.$pinyin.'.'.$root.'" class="kw"', $content, 1);
    } else {
        $content = preg_replace('<a href="\/">', 'a href="" class="kw"', $content, 1);
    }
}
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
    <title><?php echo $title.'-'.$kname, $sitename; ?></title>
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
            padding: 0;
            margin: 0;
            font-size: 14px;
            line-height: 140%;
            background: #CCCCCC;
        }
        
        body,
        html {
            height: 100%;
            font-size: 24px;
        }
        
        a {
            color: #C33;
        }
        
        img {
            max-width: 100%;
        }
        
        a:hover {
            color: #C00;
        }
        
        p {
            font-family: Arial, Helvetica, sans-serif;
        }
        
        <!-- #h {
            font-size: 40px;
            font-weight: bold;
            color: #000;
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

    <div class="responsive-header visible-xs visible-sm">
        <div class="container">
        </div>
    </div>

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
                    <a id="h"><?php echo $title; ?></a>
                    <br>
                    <br>
                </div>
                <div align="left">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- 自适应su1 -->
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="4454837343" data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    <?php echo $content; ?>
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- 自适应su1 -->
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="4454837343" data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
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