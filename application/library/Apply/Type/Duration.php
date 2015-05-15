<?php
/**
 * 借款时长
 * @author guojinli
 */
class Apply_Type_Duration extends Base_Type {
    /**
     * 保存的类型是天
     * @var integer
     */
    const DAY   = 1;
    /**
     * 保存的类型是月
     * @var integer
     */
    const MONTH = 2;
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
     * 状态名，定义这些常量只是标示一下借款的期限
     * @var array
     */
    public static $names = array(
        array(
            'type' => self::DAY,
            'value' => '15',
            'label' => '15天',
        ),
        array(
            'type' => self::MONTH,
            'value' => '1',
            'label' => '1个月',
        ),
        array(
            'type' => self::MONTH,
            'value' => '2',
            'label' => '2个月',
        ),
        array(
            'type' => self::MONTH,
            'value' => '3',
            'label' => '3个月',
        ),
        array(
            'type' => self::MONTH,
            'value' => '6',
            'label' => '6个月',
        ),
    );

    /**
     * 通过duration获取天数
     * @param string $type year|month|day
     * @param integer $startTime 开始时间 是个时间戳
     * @return number 返回从初始时间经过哪种类型后是多少天
     */
    public static function getDays($type, $startTime, $value) {
        $date = new DateTime();
        $date->setTimestamp($startTime);
        if ($type == self::MONTH){
            $date->modify("+{$value}months");
        }
        if ($type == self::DAY){
            $date->modify("+{$value}days");
        }
        $days = ($date->getTimestamp() - $startTime) / 3600 / 24;

        return $days;
    }

    /**
     * 获取某个duration的截止日期
     * @param string $type year|month|day
     * @param number $startTime 时间起始点
     * @return number 截止时间
     */
    public static function getTimestamp($type, $startTime, $value) {
        $date = new DateTime();
        $date->setTimestamp($startTime);
        if ($type == self::MONTH){
            $date->modify("+{$value}months");
        }
        if ($type == self::DAY){
            $date->modify("+{$value}days");
        }

        return $date->getTimestamp();
    }
}
