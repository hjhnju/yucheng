<?php
/**
 * 投资标的状态
 * @author jiangsongfang
 *
 */
class Awards_Type_TicketType extends Base_Type {
    /**
     * 1 现金券
     * @var integer
     */
    const CASH = 1;
    /**
     * 2 利息券
     * @var integer
     */
    const INSTREST = 2;
    /**
     * 3 代金券 
     * @var integer
     */
    const VOUCHER = 2;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'ticket_type';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::CASH     => '现金券',
        self::INSTREST => '利息券',
        self::VOUCHER  => '代金券',
    );

    protected static $units = array(
        self::CASH     => '元',
        self::INSTREST => '%',
        self::VOUCHER  => '元',
    );

    public static function getUnit($intType){
        return self::$units[$intType];
    }
}