<?php
/**
 * 借款发布(审核通过)
 * @author hejunhua
 *
 */
class PublishAction extends Yaf_Action_Abstract {
    public function execute() {

        $loanId  = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;
        $isOpen  = isset($_REQUEST['isopen']) ? intval($_REQUEST['isopen']) : 0;
        $arrRet = Loan_Api::publish($loanId, $isOpen);
        $bolRet = false;
        if(Base_RetCode::SUCCESS === $arrRet['status']) {
            $bolRet = true;
        }

        Base_Log::notice(array(
            'arrRet' => $arrRet,
            'bolRet' => $bolRet,
        ));

        $this->getView()->assign('success', $bolRet);
        $this->getView()->assign('isopen', $isOpen);

    }
}