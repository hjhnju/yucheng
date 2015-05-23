<?php
/**
 * 股份结构表
 * @author guojinli
 */
class Apply_Object_Stock extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'apply_stock';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Apply_Object_Stock';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'name', 'weight', 'apply_id', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'name'        => 'name',
        'weight'      => 'weight',
        'apply_id'    => 'applyId',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'apply_id'    => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_Stock
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 
     * @var integer
     */
    public $id;

    /**
     * 名字
     * @var string
     */
    public $name;

    /**
     * 所占股份比例
     * @var number
     */
    public $weight;

    /**
     * 申请id
     * @var integer
     */
    public $applyId;

    /**
     * 
     * @var integer
     */
    public $createTime;

    /**
     * 
     * @var integer
     */
    public $updateTime;

}
