<?php
/**
 * 放款审核接口
 * @author hejunhua
 *
 */
class AuditAction extends Yaf_Action_Abstract {
    public function execute() {

        $loanId = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;

        $bolRet = Loan_Api::fullPassAudit($loanId);
        Base_Log::notice(array(
            'loanId' => $loanId,
            'bolRet' => $bolRet,
        ));
        
        $this->getView()->assign('success', $bolRet);

    }
}