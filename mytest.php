<?php
$str = '[quote][size=2][url=forum.php?mod=redirect&goto=findpost&pid=120&ptid=16][color=#999999]dire 发表于 2017-10-09 17:43[/color][/url][/size]
地方都辅导费 [/quote]测试2';
$pid=2;
$author='admin';
$time=date('Y-m-d H:i', 1506581125);
$message='dssfgrhthjty';
$tid=2;
$insert="[quote][size=2][url=forum.php?mod=redirect&goto=findpost&pid=".$pid."&ptid=".
    $tid."[color=#999999]".$author." 发表于 ".$time."[/color][/url][/size]".$str."[/quote]".$message;
$test=substr(strrchr($str,'[/quote]'),8);
$time=date('Y-m-d H:i', 1506581125);
//定义字符串
$str1 = "Hello World!";
$str2 = "Welcome to HutaoW's BLOG!";

//连接上面两个字符串 中间用空格分隔
$str3 = $str1 . "dsdsd " . $str2."这是字符串3 ";

$time1='2017-09-23 22:06:15';
$time2='2017-09-24 02:16:27';
$time3=$time1."~".$time2;
echo $time3;
exit;