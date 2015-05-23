<?php
/**
 * @file 融资申请个人信息表 
 * @author guojinli
 */
class Apply_Object_Person extends Base_Object {
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
     * 字段列表
     * @var array
     */
    protected $fields = array('id', 'realname', 'certificate', 'house_type', 'detail_address', 'cellphone', 'telephone', 'cash', 'stock', 'is_criminal', 'is_lawsuit', 'apply_id', 'create_time', 'update_time');
    
    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'             => 'id',
        'realname'       => 'realname',
        'certificate'    => 'certificate',
        'house_type'     => 'houseType',
        'detail_address' => 'detailAddress',
        'cellphone'      => 'cellphone',
        'telephone'      => 'telephone',
        'cash'           => 'cash',
        'stock'          => 'stock',
        'criminal'       => 'isCriminal',
        'lawsuit'        => 'isLawsuit',
        'apply_id'       => 'applyId',
        'create_time'    => 'createTime',
        'update_time'    => 'updateTime',
    );
     
    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'         => 1,
        'house_type' => 1,
        'cellphone'  => 1,
        'stock'      => 1,
        'criminal'   => 1,
        'lawsuit'    => 1,
        'apply_id'   => 1,
        'create_time'=> 1,
        'update_time'=> 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_Person
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
     * 真实姓名
     * @var [string]
     */
    public $realname;

    /**
     * 身份证号
     * @var [string]
     */
    public $certificate;

    /**
     * 住房类型
     * @var [int]
     */
    public $houseType;

    /**
     * 住房详细地址
     * @var [string]
     */
    public $detailAddress;

    /**
     * 手机
     * @var [string]
     */
    public $cellphone;

    /**
     * 座机
     * @var [string]
     */
    public $telephone;

    /**
     * 现金余额
     * @var [int]
     */
    public $cash;

    /**
     * 其他资产，股票，债券等
     * @var [int]
     */
    public $stock;

    /**
     * 是否有犯罪记录
     * @var [int]
     */
    public $isCriminal;

    /**
     * 是否有未决诉讼
     * @var [int]
     */
    public $isLawsuit;

    /**
     * 申请的id
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