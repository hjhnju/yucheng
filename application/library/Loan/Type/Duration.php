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
        self::MONTH_HALF => '半个月',
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
        self::MONTH_HALF => '半个月',
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
     * @return number
     */
    public function getDays($type) {
        return 1;
    }
}