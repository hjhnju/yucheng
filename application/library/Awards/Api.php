<?php
/**
 * 邀请模块API接口
 * @author hejunhua
 *
 */
class Awards_Api {

    /**
     * Awards_Api::registNotify($userid, $inviterid)
     * 用户注册时通知模块
     * 不管是否有邀请者, 注册成功后均通知
     * @param $userid
     * @param $inviterid, default null
     */
    public static function registNotify($userid, $inviterid = null){
        $logic = new Awards_Logic_Awards();
        $ret   = $logic->awardWhenRegist($userid, $inviterid);
        if(!$ret){
            Base_Log::warn(array('userid'=>$userid, 
                'inviterid'=>$inviterid, 'ret'=>$ret));
        }
        return $ret;
    }

    /**
     * Awards_Api::getAwards($userid)
     * 获取奖励列表
     * 1. 获取所有被邀请人的注册状态，投资进度
     * 2. 判断奖励条件是否达成
     * 3. 修改达成条件的状态
     * @param integer $userid
     * @return array
     */
    public static function getAwards($userid) {
        $logic = new Awards_Logic_Awards();
        return $logic->getAwards($userid);
    }

    /**
     * Awards_Api::getInviteUrl($userid)
     * 获取个人邀请URL
     * @param integer $userid
     * @return string $url, such as 'http://www.xingjiaodai.com/invite/{$code}'
     */
    public static function getInviteUrl($userid) {
        $logic = new Awards_Logic_Invite();
        return $logic->getInviteUrl($userid);
    }
    
    /**
     * Awards_Api::receiveAwards($userid)
     * 领取奖励Api
     * @param integer $userid
     * 
     */
    public static function receiveAwards($userid) {
    	 
    }
}
