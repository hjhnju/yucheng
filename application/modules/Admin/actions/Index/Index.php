<?php
/**
 * 后台首页
 * @author jiangsongfang
 *
 */
class IndexAction extends Yaf_Action_Abstract {
	/**
	 * TODO：Admin首页展示数据获取
	 * @author yibing
	 *
	 */
	public function execute() {
    		
    	$list = new User_List_Login();
    	$list = $list->toArray();
    	//注册用户数统计
    	$userCnt  = $list['total'];
        $this->getView()->assign('userCnt', $userCnt);
            
        $loan = new Loan_List_Loan();
        $status1 = Loan_Type_LoanStatus::FINISHED;
        $status2 = Loan_Type_LoanStatus::REFUNDING;
        //构造where条件
        $loan->setFilterString("status in($status1,$status2)");
        //查询总借款额度
        $loanAmount = $loan->sumField('amount');
        //单位：万元
        $loanAmount =  $loanAmount/10000;
        $this->getView()->assign('loanAmount', $loanAmount);
        //查询借款客户总数
        $cusCnt = $loan->distinCount('user_id');
        $this->getView()->assign('cusCnt', $cusCnt); 

        $invest = new Invest_List_Invest();
        //查询投资者总数
        $investCnt = $invest->distinCount('user_id');
        $this->getView()->assign('investCnt', $investCnt);
    	}
}




