<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 2017/9/26
 * Time: 14:48
 */
class post_reply{
    public function __construct($_db)
    {
        $this->_db=$_db;

    }
    /*
     * insert 方法
     * fid帖子模块的值
     * tid帖子的编号
     *
     */

    public function  reply($tid,$author,$authorid,$message){
        $pid=$this->pidnumber()-1;
        $fid=2;
        $dateline=time();
        $usesig=1;
        $bbcodeoff=-1;
        $smileyoff=-1;
        $sql = "INSERT INTO pre_forum_post (`pid`,`fid`,`tid`, `author`, `authorid`,`dateline`,`useip`,`port`,`usesig`,`bbcodeoff`,`smileyoff`,`message`) VALUES (:pid,:fid,:tid,:author,:authorid,:dateline,:useip,:port,:usesig,:bbcodeoff,:smileyoff,:message)";
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':pid',$pid);
        $stmt->bindParam(':fid',$fid);
        $stmt->bindParam(':tid',$tid);
        $stmt->bindParam(':author',$author);
        $stmt->bindParam(':authorid',$authorid);
        $stmt->bindParam(':dateline',$dateline);
        $stmt->bindParam(':useip',$_SERVER['HTTP_HOST']);
        $stmt->bindParam(':port',$_SERVER["SERVER_PORT"]);
        $stmt->bindParam(':usesig',$usesig);
        $stmt->bindParam(':bbcodeoff',$bbcodeoff);
        $stmt->bindParam(':smileyoff',$smileyoff);
        $stmt->bindParam(':message',$message);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
//        return array(
//            'status'=>'0',
//            'message'=>array(
//            'pid'=>$pid,
//            'tid'=>$tid,
//            'author'=>$author,
//            'authorid'=>$authorid,
//            'message'=>$message,)
//        );
    }

    public function post_tableid(){
        $pid=$this->pidnumber();
        $sql="INSERT INTO pre_forum_post_tableid (`pid`) VALUES (:pid)";
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':pid',$pid);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
    }

    public function gettid($tid){
        $sql = 'select subject from pre_forum_post where `tid`=:tid AND `first`=1';
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':tid',$tid);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data[0]['subject'];
    }

    public function gettidposition($tid){
        $invisible=0;
        $sql='select message,bbcodeoff from pre_forum_post WHERE `tid`=:tid AND `invisible`=:invisible ORDER BY position ASC';
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':tid',$tid);
        $stmt->bindParam(':invisible',$invisible);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function pidnumber(){
        $sql="select Max(pid) from pre_forum_post_tableid";
        $stmt=$this->_db->prepare($sql);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data[0]['Max(pid)']+1;
    }

    public function positionnumber($tid){
        $invisible=0;
        $sql="select position from pre_forum_post WHERE `invisible`=:invisible AND `tid`=:tid ORDER BY position ASC";
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':invisible',$invisible);
        $stmt->bindParam(':tid',$tid);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function test($tid,$position){
        $posarray=$this->positionnumber($tid);
        $floor=$posarray[$position-1]['position'];
        $sql = 'select pid,message,dateline,author,bbcodeoff from pre_forum_post where `tid`=:tid AND `position`=:position';
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':tid',$tid);
        $stmt->bindParam(':position',$floor);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function usereply($tid,$author,$authorid,$message,$position){
        $posarray=$this->positionnumber($tid);
        $floor=$posarray[$position-1]['position'];
        $sql = 'select pid,message,dateline,author,bbcodeoff from pre_forum_post where `tid`=:tid AND `position`=:position';
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':tid',$tid);
        $stmt->bindParam(':position',$floor);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $time=date('Y-m-d H:i', $data[0]['dateline']);
        if($data[0]['bbcodeoff']==-1)
        {
            $str=$data[0]['message'];
        }
        if($data[0]['bbcodeoff']==0)
        {
            $str = substr(strrchr($data[0]['message'], '[/quote]'), 8);
        }

        $sql1="INSERT INTO pre_forum_post (`pid`,`fid`,`tid`, `author`, `authorid`,`dateline`,`useip`,`port`,`usesig`,`bbcodeoff`,`smileyoff`,`message`) VALUES (:pid,:fid,:tid,:author,:authorid,:dateline,:useip,:port,:usesig,:bbcodeoff,:smileyoff,:message)";
        $report=$this->_db->prepare($sql1);
        $bbcodeoff=0;
        $dateline=time();
        $usesig=1;
        $smileyoff=-1;
        $pid=$this->pidnumber()-1;
        $insert="[quote][size=2][url=forum.php?mod=redirect&goto=findpost&pid=".$data[0]['pid']."&ptid=".
            $tid."][color=#999999]".$data[0]['author']." 发表于 ".$time."[/color][/url][/size]\r\n".$str."[/quote]  ".$message;
        $fid=2;
        $report->bindParam(':tid',$tid);
        $report->bindParam(':author',$author);
        $report->bindParam(':authorid',$authorid);
        $report->bindParam(':pid',$pid);
        $report->bindParam(':fid',$fid);
        $report->bindParam(':tid',$tid);
        $report->bindParam(':author',$author);
        $report->bindParam(':authorid',$authorid);
        $report->bindParam(':dateline',$dateline);
        $report->bindParam(':useip',$_SERVER['HTTP_HOST']);
        $report->bindParam(':port',$_SERVER["SERVER_PORT"]);
        $report->bindParam(':usesig',$usesig);
        $report->bindParam(':bbcodeoff',$bbcodeoff);
        $report->bindParam(':smileyoff',$smileyoff);
        $report->bindParam(':message',$insert);
        if(!$report->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
//                return array(
//            'pid'=>$pid,
//            'tid'=>$tid,
//            'author'=>$author,
//            'authorid'=>$authorid,
//            'message'=>$message,
//        );
    }
    public function replies($tid){
        $sql = 'select subject,message from pre_forum_post where `tid`=:tid AND `first`=1';
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':tid',$tid);
        if(!$stmt->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $title= $data[0]['subject'];
        $titlecontent=$data[0]['message'];
        $invisible=0;
        $sql1='select message,bbcodeoff,pid from pre_forum_post WHERE `tid`=:tid AND `invisible`=:invisible ORDER BY position ASC';
        $report=$this->_db->prepare($sql1);
        $report->bindParam(':tid',$tid);
        $report->bindParam(':invisible',$invisible);
        if(!$report->execute()){
            throw new Exception('插入数据包失败',ErrorCode::INSERT_FAIL);
        }
        $data2=$report->fetchAll(PDO::FETCH_ASSOC);
        $dataarray=array();
        for($i=1;$i<=count($data2,1)/4-1;$i++)
        {
            if($data2[$i]['bbcodeoff']==-1){
                $dataarray[$i-1]['pid']=$data2[$i]['pid'];
                $dataarray[$i-1]['quote']=" ";
                $dataarray[$i-1]['content']=$data2[$i]['message'];
            }
            if($data2[$i]['bbcodeoff']==0){
                $dataarray[$i-1]['pid']=$data2[$i]['pid'];
                $dataarray[$i-1]['quote']=$data2[$i]['message'];
                $str = substr(strrchr($data2[$i]['message'], '[/quote]'), 8);
                $dataarray[$i-1]['content']=$str;
            }
        }
        $reply=json_encode($dataarray, JSON_UNESCAPED_UNICODE);
        $string="{\"tid\":\"".$tid."\", \"title\":\"【主题】".$title."\", \"content\":\"".$titlecontent.
            "\", \"replies\":".$reply."}";
        return $string;
    }

}