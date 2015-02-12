<?php
/**
 * 放款接口
 * @author hejunhua
 *
 */
class LoanAction extends Yaf_Action_Abstract {
    public function execute() {

        $loanId = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;

        $arrRet = Loan_Api::makeLoans($loanId);
        $arrRet['status'] = Base_RetCode::SUCCESS;
        
        $bolRet = false;
        if($arrRet['status'] === Base_RetCode::SUCCESS){
            $bolRet = Loan_Api::lendSuccess($loanId);
            if(!$bolRet){
                Base_Log::error(array(
                    'msg' => '生成还款计划未成功',
                ));
            }
        }
        Base_Log::notice(array(
            'arrRet' => $arrRet,
            'bolRet' => $bolRet,
        ));
        
        $this->getView()->assign('success', $bolRet);

    }
}