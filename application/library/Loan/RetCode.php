<?php
class Loan_RetCode extends Base_RetCode {
    /**
     * 贷款为空
     * @var integer
     */
	const LOAN_EMPTY     = 1200;
	const LOAN_SAVE_FAIL = 1201;
    
    protected static $_arrErrMap = array(
		self::LOAN_EMPTY     => '贷款金额为空',
		self::LOAN_SAVE_FAIL => '贷款信息保存失败',
    );
}