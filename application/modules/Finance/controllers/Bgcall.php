<?php 
/**
 * 汇付回调url入口类
 * 页面打印以下两种字符串
 * RECV_ORD_ID_TrxId
 * RECV_ORD_ID_OrderId
 * @author lilu
 */
class BgcallController extends Base_Controller_Page{
	
	const VERSION_10 = "10";
	
	public function init(){
		$this->huifuLogic = Finance_Chinapnr_Logic::getInstance();//需要对返回值进行验签工作
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
		
		/* 
		
		$checkValue = $this->huifuLogic->sign(self::VERSION_10.$_REQUEST['CmdId'].$_REQUEST['MerCustId']
		.$_REQUEST['BgRetUrl'].$_REQUEST['RetUrl'].$_REQUEST['UsrId'].$_REQUEST['UsrName']
		.$_REQUEST['IdType'].$_REQUEST['IdNo'].$_REQUEST['UsrMp'].$_REQUEST['UsrEmail'].$_REQUEST['MerPriv']);
		
		$veriret = $this->huifuLogic->verify($checkValue,$_REQUEST['ChkValue']);
		if($veriret) {
			//验签通过
			$usrCustId = $_REQUEST['UsrCustId'];
			$userid = intval($_REQUEST['merPriv']);
			if(User_Api::setHuifuId($userid,$usrCustId)) {
				
			} else {
				
			}
		} else {
			//验签不通过
			Base_Log::error("返回验签失败",$checkValue);
		} */
		
		
		print('RECV_ORD_ID_'.$trxId);		
		Base_Log::notice('RECV_ORD_ID_'.$trxId);
		Base_Log::notice($usrCustId);	
	}
	
	/**
	 * 汇付天下回调Action
	 * 用户绑卡回调URL
	 * 打印RECV_ORD_ID_TrxId
	 */
	public function userbindcardAction() {
		$trxId = $_REQUEST['TrxId'];
		print('RECV_ORD_ID_'.$trxId);
		Base_Log::notice('RECV_ORD_ID_'.$trxId);
	}
	
	/**
	 * 汇付天下回调Action
	 * 网银充值回调URL
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function netsaveAction() {
		$ordId = $_REQUEST['ordId'];
		print('RECV_ORD_ID_'.$ordId);
		Base_Log::notice('RECV_ORD_ID_'.$ordId);
	}
		
}
