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
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取回款中
	 * @param page begin from 1
	 * @return 标准json
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * { 
	 *     { 
	 *         'proId' 投资项目id
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年利率
	 *         'tenderAmt' 投标金额
	 *         'deadline' 期限
	 *         'tenderTime'投标时间
	 *         'haveBack' 已回款
	 *         'toBeBack' 待回款
	 *     }
	 *  }
	 * 
	 */
	public function indexAction() {
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		//Invest_Api::
	
	}
	
	/**
	 * 接口 /account/invest/tendering
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取投标中
	 * @param page begin from 1
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * { 
	 *     { 
	 *         'proId' 投资项目id
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年利率
	 *         'tenderAmt' 投标金额
	 *         'deadline' 期限
	 *         'tenderTime'投标时间
	 *         'tenderProgress' 进度
	 *     }
	 *  }
	 */
	public function  tenderingAction() {
	    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;		
	}
	
	/**
	 * 接口/account/invest/ended 
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取已结束
	 * @param page begin from 1
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * { 
	 *     { 
	 *         'proId' 投资项目id
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年利率
	 *         'tenderAmt' 投标金额
	 *         'deadline' 期限
	 *         'tenderTime'投标时间
	 *         'endTime' 合同结束时间
	 *         'totalRetAmt' 总回款
	 *         'totalProfit'总收益
	 *     }
	 *  }
	 */
	public function  endedAction() {
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	}
	
	/**
	 * 接口/account/invest/tenderFail
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取投标失败
	 * @param page begin from 1
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * { 
	 *     { 
	 *         'proId' 投资项目id
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年利率
	 *         'tenderAmt' 投标金额
	 *         'deadline' 期限
	 *         'tenderTime'投标时间
     *         'failReason' 失败原因
	 *     }
	 *  }
	 * 
	 */
	public function tenderFailAction() {
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	}
	
	/**
	 * 接口 /account/invest/repayPlan
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
	public function repayPlanAction() {
		
	}
	
	
}