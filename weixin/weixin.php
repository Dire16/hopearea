<?php
//微信登录
session_start();
//-------配置
$AppID = 'wx33589087f8e1fb68';
$AppSecret = 'bfb57e9dad5df9f21283af00009ea5c9';
$callback  =  'www.cc8.cc/GamebossAPI/callback.php'; //回调地址
//-------生成唯一随机串防CSRF攻击
$state  = md5(uniqid(rand(), TRUE));
$_SESSION["wx_state"]    =   $state; //存到SESSION
$wxurl = "https://open.weixin.qq.com/connect/qrconnect?appid=".$AppID."&redirect_uri={$callback}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
header("Location: $wxurl");
?>
