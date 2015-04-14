<?php
/**
 * 奖券的状态
 * @author hejunhua
 *
 */
class Awards_Type_TicketStatus extends Base_Type {
    /**
     * 1 未达成
     * @var integer
     */
    const NOT_FINISH = 1;
    /**
     * 2 未使用
     * @var integer
     */
    const NOT_USED = 2;
    /**
     * 3 已使用 
     * @var integer
     */
    const EXCHANGED = 3;
    /**
     * 4 已过期
     * @var integer
     */
    const OVER = 4;

    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'status';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'ticket_status';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::NOT_FINISH  => '尚未达成',
        self::NOT_USED    => '未使用',
        self::EXCHANGED   => '已使用',
        self::OVER        => '已过期',
    );
}