<?php
/**
 * 新手标投资记录
 * @author jiangsongfang
 */
class Invest_Object_Fresh extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invest_fresh';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Invest_Object_Fresh';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'user_id', 'loan_id', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'user_id'     => 'userId',
        'loan_id'     => 'loanId',
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
        'user_id'     => 1,
        'loan_id'     => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Invest_Object_Fresh
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * ID
     * @var integer
     */
    public $id;

    /**
     * 用户ID
     * @var integer
     */
    public $userId;

    /**
     * 借款ID
     * @var integer
     */
    public $loanId;

    /**
     * 
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
