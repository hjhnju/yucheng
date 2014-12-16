<?php
/**
 * 注册奖励表
 * @author hejunhua
 */
class Awards_Object_Regist extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'awards_regist';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'userid';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Awards_Object_Regist';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'status', 'amount', 'memo', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'userid'      => 'userid',
        'status'      => 'status',
        'amount'      => 'amount',
        'memo'        => 'memo',
        'create_time' => 'createTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

    /**
     * @param array $data
     * @return Awards_Object_Regist
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 用户id
     * @var 
     */
    public $userid;

    /**
     * 领取状态:1-未达到, 2-已达到未领取，3-已领取
     * @var 
     */
    public $status;

    /**
     * 奖励金额
     * @var 
     */
    public $amount;

    /**
     * 备注
     * @var string
     */
    public $memo;

    /**
     * 创建时间
     * @var 
     */
    public $createTime;

}
