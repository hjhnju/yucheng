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
        $loanId  = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;
        $loanId  = 12; //test
        $userid  = 61; //test

        //获取申请借款人
        $objUser = User_Api::getUserObject($userid);
        $arrUser = array();
        $arrUser['name'] = $objUser->name;
        $this->getView()->assign('user', $arrUser);

        //申请借款信息
        //TODO:
        //若有loanid从数据库先获取信息
        $_POST = array(
            'id' => $loanId,
            //基本借款信息
            'user_id'   => $userid,
            'title'     => '我是新标上线',
            'area'      => 2458,
            'type_id'   => Loan_Type_LoanType::CERTIFICATION, //1
            'cat_id'    => Loan_Type_LoanCat::SCHOOL,
            'content'   => '改扩建，期末教职工工资发放需要资金周转。',
            'fresh'     => 0, //是否新手
            'duration'  => 360,
            'level'     => 2, //评估等级
            'amount'    => 1000000.00,
            'interest'  => 10.2,
            'safe_id'   => implode(',', array(Loan_Type_SafeMode::CAPITAL, 
                Loan_Type_SafeMode::PLEDGE, Loan_Type_SafeMode::SHAREHOLDER)),
            'refund_type' => Loan_Type_RefundType::MONTH_INTEREST,
            'audit_info'  => '经过兴教贷线下实地尽调，该中学运营状况良好，未来收益可覆盖借款额度。批准借款', //不应该在这
            'start_time'  => strtotime('2015-02-01 10:00:00'),
            'deadline'    => strtotime('2015-02-03 00:00:00'),
                //'status'      => Loan_Type_LoanStatus::AUDIT,
            'create_uid'  => $createUid,
            //借款审核信息
            'audit'       => array(
                array('id'=>32, 'status'=>1, 'type'=>Loan_Type_Audit::COMPANY, 'name'=>'实地认证'),
                array('id'=>33, 'status'=>1, 'type'=>Loan_Type_Audit::COMPANY, 'name'=>'营业执照'),
                array('id'=>34, 'status'=>1, 'type'=>Loan_Type_Audit::COMPANY, 'name'=>'税务登记证'),
                array('id'=>35, 'status'=>1, 'type'=>Loan_Type_Audit::COMPANY, 'name'=>'银行开户许可证'),
                array('id'=>36, 'status'=>1, 'type'=>Loan_Type_Audit::COMPANY, 'name'=>'组织机构代码证'),
                array('id'=>37, 'status'=>1, 'type'=>Loan_Type_Audit::COMPANY, 'name'=>'征信报告'),
                array('id'=>38, 'status'=>1, 'type'=>Loan_Type_Audit::COMPANY, 'name'=>'抵押手续'),
                array('id'=>39, 'status'=>1, 'type'=>Loan_Type_Audit::GUARANTEE, 'name'=>'身份证'),
                array('id'=>40, 'status'=>1, 'type'=>Loan_Type_Audit::GUARANTEE, 'name'=>'户口本'),
                array('id'=>41, 'status'=>1, 'type'=>Loan_Type_Audit::GUARANTEE, 'name'=>'结婚证'),
                array('id'=>42, 'status'=>1, 'type'=>Loan_Type_Audit::GUARANTEE, 'name'=>'房产证'),
                array('id'=>43, 'status'=>1, 'type'=>Loan_Type_Audit::GUARANTEE, 'name'=>'行驶证'),
            ),
            //借款企业信息
            'company'     => array(
                'id'        => 4,
                'school'    => '福建101中学',
                'area'      => '福建东城区',
                'assets'    => '5000万',
                'employers' => 234,
                'years'     => 2010,
                'funds'     => '100万',
                'students'  => 2018,
            ),
            //借款担保人信息
            'guarantee' => array(
                'id'          => 5,
                'name'        => '李若彤',
                'account'     => '浙江金华',
                'age'         => 30,
                'marriage'    => 1,
                'companyType' => Loan_Type_SchoolType::getTypeName(Loan_Type_SchoolType::BASE),
                'jobTitle'    => '校长',
                'income'      => '100-200万',
                'status'      => 1,
            ),
            'attach' => array(
                array(
                    'id'     => 20,
                    'type'   => Loan_Type_Attach::CERTIFICATION,
                    'title'  => '身份证',
                    'url'    => 'http://sn.people.com.cn/NMediaFile/2012/0919/LOCAL201209191518214553001689727.jpg',
                    'status' => 0,
                ),
                array(
                    'id'     => 21,
                    'type'   => Loan_Type_Attach::CERTIFICATION,
                    'title'  => '身份证',
                    'url'    => 'http://img4.cache.netease.com/photo/0031/2013-05-29/902N04R659P50031.jpg',
                    'status' => 0,
                ),
                array(
                    'id'     => 22,
                    'type'   => Loan_Type_Attach::CONTRACT,
                    'title'  => '借款合同',
                    'url'    => 'http://new.gdcp.cn/images/20120827/201208271513521616.jpg',
                    'status' => 0,
                ),
                array(
                    'id'     => 23,
                    'type'   => Loan_Type_Attach::ENTITY,
                    'title'  => '学校大门',
                    'url'    => 'http://house.china.com.cn/taiyuan/UserFiles/20110118/09002660.jpg',
                    'status' => 0,
                ),
                array(
                    'id'     => 24,
                    'type'   => Loan_Type_Attach::ENTITY,
                    'title'  => '教学楼',
                    'url'    => 'http://www.zjc.com.cn/admin1/edit/UploadFile/2008922134658235.jpg',
                    'status' => 0,
                ),
            ),
        );
        Base_Log::notice(array(
            'msg'  => '创建借款申请', 
            'post' => $_POST
        ));
        if (!empty($_POST)) {
            //基本借款信息
            $_POST['status'] = Loan_Type_LoanStatus::AUDIT;
            $objLoan = Loan_Object_Loan::init($_POST);
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
            $objLoanGuar->companyType = $guarantee['companyType'];
            $objLoanGuar->jobTitle    = $guarantee['jobTitle'];
            $objLoanGuar->income      = $guarantee['income'];
            $objLoanGuar->status      = $guarantee['status'];
            $objLoanGuar->save();

            //借款审核信息
            foreach ($_POST['audit'] as $audit) {
                $audiId = isset($audit['id']) ? intval($audit['id']) : 0;
                $objLoanAudi = new Loan_Object_Audit($audiId);
                $objLoanAudi->loanId = $loanId;
                $objLoanAudi->userId = $userid;
                $objLoanAudi->type   = $audit['type'];
                $objLoanAudi->name   = $audit['name'];
                $objLoanAudi->status = $audit['status'];
                $objLoanAudi->save();
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
                $objLoanAtta->status = $attach['status'];
                $objLoanAtta->save();
            }

        }
    }
}
