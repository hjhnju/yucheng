<?php
/**
 * 信息来源
 * @author guojinli
 *
 */
class Apply_Type_Source extends Base_Type {
    /**
     * 行业协会推荐
     * @var integer
     */
    const OPTION_1 = 1;
    /**
     * 朋友推荐
     * @var integer
     */
    const OPTION_2 = 2;
    /**
     * 网络搜索
     * @var integer
     */
    const OPTION_3 = 3;
    /**
     * 广告
     * @var integer
     */
    const OPTION_4 = 4;
    /**
     * 其他
     * @var integer
     */
    const OPTION_5 = 5;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'source';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'source_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::OPTION_1 => '行业协会推荐',
        self::OPTION_2 => '朋友推荐',
        self::OPTION_3 => '网络搜索',
        self::OPTION_4 => '广告',
        self::OPTION_5 => '其他',
    );
}