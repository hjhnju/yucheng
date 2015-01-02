<?php 
/**
 * 汇付回调url入口类
 * 页面打印以下两种字符串
 * RECV_ORD_ID_TrxId
 * RECV_ORD_ID_OrderId
 * @author lilu
 */
class BgcallController extends Base_Controller_Page{
	
	public function init(){
		Yaf_Dispatcher::getInstance()->disableView();
		$this->setNeedLogin(false);
		parent::init();
	}
	
	/**
	 * 汇付天下回调Action
	 * 用户开户回调URL
	 * 打印RECV_ORD_ID_TrxId
	 */
	public function userregistAction() {
		$trxId = $_REQUEST['TrxId'];
		//TODO:确认下在用户没有登录的情况下有没有可能接触到开户界面！应该不可能
		//$userName = $_REQUEST['UsrId'];
		$usrCustId = $_REQUEST['UsrCustId'];
		print('RECV_ORD_ID_'.$trxId);		
		Base_Log::notice('RECV_ORD_ID_'.$trxId);
		Base_Log::notice($usrCustId);	
	}
	
	/**
	 * 汇付天下回调Action
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function userbindcardAction() {
		$trxId = $_REQUEST['TrxId'];
		print('RECV_ORD_ID_'.$trxId);
		Base_Log::notice('RECV_ORD_ID_'.$trxId);
	}
}
