<?php
class Awards_Ticket extends Awards_Object_Ticket {

    protected $actObj;

    protected $isExchangeable;

    public function __construct($id = 0, $db = null) {
        parent::__construct($id, $db);
        $actName              = $this->activity;
        $this->isExchangeable = true;
        if(!empty($actName) && class_exists($actName)){
            $this->actObj         = new $actName();
            $this->isExchangeable = $this->actObj->isAchieved($this);
        }
    }

    /**
     * 奖券是否可以兑换
     */
    public function isExchangeable(){
        //对于该用户活动条件是否已达成
        return $isExchangeable;
    }

    /**
     * 奖券状态：（尚未达成、未使用、已领取、已过期）是否已截止
     */
    public function getStatus(){
        //已领取、已兑换
        if($this->payTime > 0){
            return Awards_Type_TicketStatus::EXCHANGED;
        }
        //已过期
        if(time() > $this->getValidTime()){
            return Awards_Type_TicketStatus::OVER;
        }
        //符合兑换条件，尚未兑换，且在有效兑换期内
        if($this->isExchangeable){
            return Awards_Type_TicketStatus::NOT_FETCHED;
        }
        //否则，还有机会完成
        return Awards_Type_TicketStatus::NOT_FINISH;
    }

    /**
     * 奖励来源
     */
    public function getSource(){
        //活动名称
        $src = '';
        if(!$this->actObj){
            $src = $this->actObj->name;
        }
        return $src;
    }

    /**
     * 奖券有效期
     * @return int timestamp
     */
    public function getValidTime(){
        if(!$this->isExchangeable){
            return $this->actObj->endTime;
        }
        return $this->validTime;
    }
    
}
