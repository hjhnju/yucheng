<?php
class Awards_Logic_Awards {

    //奖励领取状态 1-未达到, 2-已达到未领取，3-已领取
    const STATUS_NOTFIT = 1;
    const STATUS_READY  = 2;
    const STATUS_FINISH = 3;
    
    /**
     * 用户注册时点发放的奖励
     * @param int $userid
     * @param int $type
     * @return 标准json格式 
     * status 0: 奖励插入成功
     * status 1061: 奖励失败
     */
    public function awardWhenRegist($userid, $inviterid){
        $userid    = intval($userid);
        $inviterid = intval($inviterid);
        if($userid <= 0){
            return false;
        }

        $time = date('Y-m-d H:i:s');
        //注册用户奖励
        $ret1 = false;
        $amount1 = 30;
        $regAwd = new Awards_Object_Regist();
        $regAwd->userid = $userid;
        $regAwd->status = self::STATUS_NOTFIT;
        $regAwd->amount = $amount1;
        $regAwd->create_time = $time;
        $ret1 = $regAwd->save();
        var_dump($time);
        var_dump($regAwd->toArray());die;
        var_dump($ret1);die;
        if(!$ret1){
            Base_Log::fatal(array(
                'msg'      => 'Fail create reg award',
                'userid'   => $userid,
                'inviterid'=> $inviterid,
                'amount'   => $amount1,
                'time'     => $time)
            );
        }

        //邀请人奖励
        $ret2    = true;
        $amount2 = 20;
        if($inviterid > 0){
            $ret2   = false;
            $ivtAwd = new Awards_Object_Invite();
            $ivtAwd->userid      = $userid;
            $ivtAwd->inviterid   = $inviterid;
            $ivtAwd->status      = self::STATUS_NOTFIT;
            $ivtAwd->amount      = $amount2;
            $ivtAwd->create_time = $time; 
            $ret2 = $ivtAwd->save();
            if(!$ret2){
                Base_Log::fatal(array(
                    'msg'      => 'Fail create award2',
                    'userid'   => $userid,
                    'inviterid'=> $inviterid,
                    'amount'   => $amount,
                    'time'     => $time)
                );
            }
        }

        if($ret1 && $ret2){
            Base_Log::notice(array(
                'msg'      => 'Success create award.',
                'userid'   => $userid,
                'inviterid'=> $inviterid,
                'regAmt'   => $amount1,
                'ivtAmt'   => $amount2,
                'time'     => $time)
            );
            return true;
        }
        return false;

    }

    /**
     * Invite_Api::getAwards($userid)
     * 获取奖励列表
     * 1. 获取所有被邀请人的注册状态，投资进度
     * 2. 判断奖励条件是否达成
     * 3. 修改达成条件的状态
     * TODO://
     * @param integer $userid
     * @return array
     */
    public function getAwards($userid){
        $userid = intval($userid);
        $refunds = new Invite_List_Awards();
        $filters = array('userid' => $userid);
        $refunds->setFilter($filters);
        $refunds->setOrder('create_time desc');
        $refunds->setPagesize(PHP_INT_MAX);
        $list = $refunds->toArray();
        var_dump($list);
    }
}