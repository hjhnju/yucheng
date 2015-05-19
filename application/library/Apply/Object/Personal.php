<?php
/**
 * 个人信息记录表
 * @author guojinli
 */
class Apply_Object_Personal extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'apply_personal';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Apply_Object_Personal';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'realname', 'certificate', 'house_type', 'detail_address', 'cellphone', 'telephone', 'scope_cash', 'scope_stock', 'is_criminal', 'is_lawsuit', 'apply_id', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'              => 'id',
        'realname'        => 'realname',
        'certificate'     => 'certificate',
        'house_type'      => 'houseType',
        'detail_address'  => 'detailAddress',
        'cellphone'       => 'cellphone',
        'telephone'       => 'telephone',
        'scope_cash'      => 'scopeCash',
        'scope_stock'     => 'scopeStock',
        'is_criminal'     => 'isCriminal',
        'is_lawsuit'      => 'isLawsuit',
        'apply_id'        => 'applyId',
        'create_time'     => 'createTime',
        'update_time'     => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'house_type'      => 1,
        'cellphone'       => 1,
        'scope_cash'      => 1,
        'scope_stock'     => 1,
        'is_criminal'     => 1,
        'is_lawsuit'      => 1,
        'apply_id'        => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_Personal
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
     * 
     * @var string
     */
    public $realname;

    /**
     * 身份证号
     * @var 
     */
    public $certificate;

    /**
     * 住房类型
     * @var integer
     */
    public $houseType;

    /**
     * 住房详细地址
     * @var string
     */
    public $detailAddress;

    /**
     * 手机号码
     * @var integer
     */
    public $cellphone;

    /**
     * 住宅电话
     * @var string
     */
    public $telephone;

    /**
     * 现金账户余额
     * @var integer
     */
    public $scopeCash;

    /**
     * 股票、债券等其他有价证券资产
     * @var integer
     */
    public $scopeStock;

    /**
     * 是否有犯罪记录
     * @var integer
     */
    public $isCriminal;

    /**
     * 是否有未决诉讼
     * @var integer
     */
    public $isLawsuit;

    /**
     * 
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
