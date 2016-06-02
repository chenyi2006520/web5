<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo htmlspecialchars_decode($articleTitle);?>,<?php echo ($kname); ?>吧,<?php echo ($kname); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars_decode($articleTitle);?>,<?php echo ($kname); ?>吧,吧盟" />
    <meta name="author" content="<?php echo ($kname); ?>吧" />
    <meta name="copyright" content="<?php echo ($kname); ?>吧" />
    <!--<base target='_blank'>-->
    <link href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">   
     <link href="/Public/css/bameng.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.2.1/jquery.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <title><?php echo htmlspecialchars_decode($articleTitle);?>-<?php echo ($kname); ?>吧-吧盟</title>
    <style type="text/css">
        body {
            margin: 10px 0;
            background:#CCCCCC;
        }
        
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #F60;
        }
        
        .contentFont {
            font-size: 1.125em;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 ">
                <div class="topleft">
                    <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="6966625743" data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>-->
                </div>
            </div>
            <div class="col-md-6">
                <br>
                <h3 class="text-center title"><?php echo htmlspecialchars_decode($articleTitle);?></h3>
                <br>
                <br>
                <div class="text-center">
                    <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- 自适应su1 --
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4730034842102789" data-ad-slot="4454837343" data-ad-format="auto"></ins>                &nbsp;
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>-->
                </div>
                <div style="font-size: 20px;">

                    <?php echo ($articleContent); ?>
                </div>
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
    <script type="text/javascript">
        $(function(){
            var is_iPd = navigator.userAgent.match(/(iPad|iPod|iPhone)/i) != null;
            var is_mobi = navigator.userAgent.toLowerCase().match(/(ipod|iphone|android|coolpad|mmp|smartphone|midp|wap|xoom|symbian|j2me|blackberry|win ce)/i) != null;
            if(is_mobi && window.location.search.indexOf("mv=fp")<0){
                $("img").addClass("carousel-inner img-responsive img-rounded");
            }
        });
    </script>
    <?php include '/www/web/bameng/public_html/friendlink.html'; ?>
    <div class="hidden">
        <script src="http://s5.cnzz.com/stat.php?id=5838328&web_id=5838328" language="JavaScript"></script>
    </div>
</body>

</html>