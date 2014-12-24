<?php
/**
 * 账户总览页
 */
class OverviewController extends Base_Controller_Response {

    public function init(){
    	$this->setAutoJump(false);//for test TODO remove
        parent::init();
        $this->ajax = true;
    }


	/**
	 * /account/overview/index
	 * 
	 * 1.调用Finance_Api获取账户余额等信息
	 * 2.调用Invest_Api获取收益信息     assign 数值至前端
	 * 
	 * 'avlBal' 可用余额
	 * 'acctBal' 资产总计
	 * 'frzBal' 冻结金额
	 * 'totalProfit' 累计收益
	 * 'totalInvest' 累计投资
	 * 'reposPrifit' 待收收益
	 * '$reposPrincipal' 待收本金
	 * 
	 */
	public function indexAction(){
		//$userId = $this->getUserId();
		//$objUser = User_Api::getUserObject($userId); 
		$objUser = json_decode(json_encode(array('name'=>'lilu', 'phone'=>'18611015043','realname'=>'jiangbianliming',)));//for test
		$data = array('name' => $objUser->name, 'phone' => $objUser->phone,);
		$balance = Finance_Api::queryBalanceBg($huifuid);
		$avlBal = isset($balance['avlBal']) ? $balance['avlBal'] : 0.00;//可用余额
		$acctBal = isset($balance['acctBal']) ? $balance['acctBal'] : 0.00;//资产总计
		$frzBal = isset($balance['frzBal']) ? $balance['frzBal'] : 0.00;//冻结金额
		//$totalProfit = Invest_Api:: 累计收益
		//$totalInvest = Invest_Api:: 累计投资
		//$reposPrifit = Invest_Api:: 待收收益
		//$reposPrincipal = Invest_Api:: 待收本金
		$this->getView()->assign("avlBal",$avlBal);
		$this->getView()->assign("acctBal",$acctBal);
		$this->getView()->assign("frzBal",$frzBal);
		$this->getView()->assign("totalProfit",$totalProfit);
		$this->getView()->assign("totalInvest",$totalInvest);
		$this->getView()->assign("reposPrifit",$reposPrifit);
		$this->getView()->assign("reposPrincipal",$reposPrincipal);				
	}
	
	/**
	 * 近半年收益曲线
	 * @return 标准json
	 * status 0 处理成功
	 * status 
	 * data: {
     *  x: ['2014-02', '2014-03'],
     *  y: [100, 1000]
     *  }
	 */
	public function profitCurveAction() {
		//$userId = $this->getUserId();
		//$objUser = User_Api::getUserObject($userId);
		$objUser = json_decode(json_encode(array('name'=>'lilu', 'phone'=>'15901538467','realname'=>'jiangbianliming',)));//for test
		$data = array('name' => $objUser->name, 'phone' => $objUser->phone,);
		$huifuid = $objUser->huifuid;
		if(!isset($huifuid)) {//TODO !isset
		 	$this->ajax = false;
			$this->outputView = 'test/noThirdPay.phtml';
			$this->output($data);
		} else {
			//for test
			//TODO:真正数据要从投资模块而来
			$ret = array(
				x => array('2014-05','2014-06','2014-07','2014-08','2014-09','2014-10'),
				y => array(10,20,500,60,49,1000),
			);			
			if($ret==false) {
				$this->outputError(Account_RetCode::GET_PROFIT_CURVE_FAIL,Account_RetCode::getMsg(Account_RetCode::GET_PROFIT_CURVE_FAIL));
			} else {
				$this->output($ret);
			}
					
		}
		
	}
}