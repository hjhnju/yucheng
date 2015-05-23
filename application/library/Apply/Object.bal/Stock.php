<?php
/**
 * @file 公司股份结构表 
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
     * 字段列表
     * @var array
     */
    protected $fields = array('id', 'name', 'weight', 'apply_id', 'create_time', 'update_time');
	
    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'       => 'id',
        'name'     => 'name',
        'weight'   => 'weight',
        'apply_id' => 'applyId',
        'create_time'    => 'createTime',
        'update_time'    => 'updateTime',
    );
     
    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'       => 1,
        'apply_id' => 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_Apply
     */
    public static function init($data) {
        return parent::initObject(self::getClassName(), $data);
    }

    /**
	 * @param  null
	 * @return 返回当前类的名字
	 */
	public static function getClassName(){
		return __CLASS__;
	}

    /**
     * 自增长的id
     * @var [int]
     */
    public $id;

    /**
     * 名字
     * @var [string]
     */
    public $name;

    /**
     * 份额
     * @var [int]
     */
    public $weight;

    /**
     * 申请id
     * @var [int]
     */
    public $applyId;
    /**
     * 创建时间
     * @var [int]
     */
    public $createTime;

    /**
     * 更新时间
     * @var [int]
     */
    public $updateTime;
}