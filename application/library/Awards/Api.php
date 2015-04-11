<?php
/**
 * 奖励模块API接口
 * @author hejunhua
 *
 */
class Awards_Api {

    /**
     * Awards_Api::registNotify($userid, $inviterid)
     * 用户注册时通知模块
     * 不管是否有邀请者, 注册成功后均通知
     * @param $userid
     * @return array || false
     */
    public static function registNotify($userid){        
        if(is_null($userid) || $userid <= 0) {
            Base_Log::error("invalid param", array('userid'=>$userid));
            return false;
        }
        $logic = new Awards_Logic_Award();
        //分配注册奖励
        $logic->giveRegistAward($userid);
        
        return true;
    }

    /**
     * Awards_Api::investNotify($userid, $amount)
     * @param $userid
     * @param $amount 投资金额
     */
    public static function investNotify($userid, $amount){
        $logic = new Awards_Logic_Award();
        //分配邀请人奖励
        $logic->giveInviterAward($userid, $amount);
        //分配个人投资奖励
        $logic->giveInvestAward($userid, $amount);
        
        return true;
    }

    /**
     * Awards_Api::getAwards($userid)
     * 获取奖励列表
     * 1. 获取所有被邀请人的注册状态，投资进度
     * 2. 判断奖励条件是否达成
     * 3. 修改达成条件的状态
     * @param integer $userid
     * @param integer $page
     * @return array || false
     */
    public static function getAwards($inviterid) {
        $logic = new Awards_Logic_Awards();
        return $logic->getAwards($inviterid);
    }

    /**
     * Awards_Api::getInviteUrl($userid)
     * 获取个人邀请URL
     * @param integer $userid
     * @return string $url, such as 'http://www.xingjiaodai.com/invite/{$code}' || false
     */
    public static function getInviteUrl($userid) {
    	if(is_null($userid) || $userid<0 ) {
    		Base_Log::error("invalid param",array('userid'=>$userid));
    		return false;
    	}
        $logic = new Awards_Logic_Invite();
        return $logic->getInviteUrl($userid);
    }

}
