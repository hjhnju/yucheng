<?php
/**
 * 邀请好友送米环米宝
 */ 
abstract class Awards_Activity_Invite20150415 extends Awards_Activity_Base {

    public function __construct(){
        $this->name      = "邀请好友送米环米宝";
        $this->startTime = strtotime("2015-04-15 00:00:00");
        $this->endTime   = strtotime("2015-05-15 23:59:59");
        $this->desc      = '邀请满6名可领取';
    }

    public function isAchieved(Awards_Ticket $objTicket){
        //领奖时无特殊达成条件(从领奖文件读的，也可以再判断一次)
        return true;
    }

    /**
     * 根据单笔投资额获取奖励（单位元）
     */
    public function getValue($mixArg){
        return 79.00;
    }


    /**
     * 为参与活动的用户发放奖券
     */
    public function giveAward($userid, $arrParam = array()){
        $value = $this->getValue();
        $address = $arrParam['address'];
        if($this->isActive()){
            $entity = new Awards_Object_Entity();
            $entity->id       = $arrParam['id'];
            $entity->userid   = $userid;
            $entity->name     = $arrParam['name']; //小米手环
            $entity->type     = Awards_Type_AwardType::INVITE;
            $entity->value    = $value;
            $entity->activity = get_class();
            $entity->payTime  = strtotime("2015-04-15 15:00:00");
            $entity->status   = 2;//已发放
            $ret              = $entity->save();
            if(!$ret){
                Base_Log::error(array(
                    'msg'      => 'Fail create entity award',
                    'userid'   => $userid,
                ));
                return false;
            }
            //给个系统通知
            Msg_Api::sendmsg($userid, Msg_Type::ACTIVE_AWARD,
                 array('name'=> $entity->name, 'address'=> $address));
            return true;
        }
        return false;
    }
}
