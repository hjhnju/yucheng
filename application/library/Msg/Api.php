<?php 
class Msg_Api {
    
    /**
     * 消息映射
     * 1 系统消息
     * 2 奖励消息
     * 3 充值消息
     * 4 投标放款
     * 5 项目回款
     * 6 提现消息
     * 7 活动奖励
     */
    public static $_arrMsgMap = array(
    	Msg_Type::SYSTEM => array(
    	    'type'     => '系统消息',
    	    'content'  => '欢迎您加入兴教贷！兴教贷专注教育领域投融资，为您提供最特色的全方位安全投资保障。我们相信，教育是您最好的投资。',
    	    'linkname' => '新手指南',
    	    'link'     => '/guide',
    	),
        2 => array(
            'type'     => '奖励发放',
            'content'  => '恭喜您获得 %s元现金券，请在“账户中心”－“我的奖券”中查看。',
            'linkname' => '账户中心－我的奖券',
            'link'     => '/account/award/',
    	),
        3 => array(
            'type'     => '充值',
            'content'  => '您于 %s成功充值 %s元，可在“我的账户”－“充值提现”中查看明细。',
            'linkname' => '我的账户－充值提现',
            'link'     => '/account/cash/',
        ),
        4 => array(
            'type'     => '投标放款',
            'content'  => '您投资的项目%s已成交，投资额 %s元已拨划至借款人账户中。可在“我的账户”－“我的投资”中查看明细。',
            'linkname' => '我的账户－我的投资',
            'link'     => '/account/invest/',
        ),
        5 => array(
            'type'     => '项目回款',
            'content'  => '回款通知：您今日收到投资还款 %s元。助力教育、投资未来，邀请朋友一起分享收益吧！',
            'linkname' => '我的账户－我的投资',
            'link'     => '/account/invest/',
        ),
        6 => array(
            'type'     => '提现',
            'content'  => '您的 %s元提现申请已通过审核，预计将于1-2个工作日到达您的账户。',
            'linkname' => '我的账户－充值提现',
            'link'     => '/account/cash/',
        ),
        7 => array(
            'type'     => '活动奖励',
            'content'  => '恭喜您获得活动奖励，您的奖品%s一个已发出，请注意查收。欢迎您继续参与活动。携手兴教贷，助力教育、投资未来！',
            'linkname' => '查看活动',
            'link'     => '/activity/spring',
        ),
    );
    
    /**
     * 根据消息类型返回消息link
     * @param string $strType
     * @return array
     */
    public static function getLink($strType){
        foreach (self::$_arrMsgMap as $val){
            if($val['type'] === $strType){
                return array(
                    'link'     =>$val['link'],
                    'linkname' => $val['linkname'],
                );
            }
        }
    }
    
    /**
     * 发送系统消息
     * @param integer $fromid
     * @param integer $toid
     * @param int     $intType：消息类型，定义见 Msg_Type
     * @param array   $arrParam
     * @return true|false 成功true, 失败 false
     */
    public static function sendmsg($toid, $intType, $arrParam = array(), $fromid = 0) {
        $objMsg = new Msg_Object_Msg();
        $objMsg->sender    = $fromid;
        $objMsg->receiver  = $toid;
        $objMsg->type      = self::$_arrMsgMap[$intType]['type'];
        $objMsg->title     = Msg_Type::getTypeName($intType);
        if(!empty($arrParam)){
            $strContent = vsprintf(self::$_arrMsgMap[$intType]['content'],$arrParam);            
        }else{
            $strContent = self::$_arrMsgMap[$intType]['content'];
        }
        $objMsg->content   = $strContent;
        $ret = $objMsg->save();
        Base_Log::notice(array(
        	'msg'    => 'msg send',
        	'fromid' => $fromid,
        	'toid'   => $toid,
        ));
        return $ret;
    }
    
    /**
     * 获取未读消息数
     * @param int $uid
     * @return int 
     */
    public static function getUnreadMsgNum($uid){
        $msg = new Msg_Logic_Msg();
        $ret = $msg->getUnread($uid);
        return $ret;
    }
}