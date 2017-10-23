<?php
require __DIR__.'/post_reply.php';
$pdo=require __DIR__.'/db.php';
include_once "./uc_test/config.inc.php";
include "./uc_client/client.php";
//include_once "./uc_test/config.inc.php";
//include "./uc_client/client.php";
//include "./source/function/function_post.php";
class sqltitle{
    private $_post_reply;
    public function __construct(post_reply $_post_reply)
    {
        $this->_post_reply = $_post_reply;
    }
    public function run(){
        return $this->_jsontitle($this->_gettie());
    }
    private function _jsontitle($array,$code=0)
    {
        /*if($array === null && $code === 0){
            $code = 204;
        }
        if($array !== null && $code === 0){
            $code = 200;
        }*/
        if ($code > 0 && $code != 200 && $code != 204) {
            header("HTTP/1.1 " . $code . "  " . $this->_statusCodes[$code]);
        }
        header("Content-Type=application/json;charset=UTF-8 ");
        echo json_encode($array, JSON_UNESCAPED_UNICODE);

        exit();
    }


    private function _getBodyParams(){
        $raw = file_get_contents('php://input');
        if(empty($raw)){
            throw new Exception("请求参数错误", 400);
        }
        return json_decode($raw,true);
    }

    private function _gettie(){
        $body=$this->_getBodyParams();
        return $this->_post_reply->gettid($body['tid']);
    }
}
$_post_reply=new post_reply($pdo);
$concent=new sqltitle($_post_reply);
$username="admin";
$password="123";
$email="dire@qq.com";
list($uid, $username, $password, $email) = uc_user_login($username, $password);
if($uid > 0) {
    $concent->run();
} elseif($uid == -1) {
    echo '用户不存在,或者被删除';
} elseif($uid == -2) {
    echo '密码错误';
} else {
    echo '未定义';
}
?>