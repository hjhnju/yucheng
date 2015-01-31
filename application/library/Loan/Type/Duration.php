<?php
/**
 * 借款时长
 * @author jiangsongfang
 *
 */
class Loan_Type_Duration extends Base_Type {
    /**
     * 1天
     * @var integer
     */
    const DAY = 1;
    /**
     * 一周 7天
     * @var integer
     */
    const WEEK = 7;
    /**
     * 半个月
     * @var integer
     */
    const MONTH_HALF = 15;
    /**
     * 一个月
     * @var integer
     */
    const MONTH = 30;
    /**
     * 两个月
     * @var integer
     */
    const MONTH_2 = 60;
    /**
     * 三个月
     * @var integer
     */
    const MONTH_3 = 90;
    /**
     * 六个月
     * @var integer
     */
    const MONTH_6 = 180;
    /**
     * 九个月
     * @var integer
     */
    const MONTH_9 = 270;
    /**
     * 12个月
     * @var integer
     */
    const MONTH_12 = 360;
    /**
     * 18个月
     * @var integer
     */
    const MONTH_18 = 480;
    /**
     * 24个月
     * @var integer
     */
    const MONTH_24 = 720;
    /**
     * 36个月
     * @var integer
     */
    const MONTH_36 = 1080;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'duration';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'duration_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::DAY => '1天',
        self::WEEK => '7天',
        self::MONTH_HALF => '15天',
        self::MONTH => '1个月',
        self::MONTH_2 => '2个月',
        self::MONTH_3 => '3个月',
        self::MONTH_6 => '6个月',
        self::MONTH_9 => '9个月',
        self::MONTH_12 => '12个月',
        self::MONTH_18 => '18个月',
        self::MONTH_24 => '24个月',
        self::MONTH_36 => '36个月',
    );
    
    /**
     * 开放的借款期限
     * @var array
     */
    public static $enabled = array(
        self::MONTH_HALF => '15天',
        self::MONTH => '1个月',
        self::MONTH_2 => '2个月',
        self::MONTH_3 => '3个月',
        self::MONTH_6 => '6个月',
        self::MONTH_9 => '9个月',
        self::MONTH_12 => '12个月',
        self::MONTH_18 => '18个月',
        self::MONTH_24 => '24个月',
        self::MONTH_36 => '36个月',
    );
    
    /**
     * 通过duration获取天数
     * @param integer $type duration类型
     * @param integer $startTime 开始时间
     * @return number
     */
    public static function getDays($type, $startTime) {
        if ($type < 30) {
            return $type;
        }
        
        $months = self::getMonths($type);
        $date = new DateTime();
        $date->setTimestamp($startTime);
        $date->modify("+{$months}months");
        
        $days = ($date->getTimestamp() - $startTime) / 3600 / 24;
        return $days;
    }
    
    /**
     * 通过duration获取有多少个月
     * @param number $type
     * @return number
     */
    public static function getMonths($type) {
        return floor($type / 30);
    }
    
    /**
     * 获取某个duration的截止日期
     * @param number $type duration的typeid
     * @param number $startTime 时间起始点
     * @return number 截止时间
     */
    public static function getTimestamp($type, $startTime) {
        if ($type < 30) {
            return $type * 24 * 3600;
        }
        
        $months = self::getMonths($type);
        $date = new DateTime();
        $date->setTimestamp($startTime);
        $date->modify("+{$months}months");
        return $date->getTimestamp();
    }
}
