<?php
/**
 * 借款审核信息
 * @author jiangsongfang
 */
class Loan_Object_Audit extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_audit';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Loan_Object_Audit';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'type', 'name', 'comment', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'loan_id'     => 'loanId',
        'user_id'     => 'userId',
        'type'        => 'type',
        'name'        => 'name',
        'comment'     => 'comment',
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
        'loan_id'     => 1,
        'user_id'     => 1,
        'type'        => 1,
        'status'      => 1,
    );

    /**
     * @param array $data
     * @return Loan_Object_Audit
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
    public $loanId;

    /**
     * 创建人
     * @var integer
     */
    public $userId;

    /**
     * 认证类型 1企业 2担保
     * @var integer
     */
    public $type;

    /**
     * 认证项 英文
     * @var string
     */
    public $name;

    /**
     * 备注
     * @var string
     */
    public $comment;

    /**
     * 状态 1通过 2未通过
     * @var integer
     */
    public $status;

    /**
     * 创建时间
     * @var string
     */
    public $createTime;

    /**
     * 更新时间
     * @var string
     */
    public $updateTime;

}
