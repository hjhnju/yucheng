<?php
class Awards_Logic_Awards {

    //;注册奖励
    private $regAmt;
    //;注册人投资满额
    private $regLimitAmt;
    //;邀请人奖励
    private $invAmt;
    //;被邀请人的奖励
    private $invLimitAmt;

    public function __construct(){
        $this->regAmt      = Base_Config::getConfig('awards.regist.amount', CONF_PATH.'/awards.ini');
        $this->regLimitAmt = Base_Config::getConfig('awards.regist.limitamt', CONF_PATH.'/awards.ini');
        
        $this->invAmt      = Base_Config::getConfig('awards.inviter.amount', CONF_PATH.'/awards.ini');
        $this->invLimitAmt = Base_Config::getConfig('awards.inviter.limitamt', CONF_PATH.'/awards.ini');
    }

    //奖励领取状态 1-未达到, 2-已达到未领取，3-已领取
    const STATUS_NOTFIT = 1;
    const STATUS_READY  = 2;
    const STATUS_FINISH = 3;
    //一个月有效期
    CONST AW_MONTHS     = 1;

    /**
     * 用户注册时发放奖励
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

        $time = date('Y-m-d H:i:s',time());
        //注册用户奖励
        $ret1                = false;
        $regAwd              = new Awards_Object_Regist();
        $regAwd->userid      = $userid;
        $regAwd->status      = self::STATUS_NOTFIT;
        $regAwd->amount      = $this->regAmt;
        $regAwd->create_time = $time;
        $ret1                = $regAwd->save();
        if(!$ret1){
            Base_Log::error(array(
                'msg'      => 'Fail create reg award',
                'userid'   => $userid,
                'inviterid'=> $inviterid,
                'amount'   => $this->regAmt,
                'time'     => $time,
            ));
        }
        //邀请人奖励
        $ret2    = true;
        if($inviterid > 0){
            $ret2                = false;
            $ivtAwd              = new Awards_Object_Invite();
            $ivtAwd->userid      = $userid;
            $ivtAwd->inviterid   = $inviterid;
            $ivtAwd->status      = self::STATUS_NOTFIT;
            $ivtAwd->amount      = $this->invAmt;
            $ivtAwd->create_time = $time; 
            $ret2                = $ivtAwd->save();
            if(!$ret2){
                Base_Log::fatal(array(
                    'msg'      => 'Fail create award2',
                    'userid'   => $userid,
                    'inviterid'=> $inviterid,
                    'amount'   => $this->invAmt,
                    'time'     => $time,
                ));
            }
        }

        if($ret1 && $ret2){
            Base_Log::notice(array(
                'msg'      => 'Success create award.',
                'userid'   => $userid,
                'inviterid'=> $inviterid,
                'regAmt'   => $this->regAmt,
                'ivtAmt'   => $this->invAmt,
                'time'     => $time,
            ));
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
     * @param integer page
     * @param integer $userid
     * @return array or false
     */
    public function getAwards($inviterid) {
        $inviterid = intval($inviterid);
        if($inviterid <= 0){
            Base_Log::error(array(
                'msg'       => '请求参数错误',
                'inviterid' => $inviterid,
            ));
            return false;
        }

        $ret       = array();

        //首先拿到邀请人的信息
        $inviter   = array();
        $objInvier = User_Api::getUserObject($inviterid);
        $huifuid   = $objInvier->huifuid;
        if(empty($huifuid)) {
            $inviter['tenderAmount']   = 0.00;
            $inviter['registProgress'] = 2;
        } else {
            $inviter['registProgress'] = 1;
        }       
        $startTime               = $objInvier->createTime;
        $endTime                 = $startTime+3600*24*30*self::AW_MONTHS;
        $inviter['tenderAmount'] = Invest_Api::getUserAmount($inviterid,$startTime,$endTime);        
        if(empty($inviter['tenderAmount'])) {
            $inviter['tenderAmount'] = 0.00;
        }                   

        //取一个记录，不是唯一的吗？Awards_Oject_Regist不行？
        $regRegist     = new Awards_List_Regist();
        $filters       = array('userid' => $inviterid);
        $regRegist->setFilter($filters);
        $list          = $regRegist->toArray();
        $inviterData   = $list['list'][0];
        $inviterStatus = intval($inviterData['status']);     
  

        $inviter['awardAmt'] = '点击领取'. $this->regAmt .'元';
        //未达到
        if($inviterStatus === self::STATUS_NOTFIT) {
            if($inviter['tenderAmount'] >= $this->regLimitAmt) {
                $this->updateRegistStatus($inviterid,self::STATUS_READY);  
                $inviter['canBeAwarded'] = 1;
            } 
            $inviter['canBeAwarded'] = 0;
        }
        //已达到未领取
        if($inviterStatus === self::STATUS_READY) {
            if(empty($huifuid)) {
                $inviter['canBeAwarded'] = 0;
            } else {
                $inviter['canBeAwarded'] = 1;
            }               
        }
        //已领取
        if($inviterStatus === self::STATUS_FINISH) {
            $inviter['canBeAwarded'] = 0;
            $inviter['awardAmt'] = '已领取'. $this->regAmt .'元';
        }                   
        $inviter['name']         = '我';
        $inviter['phone']        = $objInvier->phone;   
        $inviter['phone']        = Base_Util_String::starPhone($inviter['phone']);
        $inviter['id']           = $inviterid;  
        $percent                 = ($inviter['tenderAmount'] / $this->regLimitAmt) * 100;      
        $percent                 = ($percent <= 100) ? $percent : 100;
        $inviter['tenderAmount'] = $percent;
        //返回值得第一项为该用户的信息
        $ret[0]                  = $inviter; 
                
        //开始获取该用户邀请的用的信息       
        $invite  = new Awards_List_Invite();
        $filters = array('inviterid' => $inviterid); //caution:被邀请人的userid
        $invite->setFilter($filters);
        $invite->setOrder('create_time desc');
        $list    = $invite->toArray(); //拿到了该邀请人邀请到的所有人的信息
        $users   = $list['list'];        
        if(empty($users)) { 
            return $ret; //若没有邀请者，返回false
        }
        $count = 1;
        foreach ($users as $key=>$value) {
            $data    = array();
            $id      = $value['id'];
            $userId  = $value['userid'];
            $objUser = User_Api::getUserObject($userId);
            $huifuid = $objUser->huifuid;
            if(empty($huifuid)){
                $tenderAmount           = 0.00;
                $data['registProgress'] = 2;            
            } else {
                $data['registProgress'] = 1;
            }           
            $startTime    = $objUser->createTime;
            $endTime      = $startTime+3600*24*30*self::AW_MONTHS;
            $tenderAmount = Invest_Api::getUserAmount($userId,$startTime,$endTime); //拿到了被邀请人的投资总额
            if(empty($tenderAmount)) {
                $tenderAmount = 0.00;               
            }           
            $data['tenderAmount'] = $tenderAmount;
            $status               = intval($value['status']);
            $data['awardAmt'] = '点击领取'. $this->invAmt .'元';
            //未达到
            if($status === self::STATUS_NOTFIT) {
                if($tenderAmount >= $this->regLimitAmt) {
                    $this->updateAwardsStatus($id,self::STATUS_READY);
                    $data['canBeAwarded'] = 1;
                }
                $data['canBeAwarded'] = 0;
            }else if($status === self::STATUS_READY) {
                $inviterHuifu = $objInvier->huifuid;
                if(empty($inviterHuifu)) {
                    $data['canBeAwarded'] = 0;
                }
                else {
                    $data['canBeAwarded'] = 1;
                }               
            }else if($status === self::STATUS_FINISH) {
                $data['canBeAwarded'] = 0;
                $data['awardAmt'] = '已领取'. $this->invAmt .'元';
            }           
            $data['name']         = $objUser->name;
            $data['phone']        = Base_Util_String::starPhone($objUser->phone);
            $data['id']           = $userId;
            $percent              = floatval(($data['tenderAmount'] / $this->invLimitAmt) * 100);            
            $percent              = ($percent <= 100) ? $percent : 100;
            $data['tenderAmount'] = $percent;

            $ret[$count] = $data;
            $count++;
        }
        return $ret;
    }
    
    /**
     * 更新awards_invite表领取状态
     * @param int id 
     * @param int status
     * @return bool
     */
    public function updateAwardsStatus($id,$status) {
        if(!isset($status) || !isset($id)) {
            Base_Log::error(array(
                'msg'    => '请求参数错误',
                'status' => $status,
            ));
            return false;
        }
        $id = intval($id);
        $status = intval($status);
        
        $regInvite = new Awards_Object_Invite();
        $regInvite->id = $id;
        $regInvite->status = $status;
        $ret = $regInvite->save();
        if(!$ret) {
            Base_Log::error(array(
                'msg'    => '更新awards_invite表错误',
                'id'     => $id,
                'status' => $status,
            ));
            return false;           
        }
        return true;    
    }
    
    /**
     * 更新awards_regist表状态
     * @param int userid
     * @param int status
     * @return bool
     * 
     */
    public function updateRegistStatus($userid,$status) {
        if(!isset($userid) || !isset($status)) {
            Base_Log::error(array(
                'msg'    => '请求参数错误',
                'userid' => $userid,
                'status' => $status,
            ));
            return false;
        }
        $userid = intval($userid);
        $status = intval($status);
        $regRegist = new Awards_Object_Regist();
        $regRegist->userid = $userid;
        $regRegist->status = $status;
        $ret = $regRegist->save();
        if(!$ret) {
            Base_Log::error(array(
                'msg'    => '更新awards_regist表错误',
                'userid' => $userid,
                'status' => $status,
            ));
            return false;
        }
        return true;    
    }
    
}
