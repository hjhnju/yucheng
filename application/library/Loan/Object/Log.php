<?php
/**
 * 借款操作记录
 * @author jiangsongfang
 */
class Loan_Object_Log extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_log';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Loan_Object_Log';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'content', 'create_time', 'ip');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'loan_id'     => 'loanId',
        'user_id'     => 'userId',
        'content'     => 'content',
        'create_time' => 'createTime',
        'ip'          => 'ip',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'user_id'     => 1,
        'create_time' => 1,
    );

    /**
     * @param array $data
     * @return Loan_Object_Log
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
     * 操作人
     * @var integer
     */
    public $userId;

    /**
     * 操作内容
     * @var string
     */
    public $content;

    /**
     * 创建时间
     * @var integer
     */
    public $createTime;

    /**
     * 操作IP
     * @var string
     */
    public $ip;

}
