<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 2017/10/20
 * Time: 14:28
 */
class register{
    function __construct($_db)
    {
        $this->_db=$_db;
    }

    private function isregister($openid)
    {
        $sql='select openid from pre_weixin_control where `openid`=:openid';
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':openid',$openid);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($result);
    }
    private function insertwechat($openid,$nickname,$uid){
        $sql='INSERT INTO pre_weixin_control (`openid`,`nickname`,`uid`) VALUES (:openid,:nickname,:uid)';
        $stmt=$this->_db->prepare($sql);
        $stmt->bindParam(':openid',$openid);
        $stmt->bindParam(':nickname',$nickname);
        $stmt->bindParam(':uid',$uid);
        if(!$stmt->execute()){
            throw new Exception('pre_weixin_control插入数据库失败',ErrorCode::UPDATE_FAIL);
        }
    }
    public function register($openid,$nickname){
        if($this->isregister($openid))
        {
            $isregister=1;
        }else{
            $isregister=0;
        }
        if(!$isregister){
            $ip = $_SERVER["REMOTE_ADDR"];
            $date=date();
            $ucenter_members='INSERT INTO pre_ucenter_members (`username`, `regip`,`regdate`) VALUES (:username,:regip,:regdate)';
            $stmt=$this->_db->prepare($ucenter_members);
            $stmt->bindParam(':username',$nickname);
            $stmt->bindParam(':regip',$ip);
            $stmt->bindParam(':regdate',$date);
            if(!$stmt->execute()){
                throw new Exception('ucenter_members更新数据库失败',ErrorCode::UPDATE_FAIL);
            }
            $selectuid='select uid from pre_ucenter_members WHERE `username`=:username';
            $getuid=$this->_db->prepare($selectuid);
            $getuid->bindParam(':username',$nickname);
            $result = $getuid->fetch(PDO::FETCH_ASSOC);
            $uid=$result[0]['uid'];
            $this->insertwechat($openid,$nickname,$uid);
            $common_member='INSERT INTO pre_common_member (`username`,`groupid`,`regdate`,`credits`,`timeoffset`) VALUES (:username,10,:regdate,2,9999)';
            $common_memberinsert=$this->_db->prepare($common_member);
            $common_memberinsert->bindParam(':username',$nickname);
            $common_memberinsert->bindParam(':regdate',$date);
            if(!$common_memberinsert->execute()){
                throw new Exception('common_member更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $common_credit_rule_log='INSERT INTO pre_common_credit_rule_log (`uid`,`rid`,`total`,`cyclenum`,`extcredits2`,`dateline`)
                                      VALUES (:uid,15,1,1,2,:dateline)';
            $common_credit_rule_loginsert=$this->_db->prepare($common_credit_rule_log);
            $common_credit_rule_loginsert->bindParam(':uid',$uid);
            $common_credit_rule_loginsert->bindParam(':dateline',$date);
            if(!$common_credit_rule_loginsert->execute()){
                throw new Exception('common_credit_rule_log更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $common_member_count='INSERT INTO pre_common_member_count (`uid`,`extcredits2`) VALUES (:uid,2)';
            $common_member_countinsert=$this->_db->prepare($common_member_count);
            $common_member_countinsert->bindParam(':uid',$uid);
            if(!$common_member_countinsert->execute()){
                throw new Exception('common_member_countinsert更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $common_member_field_forum='INSERT INTO pre_common_member_field_forum (`uid`) VALUES (:uid)';
            $common_member_field_foruminsert=$this->_db->prepare($common_member_field_forum);
            $common_member_field_foruminsert->bindParam(':uid',$uid);
            if(!$common_member_field_foruminsert->execute()){
                throw new Exception('common_member_field_foruminsert更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $common_member_field_home='INSERT INTO pre_common_member_field_home (`uid`) VALUES (:uid)';
            $common_member_field_homeinsert=$this->_db->prepare($common_member_field_home);
            $common_member_field_homeinsert->bindParam(':uid',$uid);
            if(!$common_member_field_homeinsert->execute()){
                throw new Exception('common_member_field_homeinsert更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $common_member_profile='INSERT INTO pre_common_member_profile (`uid`) VALUES (:uid)';
            $common_member_profileinsert=$this->_db->prepare($common_member_profile);
            $common_member_profileinsert->bindParam(':uid',$uid);
            if(!$common_member_profileinsert->execute()){
                throw new Exception('common_member_profile更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $common_member_status='INSERT INTO pre_common_member_status (`uid`,`regip`,`lastip`,`lastvisit`,`lastactivity`)
                                    VALUES (:uid,:regip,:lastip,:lastvisit,:lastactivity)';
            $common_member_statusinsert=$this->_db->prepare($common_member_status);
            $common_member_statusinsert->bindParam(':uid',$uid);
            $common_member_statusinsert->bindParam(':regip',$ip);
            $common_member_statusinsert->bindParam(':lastip',$ip);
            $common_member_statusinsert->bindParam(':lastvisit',$date);
            $common_member_statusinsert->bindParam(':lastactivity',$date);
            if(!$common_member_statusinsert->execute()){
                throw new Exception('common_member_status更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $today=date('Ymd');
            $common_statuser='INSERT INTO pre_common_statuser (`uid`,`daytime`,`type`) VALUES (:uid,:daytime,login)';
            $common_statuserinsert=$this->_db->prepare($common_statuser);
            $common_statuserinsert->bindParam(':uid',$uid);
            $common_statuserinsert->bindParam(':daytime',$today);
            if(!$common_statuserinsert->execute()){
                throw new Exception('common_statuser更新数据库失败',ErrorCode::UPDATE_FAIL);
            }

            $ucenter_memberfields='INSERT INTO pre_ucenter_memberfields(`uid`) VALUES (:uid)';
            $ucenter_memberfieldsinsert=$this->_db->prepare($ucenter_memberfields);
            $ucenter_memberfieldsinsert->bindParam(':uid',$uid);
            if(!$ucenter_memberfieldsinsert->execute()){
                throw new Exception('ucenter_memberfieldsinsert更新数据库失败',ErrorCode::UPDATE_FAIL);
            }
        }else{
            $sql="";
        }
    }
}