<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="/Public/js/jquery-2.2.0.min.js"></script>
    <title>Document</title>
</head>
<body>
    <br />[页面执行时间：<?php echo ($total); ?> ]秒<br />
    <script type="text/javascript">
        $(function(){
            setInterval(showLeftTime,1000*60);
        });
        function showLeftTime()
        {
           window.close()
        }
    </script>
</body>
</html>