<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="吧盟,ba.cc" />
    <meta name="description" content="吧盟,ba.cc" />
    <meta name="author" content="ba.cc" />
    <meta name="copyright" content="ba.cc" />
    <base target='_blank'>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bameng.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.2.1/jquery.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <title>吧盟 ba.cc</title>
    <style type="text/css">
        body {
            margin: 10px 0;
            background:#CCCCCC;
        }
        
        .title {
            font-size: 50px;
            font-weight: bold;
            color: #F60;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 ">
                <div class="topleft">
                    <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="8860536544" data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>-->
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="title text-center">吧盟</h1>
                <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="6966625743" data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>-->
                &nbsp;
                <?php if(is_array($tagList)): foreach($tagList as $key=>$val): ?><h3 class="text-center"><a href="http://<?php echo ($val['b_pinyinkey']); ?>.ba.cc"><?php echo ($val["b_string"]); ?>吧</a></h3><?php endforeach; endif; ?>
            </div>
            <div class="col-md-3 ">
                <div class="topright">
                    <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="6966625743" data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>-->
                </div>
            </div>
        </div>
    </div>
    <?php include '/www/web/bameng/public_html/friendlink.html'; ?>
    <div class="hidden">
        <script src="http://s5.cnzz.com/stat.php?id=5838328&web_id=5838328" language="JavaScript"></script>
    </div>
</body>

</html>