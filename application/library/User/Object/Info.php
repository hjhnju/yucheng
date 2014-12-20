<?php
/**
 * 
 * @author hejunhua
 */
class User_Object_Info extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_info';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'uid';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'User_Object_Info';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('uid', 'type', 'real_name', 'certificate_type', 'certificate_content', 'headurl', 'huifu_uid', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'uid'                 => 'uid',
        'type'                => 'type',
        'real_name'           => 'realName',
        'certificate_type'    => 'certificateType',
        'certificate_content' => 'certificateContent',
        'headurl'             => 'headurl',
        'huifu_uid'           => 'huifuUid',
        'create_time'         => 'createTime',
        'update_time'         => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

    /**
     * @param array $data
     * @return User_Object_Info
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 用户id
     * @var 
     */
    public $uid;

    /**
     * 用户类型
     * @var 
     */
    public $type;

    /**
     * 用户真实姓名
     * @var string
     */
    public $realName;

    /**
     * 证件类型
     * @var 
     */
    public $certificateType;

    /**
     * 证件内容
     * @var string
     */
    public $certificateContent;

    /**
     * 头像URL
     * @var string
     */
    public $headurl;

    /**
     * 汇付用户ID
     * @var string
     */
    public $huifuUid;

    /**
     * 注册时间
     * @var string
     */
    public $createTime;

    /**
     * 修改资料时间
     * @var string
     */
    public $updateTime;

}
