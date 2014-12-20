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
	 * 调用投资模块Invest_Api获取投资项目列表数据
	 * @param status 投资项目状态
	 */
	public function indexAction() {
		
	}
	
	/**
	 * 接口 /account/invest/list
	 * 调用投资模块Invest_Api获取投资项目列表数据
	 * @param status 投资项目状态  0--获取全部  1--汇款中  2--投标中 3--已结束  4--投标失败
	 */
	public function listAction() {
		$status = isset($_REQUEST['status'])?$_REQUEST['status']:0;
	
	}
	
	/**
	 * 调用投资模块Invest_Api获取某个项目的还款计划
	 */
	public function repayPlan() {
		
	}
	
	
}