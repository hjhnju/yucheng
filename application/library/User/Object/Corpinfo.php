<?php
/**
 * 企业用户信息表
 * @author hejunhua
 */
class User_Object_Corpinfo extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_corpinfo';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'userid';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'User_Object_Corpinfo';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'corpname', 'busicode', 'instucode', 'taxcode', 'area', 'years', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'userid'      => 'userid',
        'corpname'    => 'corpname',
        'busicode'    => 'busicode',
        'instucode'   => 'instucode',
        'taxcode'     => 'taxcode',
        'area'        => 'area',
        'years'       => 'years',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'userid'      => 1,
        'area'        => 1,
        'years'       => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return User_Object_Corpinfo
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 用户id
     * @var integer
     */
    public $userid;

    /**
     * 企业名称
     * @var string
     */
    public $corpname;

    /**
     * 营业执照
     * @var string
     */
    public $busicode;

    /**
     * 组织机构代码证
     * @var string
     */
    public $instucode;

    /**
     * 税务登记号
     * @var string
     */
    public $taxcode;

    /**
     * 所在地
     * @var integer
     */
    public $area;

    /**
     * 注册年限
     * @var integer
     */
    public $years;

    /**
     * 注册时间
     * @var integer
     */
    public $createTime;

    /**
     * 修改资料时间
     * @var integer
     */
    public $updateTime;

}
