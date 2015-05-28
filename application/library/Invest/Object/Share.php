<?php
/**
 * 收益分享表
 * @author huwei
 */
class Invest_Object_Share extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invest_share';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Invest_Object_Share';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'invest_id', 'from_userid', 'to_userid', 'rate', 'income', 'create_time', 'update_time', 'memo');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'loan_id'     => 'loanId',
        'invest_id'   => 'investId',
        'from_userid' => 'fromUserid',
        'to_userid'   => 'toUserid',
        'rate'        => 'rate',
        'income'      => 'income',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
        'memo'        => 'memo',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'invest_id'   => 1,
        'from_userid' => 1,
        'to_userid'   => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Invest_Object_Share
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 收益分享id
     * @var integer
     */
    public $id;

    /**
     * 借款ID 
     * @var integer
     */
    public $loanId;

    /**
     * 投资ID 
     * @var integer
     */
    public $investId;

    /**
     * 分享收益的用户ID 
     * @var integer
     */
    public $fromUserid;

    /**
     * 获得收益的用户ID 
     * @var integer
     */
    public $toUserid;

    /**
     * 分享的利率
     * @var number
     */
    public $rate;

    /**
     * 分享的总收益
     * @var number
     */
    public $income;

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

    /**
     * 说明
     * @var string
     */
    public $memo;

}
