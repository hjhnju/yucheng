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
	 * 调用投资模块Invest_Api获取投资列表数据
	 */
	public function indexAction() {
		
	}
	
	
}