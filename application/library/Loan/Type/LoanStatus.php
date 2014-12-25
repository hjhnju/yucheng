<?php
/**
 * 借款状态
 * @author jiangsongfang
 *
 */
class Loan_Type_LoanStatus extends Base_Type {
    /**
     * 1审核中 
     * @var integer
     */
    const AUDIT = 1;
    /**
     * 2投标中 
     * @var integer
     */
    const LENDING = 2;
    /**
     * 3放款审核 
     * @var integer
     */
    const PAY_CHECK = 3;
    /**
     * 4打款中 
     * @var integer
     */
    const PAYING = 4;
    /**
     * 5回款中
     * @var integer
     */
    const REFUNDING = 5;
    /**
     * 6已完成
     * @var integer
     */
    const FINISHED = 6;
    /**
     * 7已撤销
     * @var integer
     */
    const CANCEL = 7;
    /**
     * 9借款失败
     * @var integer
     */
    const FAILED = 9;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'status';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'loan_status';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::AUDIT => '审核中',
        self::LENDING => '投标中',
        self::PAY_CHECK => '放款审核',
        self::PAYING => '打款中',
        self::REFUNDING => '回款中',
        self::FINISHED => '已完成',
        self::CANCEL => '已撤销',
        self::FAILED => '借款失败',
    );
}