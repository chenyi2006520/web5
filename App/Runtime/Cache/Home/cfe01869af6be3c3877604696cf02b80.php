<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Document</title>
    <link rel="stylesheet" href="/Public/css/bootstrap.min.css">
    <script src="/Public/js/jquery-2.2.0.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
</head>

<body class="btn-info">
    <div class="container col-lg-12  text-center">
        <div class="form-horizontal">
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable" id="thisTime"><?php echo ($thisTime); ?></lable>
            </div>
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable" id="constantCount">热门关键字总数:<?php echo ($constantCount); ?></lable>
            </div>
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable" id="ArticleTitleCount">文章标题中间表总数:<?php echo ($ArticleTitleCount); ?></lable>
            </div>
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable" id="totalArticleCount">文章总数:<?php echo ($totalArticleCount); ?></lable>
            </div>
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable btn-success" >以上数据总量代表上次最后更新的数据</lable>
            </div>
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable" id="time2Units">首页更新时间:<?php echo ($time2Units); ?></lable>
            </div>
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable" id="LastGetFile">获取文件更新时间:<?php echo ($LastGetFile); ?></lable>
            </div>
            <div class="form-group m-bottom-md">
                <lable class="col-sm-6 col-lg-12 text-center control-lable" id="LastGetFileContent">文章更新时间:<?php echo ($LastGetFileContent); ?></lable>
            </div>
            <input type="hidden" id="hourtime" value="<?php echo ($hourtime); ?>">
            <input type="hidden" id="tempLast001" value="<?php echo ($tempLast001); ?>">
            <input type="hidden" id="tempLast002" value="<?php echo ($tempLast002); ?>">
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            setInterval(secondUpdate,1000);
            setInterval(lastUpdateTime,1000);
        });
        
        function secondUpdate()
        {
            showLeftTime();
            updateKeyWords();
            updateKeyTitle();
            updateKeyContent()
        }
        
        function showLeftTime()
        {
            var now = new Date();
            var year = now.getFullYear();
            var month = now.getMonth();
            var day = now.getDate();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var strHtml = year + "年" + month + "月" + day + "日 " + hours + ":" + minutes + ":" + seconds + "";
            var mytime=now.toLocaleString()
            $("#thisTime").text(mytime);
        }
        
        function updateKeyWords()
        {
            var hourtime = $("#hourtime").val();
            if(hourtime >= 1)//最后更新的是大于一个小时就重新获取
            {
                $.get("<?php echo U('/Home/Article/getkeywords/');?>",function(){
                    location.reload();                         
                });
            }
        }
        
        function updateKeyTitle()
        {
            var tempLast001 = $("#tempLast001").val();
            if(tempLast001 >= 2)
            {
                $.get("<?php echo U('/Home/Article/getArticleTitleAndFile/');?>",function(){
                    location.reload();                         
                });
            }
        }
        
        function updateKeyContent()
        {
            var tempLast002 = $("#tempLast002").val();
            if(tempLast002 >= 2.5)
            {
                $.get("<?php echo U('/Home/Article/getArticleFileContent/');?>",function(){
                    location.reload();                         
                });  
            }
        }
        
        
        function lastUpdateTime()
        {
            $.get("<?php echo U('/Home/Article/getLastUpdateTime/');?>",function(data){
                $("#hourtime").val(data.LastGetWord[0]);                      
                    $("#tempLast001").val(data.LastGetFile[0]);                      
                    $("#tempLast002").val(data.LastGetFileContent[0]);  
                    
                    $("#time2Units").text("首页更新时间:" + data.LastGetWord[1]);  
                    $("#LastGetFile").text("获取文件更新时间:" + data.LastGetFile[1]);  
                    $("#LastGetFileContent").text("文章更新时间:" + data.LastGetFileContent[1]);                    
                });
        }
    </script>
</body>

</html>