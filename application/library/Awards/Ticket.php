<?php
/**
 * 奖券类
 * @author hejunhua
 * @since  2015-04-12
 */
class Awards_Ticket extends Awards_Object_Ticket {

    //活动类
    protected $actObj;

    public function setData($data){
        parent::setData($data);
        $actName = $this->activity;
        if(!empty($actName) && class_exists($actName)){
            $this->actObj = new $actName();
        }else{
            Base_Log::error(array('msg'=>'activity not exists', 'activity'=>$actName));
        }
    }

    public function __construct($id = 0, $db = null) {
        parent::__construct($id, $db);
        $actName = $this->activity;
        if(!empty($actName) && class_exists($actName)){
            $this->actObj = new $actName();
        }else{
            Base_Log::error(array('msg'=>'activity not exists', 'activity'=>$actName));
        }
    }

    /**
     * 奖券是否可以兑换
     */
    public function isEnabled(){
        $isEnabled = false;
        if($this->status === Awards_Type_TicketStatus::NOT_FINISH){
            //未达成状态, 判断对于该用户活动条件是否已达成
            $isEnabled = $this->actObj->isAchieved($this);
            if($isEnabled){
                $this->status = Awards_Type_TicketStatus::NOT_USED;
                $isEnabled = $this->save();
            }
        }elseif ($this->status === Awards_Type_TicketStatus::NOT_USED){
            $isEnabled = true;
        }
        
        return $isEnabled;
    }

    /**
     * 奖励来源
     */
    public function getSource(){
        $src = '平台奖励';
        if($this->actObj){
            //活动名称
            $src = $this->actObj->getName();
        }
        return $src;
    }

    /**
     * 奖券有效期
     * @return int timestamp
     */
    public function getValidTime(){
        if(!$this->isEnabled){
            return $this->actObj->endTime;
        }
        return $this->validTime;
    }

    public function getDesc(){
        if($this->actObj){
            return $this->actObj->getDesc();
        }
        return '';
    }

    /**
     * 兑换使用奖券
     * @param  int $userid 兑换发起人
     * @return boolean
     */
    public function exchange($userid){
        if($userid != $this->userid){
            Base_Log::notice(array('msg'=>'非拥有用户不能兑换', 
                'userid'=>$userid, 'ticketid'=>$this->id));
            return false;
        }

        if(!$this->isEnabled()){
            Base_Log::error(array(
                'msg'      => '奖励条件尚未达成或已领取',
                'userid'   => $userid,
            )); 
            return false;
        }

        $bolRet = true;
        if(Awards_Type_TicketType::CASH === $this->ticketType){
            //现金券直接发放
            $bolRet = Finance_Api::transfer($this->userid, $this->value);
        }

        if($bolRet){
            $this->status = Awards_Type_TicketStatus::EXCHANGED;
            $bolRet = $this->save();
        }

        return $bolRet;
    }

    /**
     * 获取奖券价值描述
     * @return string
     */
    public function getValueDesc(){
        $typeName = Awards_Type_TicketType::getTypeName($this->ticketType);
        $unit     = Awards_Type_TicketType::getUnit($this->ticketType);
        return $this->value . $unit . $typeName;
    }
    
}
