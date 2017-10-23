<?php
require __DIR__.'/post_reply.php';
$pdo=require __DIR__.'/db.php';
//include_once "./uc_test/config.inc.php";
//include "./uc_client/client.php";
//include_once "./uc_test/config.inc.php";
//include "./uc_client/client.php";
//include "./source/function/function_post.php";
session_start();
class reply{
    private $_post_reply;

    public function __construct(post_reply $_post_reply)
    {
        $this->_post_reply = $_post_reply;
    }

    public function run(){
        $src=file_get_contents('php://input');
        $srcarray=json_decode($src,true);
        if(empty ($srcarray['position']))
        {
            $this->_handreply();
        }
       
    }
    private function _getBodyParams(){
        $raw = file_get_contents('php://input');
        if(empty($raw)){
            throw new Exception("请求参数错误", 400);
        }
        return json_decode($raw,true);
    }

    private function _handreply(){

        $body=$this->_getBodyParams();
        $this->_post_reply->post_tableid();
        return $this->_post_reply->reply($body['tid'],$body['author'],$body['authorid'],$body['message']);
    }
}

$src=file_get_contents('php://input');
$srcarray=json_decode($src,true);
if($_SESSION['authcode']!=null) {
    if (strtolower($srcarray['authcode']) == $_SESSION['authcode']) {

            $_post_reply = new post_reply($pdo);
            $concent = new reply($_post_reply);
            $concent->run();
            echo '{"status":"0","message":"发表成功"}';
            $_SESSION['authcode'] = null;
    }
    else {

        echo '{"status":"-1","message":"验证码错误"}';
    }
}else{
    echo 'session值不存在';
}
?>