
<?php
/**
 * 奖励类型 1:注册奖励 2:邀请奖励 3:投资奖励
 * @author hejunhua
 *
 */
class Awards_Type_AwardType extends Base_Type {
    /**
     * 1 注册奖励
     * @var integer
     */
    const REGIST = 1;
    /**
     * 2 邀请奖励
     * @var integer
     */
    const INVITE = 2;
    /**
     * 3 投资奖励 
     * @var integer
     */
    const INVEST = 3;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'award_type';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::REGIST => '注册奖励',
        self::INVITE => '邀请奖励',
        self::INVEST => '投资奖励',
    );
}