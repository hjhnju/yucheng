<?php
/**
 * 借款发布
 * @author hejunhua
 *
 */
class PublishAction extends Yaf_Action_Abstract {
    public function execute() {

        $loanId = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;

        $arrRet = Loan_Api::publish($loanId);
        $bolRet = isset($arrRet['success']) ? $arrRet['success'] === Base_RetCode::SUCCESS : false;
        Base_Log::notice(array(
        	'arrRet' => $arrRet,
        	'bolRet' => $bolRet,
        ));
        
    	$this->getView()->assign('success', $bolRet);

    }
}