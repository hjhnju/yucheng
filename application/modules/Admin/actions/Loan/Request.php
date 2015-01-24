<?php
/**
 * 申请借款
 * @author hejunhua
 *
 */
class RequestAction extends Yaf_Action_Abstract {
    public function execute() {
        //参数获取
        $userid    = isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : null;
        if(empty($userid)){
        	$errMsg = '未指定贷款申请人';
        	$this->getView()->assign('error_msg', $errMsg);
        }
        $createUid = isset($_REQUEST['cid']) ? intval($_REQUEST['cid']) : 0;

        //获取申请借款人
        $objUser = User_Api::getUserObject($userid);
        $arrUser = array();
        $arrUser['name'] = $objUser->name;
        $this->getView()->assign('user', $arrUser);

        //申请借款
        $_POST = array(
            //借款企业信息
            'user_id'   => $userid,
            'title'     => '北京中学资金流转',
            'area'      => 1,
            'type_id'   => Loan_Type_LoanType::CERTIFICATION, //1
            'cat_id'    => Loan_Type_LoanCat::SCHOOL,
            'content'   => '期末教职工工资发放需要资金周转。',
            'fresh'     => 0, //是否新手
            'duration'  => 1,
            'level'     => 2, //评估等级
            'amount'    => 100000.00,
            'interest'  => 10.5,
            'safe_id'   => implode(',', array(Loan_Type_SafeMode::CAPITAL, 
                Loan_Type_SafeMode::PLEDGE, Loan_Type_SafeMode::SHAREHOLDER)),
            'refund_type' => Loan_Type_RefundType::MONTH_INTEREST,
            'audit_info'  => '经过兴教贷线下实地尽调，该中学运营状况良好，未来收益可覆盖借款额度。批准借款', //不应该在这
            'start_time'  => strtotime('2015-02-01 10:00:00'),
            'deadline'    => strtotime('2015-02-08 00:00:00'),
            'status'      => Loan_Type_LoanStatus::LENDING,
            'create_uid'  => $createUid,
        );
        Base_Log::notice($_POST);
        if (!empty($_POST)) {
            //基本借款信息
            $objLoan = Loan_Object_Loan::init($_POST);
            if (!$objLoan->save()) {
                $errMsg = '保存借款基本信息失败';
                $this->getView()->assign('error_msg', $errMsg);
            }
            // //借款企业信息
            // $objLoanComp = new Loan_Object_Company($objLoan->id);
            // //借款担保信息
            // $objLoanGuar = new Loan_Object_Guarantee($objLoan->id);
            // //借款审核信息
            // $objLoanAudi = new Loan_Object_Audit($objLoan->id);
            // //借款附件
            // $objLoanAtta = Loan_Object_Attatch::init($attaInfo);

        }
    }
}