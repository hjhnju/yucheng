<?php
/**
 * 借款附件
 * @author guojinli
 */
class Apply_Object_Attach extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'apply_attach';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Apply_Object_Attach';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'apply_id', 'userid', 'type', 'title', 'url', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'apply_id'    => 'applyId',
        'userid'      => 'userid',
        'type'        => 'type',
        'title'       => 'title',
        'url'         => 'url',
        'status'      => 'status',
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
        'userid'      => 1,
        'type'        => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_Attach
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
     * 借款ID
     * @var integer
     */
    public $applyId;

    /**
     * 用户ID
     * @var integer
     */
    public $userid;

    /**
     * 类别 1申请人及担保人信息材料 2学校基本信息材料 3学校财务证明材料
     * @var integer
     */
    public $type;

    /**
     * 标题
     * @var string
     */
    public $title;

    /**
     * 地址
     * @var string
     */
    public $url;

    /**
     * 状态
     * @var integer
     */
    public $status;

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
