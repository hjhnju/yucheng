<?php
class Loan_RetCode extends Base_RetCode {
    /**
     * 贷款为空
     * @var integer
     */
    const LOAN_EMPTY       = 1200;
    const LOAN_SAVE_FAIL   = 1201;
    const MAKE_LOAN_FAIL   = 1202;
    const UNABLE_MAKE_LOAN = 1203;
    const UNABLE_REFUND    = 1204;
    const REFUND_FAILED    = 1205;
    
    protected static $_arrErrMap = array(
        self::LOAN_EMPTY       => '贷款金额为空',
        self::LOAN_SAVE_FAIL   => '贷款信息保存失败',
        self::MAKE_LOAN_FAIL   => '发放贷款失败',
        self::UNABLE_MAKE_LOAN => '该笔投资不可打款',
        self::UNABLE_REFUND    => '该笔投资不可还款',
        self::REFUND_FAILED    => '还款失败',
    );
}