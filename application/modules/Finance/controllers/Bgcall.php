<?php 
/**
 * 汇付回调url入口类
 * 页面打印以下两种字符串
 * RECV_ORD_ID_TrxId
 * RECV_ORD_ID_OrderId
 * @author lilu
 */
class BgcallController extends Base_Controller_Page{
	
	private $huifuLogic;
	private $financeLogic;
	const VERSION_10 = "10";
	
	public function init(){
		$this->huifuLogic = Finance_Chinapnr_Logic::getInstance();//需要对返回值进行验签工作
		$this->financeLogic = new Finance_Logic_Base();
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
		$userid = intval($_REQUEST['MerPriv']);//取客户私用域中的userid
		$usrCustId = intval($_REQUEST['UsrCustId']);
		$respCode = intval($_REQUEST['RespCode']) ;
		$respDesc = $_REQUEST['RespDesc'] ;	
		if($respCode != '000') {
			Base_Log::error('汇付处理失败',array(
				'respCode' => $respCode,
				'respDesc' => $respDesc,
			));
			return ;
		}			
		//验签
		$checkValue = $this->huifuLogic->sign(self::VERSION_10.$_REQUEST['CmdId'].$_REQUEST['MerCustId']
		.$_REQUEST['BgRetUrl'].$_REQUEST['RetUrl'].$_REQUEST['UsrId'].$_REQUEST['UsrName']
		.$_REQUEST['IdType'].$_REQUEST['IdNo'].$_REQUEST['UsrMp'].$_REQUEST['UsrEmail'].$_REQUEST['MerPriv']);
		
		$veriret = $this->huifuLogic->verify($checkValue,$_REQUEST['ChkValue']);
		if($veriret) {
			//验签通过
			if(User_Api::setHuifuId($userid,$usrCustId)) {
				
			} else {
				$strMonitor = 'userid:'.$userid.' '.'$usrCustId'.$usrCustId;
				Base_Log::error("设置用户汇付id失败",array(
				    'userid:'    => $userid,
				    '$usrCustId' => $usrCustId,
				));
				return ;				
			}
		} else {
			//验签不通过
			Base_Log::error("返回验签失败",$checkValue);
			return ;
		} 					
		Base_Log::notice(array(
			'userid'       =>$userid,
		    'usrCustId'    =>$usrCustId,
		    'huifuRetCode' =>$respCode,
		    'huifuRetDes'  =>$respDesc,
		    'trxId'        =>$trxId,
		));
		//页面打印值
		print('RECV_ORD_ID_'.$trxId);
	}
	
	/**
	 * 汇付天下回调Action
	 * 用户绑卡回调URL
	 * 打印RECV_ORD_ID_TrxId
	 */
	public function userbindcardAction() {
		//Base_Log::notice('hhhhhhh');
		$trxId = $_REQUEST['TrxId'];
		$userid = intval($_REQUEST['MerPriv']);//取客户私用域中的userid
		$usrCustId = intval($_REQUEST['UsrCustId']);
		$respCode = intval($_REQUEST['RespCode']) ;
		$respDesc = $_REQUEST['RespDesc'] ;
		if($respCode != '000') {
			Base_Log::error('汇付处理失败',array(
			    'respCode' => $respCode,
			    'respDesc' => $respDesc,
			));
			return ;
		}
		//验签！！
		
		
		Base_Log::notice(array(
		    'userid'       =>$userid,
		    'usrCustId'    =>$usrCustId,
		    'huifuRetCode' =>$respCode,
		    'huifuRetDes'  =>$respDesc,
		    'trxId'        =>$trxId,
		));
		print('RECV_ORD_ID_'.$trxId);
	}
	
	/**
	 * 汇付天下回调Action
	 * 网银充值回调URL
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function netsaveAction() {
		$ordId    = intval($_REQUEST['OrdId']);
        $userid   = intval($_REQUEST['MerPriv']);//取客户私用域中的userid
        $huifuid  = intval($_REQUEST['UsrCustId']);//用户的huifuid
        $amount   = floatval($_REQUEST['TransAmt']);
        $bgret    = Finance_Api::queryBalanceBg($huifuid);
        $lastip   = Base_Util_Ip::getClientIp();
        $respCode = intval($_REQUEST['RespCode']) ;
        $respDesc = $_REQUEST['RespDesc'] ;
        $timeNow  = time();
        if($bgret['status'] != 0) {
        	$balance = 0.00;
        	$tatal   = 0.00;
        } else {
        	$balance = $bgret['data']['avlBal'];
        	$tatal   = $balance['data']['acctBal'];
        }
        if($respCode != '000') {
        	Base_Log::error('汇付处理失败',array(
        	    'respCode' => $respCode,
        	    'respDesc' => $respDesc,
        	));        	
        	//充值财务订单状态更新为处理失败         	
        	$param = array(
        	    'order_id'     => $ordId,
        		'status'       => Finance_TypeStatus::ENDWITHFAIL,
        		'update_time'  => $timeNow,
        		'comment'      => '充值订单处理失败',
        	);
        	$ret = $this->financeLogic->payOrderEnterDB($param);
        	//充值订单更新失败
        	if($ret == false) {
        		BaseLog::error("fail to update finance order", $param);
        	} 
        	return ;
        }
        //验签！！
        //充值财务订单状态更新为处理成功
        $param = array(
        		'order_id'     => $ordId,
        		'status'       => Finance_TypeStatus::ENDWITHSUCCESS,
        		'update_time'  => $timeNow,
        		'comment'      => '充值订单处理成功',
        );
        
        //充值财务记录入库
        $param = array(
            'orderid'    => $ordId,
        	'userId'     => $userid,
        	'type'       => Finance_TypeStatus::NETSAVE,
        	'amount'     => $amount,
        	'balance'    => $balance,
        	'total'      => $tatal,
        	'comment'    => '充值记录入库',
        	'createTime' => $timeNow,
        	'updateTime' => $timeNow,
        	'ip'         => $lastip,
        	
        );
        $ret = $this->financeLogic->payRecordEnterDB($param);
        //充值记录入库失败
        if($ret == false) {
        	BaseLog::error("fail to create finance record", $param);
        }
		Base::notice(array(
		    'userid'       =>$userid,
		    'usrCustId'    =>$usrCustId,
		    'huifuRetCode' =>$respCode,
		    'huifuRetDes'  =>$respDesc,
		    'trxId'        =>$ordId,
		));
		print('RECV_ORD_ID_'.$ordId);
	}
		
}
