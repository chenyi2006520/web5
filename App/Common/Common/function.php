<?php 

function pp($array) {
    dump($array, true, "<pre>", false);
}

//创建文章内容文件，以日期天数为区分
function getArticleFile($md5FileName, $content) {
    if ($md5FileName) {
        $today = date("Y-m-d", time());
        F($md5FileName, $content, './Data/'.$today."/", "html");
    }
}

function handelHOST()
{
    return str_replace("www.","", $_SERVER['HTTP_HOST']);   
}


function getUrlContent($url)
{
    $timeout = 10;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true);
    // curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
    curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    $file_contents = curl_exec($ch); 
    $info = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    curl_close($ch);
    if ($info == "302") {
        return file_get_contents($url);
    }else {
        return $file_contents;
    }
}

function unicode2utf8($str) {
    if (!$str) return $str;
    $decode = json_decode($str);
    if ($decode) return $decode;
    $str = '["'.$str.'"]';
    $decode = json_decode($str);
    if (count($decode) == 1) {
        return $decode[0];
    }
    return $str;
}

//汉字转拼音调用
function TranslateValue($value)
{
    import("Class.Pinyin");
    $setting = [
            'delimiter' => '',
            'accent' => false,
        ];
    return \Overtrue\Pinyin\Pinyin::trans($value, $setting);
}


/**
 * 时间差计算
 *
 * @param Timestamp $time
 * @return String Time Elapsed
 * @author Shelley Shyan
 * @copyright http://phparch.cn (Professional PHP Architecture)
 */
function time2Units ($time)
{
   $year   = floor($time / 60 / 60 / 24 / 365);
   $time  -= $year * 60 * 60 * 24 * 365;
   $month  = floor($time / 60 / 60 / 24 / 30);
   $time  -= $month * 60 * 60 * 24 * 30;
   $week   = floor($time / 60 / 60 / 24 / 7);
   $time  -= $week * 60 * 60 * 24 * 7;
   $day    = floor($time / 60 / 60 / 24);
   $time  -= $day * 60 * 60 * 24;
   $hour   = floor($time / 60 / 60);
   $time  -= $hour * 60 * 60;
   $minute = floor($time / 60);
   $time  -= $minute * 60;
   $second = $time;
   $elapse = '';

   $unitArr = array('年'  =>'year', '个月'=>'month',  '周'=>'week', '天'=>'day',
                    '小时'=>'hour', '分钟'=>'minute', '秒'=>'second'
                    );

   foreach ( $unitArr as $cn => $u )
   {
       if ( $$u > 0 )
       {
           $elapse = $$u . $cn;
           break;
       }
   }

   return $elapse;
}


function getRealTime($lastime)
{
    $cle = time() -$lastime ; //得出时间戳差值

    /* 这个只是提示
    echo floor($cle/60); //得出一共多少分钟
    echo floor($cle/3600); //得出一共多少小时
    echo floor($cle/3600/24); //得出一共多少天
    */
    
    /*Rming()函数，即舍去法取整*/
    $d = floor($cle/3600/24);
    $h = floor(($cle%(3600*24))/3600);  //%取余
    $m = floor(($cle%(3600*24))%3600/60);
    $s = floor(($cle%(3600*24))%60);

    return "$h 小时 $m 分 $s 秒";
}

?>