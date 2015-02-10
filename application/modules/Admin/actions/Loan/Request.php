<?php
/**
 * 申请借款
 * @author hejunhua
 *
 */
class RequestAction extends Yaf_Action_Abstract {
    public function execute() {
        // 怎么把controller的参数传入
        $createUid = isset($_REQUEST['cid']) ? intval($_REQUEST['cid']) : 0;

        //参数获取
        $userid    = isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : null;
        if(empty($userid)){
            $errMsg = '未指定贷款申请人';
            $this->getView()->assign('error_msg', $errMsg);
            return;
        }
        //参数获取－借款id
        $loanId  = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : 0;

        //获取申请借款人
        $objUser = User_Api::getUserObject($userid);
        $arrUser = array();
        $arrUser['name'] = $objUser->name;
        $this->getView()->assign('user', $arrUser);

        //申请借款信息
        //TODO:
        if (!empty($_POST)) {
            //var_dump($_POST);
        }
        Base_Log::notice(array(
            'msg'  => '创建借款申请', 
            'post' => $_POST
        ));
        if (!empty($_POST)) {
            //基本借款信息
            $_POST['id'] = $loanId;
            $_POST['status'] = Loan_Type_LoanStatus::AUDIT;
            $_POST['safe_id'] = !empty($_POST['safes']) ? implode(',', $_POST['safes']) : '1';
            $objLoan         = Loan_Object_Loan::init($_POST);
            if (empty($objLoan->startTime)) {
                $objLoan->startTime = time();
                $objLoan->deadline = time() + 7 * 24 * 3600;
            }
            if (!$objLoan->save()) {
                $errMsg = '保存借款基本信息失败';
                $this->getView()->assign('error_msg', $errMsg);
            }
            $loanId = $objLoan->id;

            //借款企业信息
            $company = $_POST['company'];
            $compId  = isset($company['id']) ? intval($company['id']) : 0;
            $objLoanComp = new Loan_Object_Company($compId);
            $objLoanComp->loanId    = $loanId;
            $objLoanComp->userId    = $userid;
            $objLoanComp->school    = $company['school'];
            $objLoanComp->area      = $company['area'];
            $objLoanComp->assets    = $company['assets'];
            $objLoanComp->employers = $company['employers'];
            $objLoanComp->years     = $company['years'];
            $objLoanComp->funds     = $company['funds'];
            $objLoanComp->students  = $company['students'];
            $objLoanComp->save();

            //借款担保信息
            $guarantee = $_POST['guarantee'];
            $guarId  = isset($guarantee['id']) ? intval($guarantee['id']) : 0;
            $objLoanGuar = new Loan_Object_Guarantee($guarId);
            $objLoanGuar->loanId      = $loanId;
            $objLoanGuar->userId      = $userid;
            $objLoanGuar->name        = $guarantee['name'];
            $objLoanGuar->account     = $guarantee['account'];
            $objLoanGuar->age         = $guarantee['age'];
            $objLoanGuar->marriage    = $guarantee['marriage'];
            $objLoanGuar->companyType = $guarantee['company_type'];
            $objLoanGuar->jobTitle    = $guarantee['job_title'];
            $objLoanGuar->income      = $guarantee['income'];
            $objLoanGuar->status      = 0;
            $objLoanGuar->save();

            //借款审核信息
            foreach ($_POST['audit'] as $audit) {
                if (!empty($audit['name'])) {
                    $audiId = isset($audit['id']) ? intval($audit['id']) : 0;
                    $objLoanAudi = new Loan_Object_Audit($audiId);
                    $objLoanAudi->loanId = $loanId;
                    $objLoanAudi->userId = $userid;
                    $objLoanAudi->type   = $audit['type'];
                    $objLoanAudi->name   = $audit['name'];
                    $objLoanAudi->status = $audit['status'];
                    $objLoanAudi->save();
                }
            }

            //借款附件
            foreach ($_POST['attach'] as $attach) {
                $attaId = isset($attach['id']) ? intval($attach['id']) : 0;
                $objLoanAtta = new Loan_Object_Attach($attaId);
                $objLoanAtta->loanId = $loanId;
                $objLoanAtta->userId = $userid;
                $objLoanAtta->type   = $attach['type'];
                $objLoanAtta->title  = $attach['title'];
                $objLoanAtta->url    = $attach['url'];
                $objLoanAtta->status = 0;
                $objLoanAtta->save();
            }

        }
        $this->_view->assign('loanId', $loanId);
    }
}
