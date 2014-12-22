<?php
/**
 * 我的投资页
 * @author lilu
 */
class InvestController extends Base_Controller_Response {
	
	public function init(){
		parent::init();
		$this->ajax = true;
	}
	
	/**
	 * 接口 /account/invest/index
	 * 调用投资模块Invest_Api获取投资项目列表数据
	 * @param status 投资项目状态   1--回款中(默认)  2--投标中 3--已结束  4--投标失败
	 * @param page begin from 1
	 * @return 标准json
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * { 
	 *     { 
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年化利率
	 *         'tenderAmt' 投标金额
	 *         'tenderTime'投标时间
	 *         'haveBack' 已回款
	 *         'toBeBack' 待回款
	 *     }
	 *  }
	 * 
	 */
	public function indexAction() {
		$status = isset($_REQUEST['status'])?$_REQUEST['status']:0;
	
	}
	
	/**
	 * 调用投资模块Invest_Api获取某个项目的还款计划
	 * @param proId 项目ID
	 * @return 标准json
	 * status 0 处理成功
	 * status 1109
	 * data =
	 * { 
	 *     { 
	 *         'invester' 投资人用户名
	 *         'time' 时间
	 *         'repossPrincipal' 待收本金
	 *         'repossProfit' 待收收益
	 *         'recePrincipal' 已收本金
	 *         'receProfit'已收收益
	 *         'paymentStatus' 还款状态 
	 *         'punitive' 罚息
	 *     }
	 *  }
	 */
	public function repayPlan() {
		
	}
	
	
}