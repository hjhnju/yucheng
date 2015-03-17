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
    	    $userCount  = $list['total'];
            $this->getView()->assign('userCount', $userCount);
            
            $loan = new Loan_List_Loan();
            $loan->setFilterString('status = 5');
            $loanAmount = $loan->sumField('amount');
            $loanAmount =  $loanAmount/10000;
            $this->getView()->assign('loanAmount', $loanAmount);
            $clientAmount = $loan->distinCount('user_id');
            $this->getView()->assign('clientAmount', $clientAmount); 

            $invest = new Invest_List_Invest();
            $investAmount = $invest->distinCount('user_id');
            $this->getView()->assign('investAmount', $investAmount);
    	}
}




