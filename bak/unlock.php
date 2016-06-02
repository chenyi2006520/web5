<?php
include 'conn.php';
$lock= mysql_fetch_row(mysql_query("SELECT lswitch FROM locker where lid = 1"));
$lswitch=$lock[0];
$kidmax= mysql_fetch_row(mysql_query('SELECT kid FROM keyword order by kid desc limit 1'));
$kidmax=$kidmax[0];
$amax= mysql_fetch_row(mysql_query("SELECT aid FROM article order by aid desc limit 1"));
$amax=$amax[0];
echo '锁'.$lswitch.'<br>';
echo '关键词数'.$kidmax.'<br>';
echo '文章数'.$amax.'<br>';
if ($_GET['s']==10) {
	mysql_query("UPDATE locker SET lswitch = 0 WHERE lid=1 ");
}
?>
<a href="unlock.php?s=10">戳这儿解锁</a>