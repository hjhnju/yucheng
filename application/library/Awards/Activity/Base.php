<?php
/**
 * 奖励活动的基类
 * 设计考虑，活动是比较少的，不采用数据库存储；
 * @author hejunhua
 */ 
class Awards_Activity_Base {

    //活动名称
    protected $name;

    //活动开始时间
    protected $startTime;

    //活动截止时间
    protected $endTime;

    /**
     * 领奖时判断用户$userid是否已完成活动领奖条件
     * 
     * 通过继承该方法可自定义活动生效条件，默认直接生效
     * @return boolean true|false
     */
    public function isAchieved(Awards_Ticket $objTicket){
        return true;
    }

    /**
     * 获取活动价值
     * @param $mixArg [description]
     * @return mix
     */
    public static function getValue($mixArg = null){
        return 0;
    }
}
