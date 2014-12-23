<?php
/**
 * 
 * @author jiangsongfang
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
    protected $prikey = 'userid';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'User_Object_Info';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'usertype', 'realname', 'certificate_type', 'certificate_content', 'headurl', 'huifuid', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'userid'              => 'userid',
        'usertype'            => 'usertype',
        'realname'            => 'realname',
        'certificate_type'    => 'certificateType',
        'certificate_content' => 'certificateContent',
        'headurl'             => 'headurl',
        'huifuid'             => 'huifuid',
        'create_time'         => 'createTime',
        'update_time'         => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'userid'              => 1,
        'usertype'            => 1,
        'certificate_type'    => 1,
        'create_time'         => 1,
        'update_time'         => 1,
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
     * @var integer
     */
    public $userid;

    /**
     * 用户类型 1:个人用户 2:企业用户
     * @var integer
     */
    public $usertype;

    /**
     * 用户真实姓名
     * @var string
     */
    public $realname;

    /**
     * 证件类型
     * @var integer
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
    public $huifuid;

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
