<?php
/**
 * 消息类型
 * @author hejunhua
 *
 */
class Msg_Type extends Base_Type {
    /**
     * 1 系统消息
     * @var integer
     */
    const SYSTEM = 1;
    /**
     * 2 奖励消息
     * @var integer
     */
    const AWARDS = 2;
    /**
     * 3 充值消息 
     * @var integer
     */
    const CASH = 3;
    /**
     * 4 投标放款
     * @var integer
     */
    const INVEST_MAKE_LOAN = 4;
    /**
     * 5 项目回款
     * @var integer
     */
    const INVEST_BACK = 5;
    /**
     * 6 提现消息
     * @var integer
     */
    const WITHDRAW = 6;
    
    /**
     * 7 活动奖励
     * @var integer
     */
    const ACTIVE_AWARD = 7;

    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'msg_type';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::SYSTEM           => '系统消息',
        self::AWARDS           => '奖励消息',
        self::CASH             => '充值消息',
        self::INVEST_MAKE_LOAN => '投标放款',
        self::INVEST_BACK      => '项目回款',
        self::WITHDRAW         => '提现消息',
        self::ACTIVE_AWARD     => '活动奖励',
    );
}