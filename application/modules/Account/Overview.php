<?php
class OverviewController extends Base_Controller_Response {

    public function init(){
        parent::init();
        $this->ajax = true;
    }


	/**
	 * /account/overview/index
	 * 
	 * 1.调用Finance_Api获取账户余额等信息
	 * 2.调用Invest_Api获取收益信息
	 */
	public function indexAction(){

	}
}
