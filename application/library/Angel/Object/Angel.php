<?php
/**
 * 天使关注信息表
 * @author huwei
 */
class Angel_Object_Angel extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'angel';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Angel_Object_Angel';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'angelid', 'angelcode', 'angelname', 'angelimage', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'userid'      => 'userid',
        'angelid'     => 'angelid',
        'angelcode'   => 'angelcode',
        'angelname'   => 'angelname',
        'angelimage'  => 'angelimage',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'angelid'     => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Angel_Object_Angel
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 自增id
     * @var integer
     */
    public $id;

    /**
     * 用户ID 
     * @var integer
     */
    public $userid;

    /**
     * 关注的主播用户ID 
     * @var integer
     */
    public $angelid;

    /**
     * 主播码
     * @var string
     */
    public $angelcode;

    /**
     * 主播用户名
     * @var string
     */
    public $angelname;

    /**
     * 主播图像
     * @var string
     */
    public $angelimage;

    /**
     * 创建时间
     * @var integer
     */
    public $createTime;

    /**
     * 更新时间
     * @var integer
     */
    public $updateTime;

}
