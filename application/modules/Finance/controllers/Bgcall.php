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
		if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) ||
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrId']) || !isset($_REQUEST['UsrCustId']) ||
           !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
            Base_Log(array(
                'msg' => '汇付返回参数错误',           
            ));
        }
		$trxId    = $_REQUEST['TrxId'];
		$userid   = $_REQUEST['MerPriv'];//取客户私用域中的userid
		$huifuid  = $_REQUEST['UsrCustId'];//用户汇付id入库
		$realName = $_REQUEST['UsrName'];//用户真实姓名入库
		$phone    = $_REQUEST['UsrMp'];//用户手机号码入库
		$email    = $_REQUEST['UsrEmail'];//用户email入库
		$idType   = $_REQUEST['IdType'];//证件类型入库
		$idNo     = $_REQUEST['IdNo'];//用户身份证号码入库
		$respCode = $_REQUEST['RespCode']; 
		$respDesc = $_REQUEST['RespDesc'];	
		if($respCode !== '000') {
			Base_Log::error(array(
			    'msg'      => $respDesc,
				'respCode' => $respCode,
			));
			return ;
		}			
		//验签
		$checkValue = $this->huifuLogic->sign(self::VERSION_10.$_REQUEST['CmdId'].$_REQUEST['MerCustId']
		.$_REQUEST['BgRetUrl'].$_REQUEST['RetUrl'].$_REQUEST['UsrId'].$_REQUEST['UsrName']
		.$_REQUEST['IdType'].$_REQUEST['IdNo'].$_REQUEST['UsrMp'].$_REQUEST['UsrEmail'].$_REQUEST['MerPriv']);
		
		$bolVeri = $this->huifuLogic->verify($checkValue,$_REQUEST['ChkValue']);
		if(!$bolVeri) {
			Base_Log::error(array(
				'msg' => '返回验签失败',
			));
			return ;
		}
		$userid = intval($userid);
		if(!User_Api::setHuifuId($userid,$huifuid)) {		
		    Base_Log::error( array(
			    'msg'       => '汇付id入库失败',
			    'userid:'   => $userid,
			    'usrCustId' => $huifuid,
			));
			return ;
		}
		if(!User_Api::setRealName($userid,$realName)) {
			Base_Log::error(array(
				'msg'    => '用户真实姓名入库失败',
				'userid' => $userid,
			));
			return ;
		}
		if(!User_Api::setEmail($userid,$email)) {
			Base_Log::error(array(
				'msg'    => '用户email入库失败',
				'userid' => $userid,
			));
			return ;
		}
		//证件信息入库，默认为身份证
		if(!User_Api::setCertificate($userid,$idNo)) {
			Base_Log::error(array(
				'msg'    => '用户证件信息入库失败',
				'userid' => $userid,
			));
			return ;
		}
		Base_Log::notice($_REQUEST);
		//页面打印值,汇付检验
		print('RECV_ORD_ID_'.$trxId);
	}
	
	/**
	 * 汇付天下回调Action
	 * 用户绑卡回调URL
	 * 打印RECV_ORD_ID_TrxId
	 */
	public function userbindcardAction() {
		if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrCustId']) ||
		   !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) || !isset($_REQUEST['Version'])) {
		    Base_Log(array(
		    	'msg' => '汇付返回参数错误',
		    ));   	
		}
		$trxId     = $_REQUEST['TrxId'];
		$userid    = $_REQUEST['MerPriv'];//取客户私用域中的userid
		$usrCustId = $_REQUEST['UsrCustId'];
		$respCode  = $_REQUEST['RespCode'];
		$respDesc  = $_REQUEST['RespDesc'];
		if($respCode !== '000') {
			Base_Log::error(array(
			    'msg'      => $respDesc,
			    'respCode' => $respCode,
			));
			return ;
		}
		//验签！！
		
		
		Base_Log::notice($_REQUEST);
		print('RECV_ORD_ID_'.$trxId);
	}
	
	/**
	 * 汇付天下回调Action
	 * 网银充值回调URL
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function netsaveAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrCustId']) || !isset($_REQUEST['OrdId']) || 
		   !isset($_REQUEST['OrdDate']) || !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['BgRetUrl']) ||
		   !isset($_REQUEST['ChkValue']) || !isset($_REQUEST['FeeAmt']) || !isset($_REQUEST['FeeCustId']) ||
		   !isset($_REQUEST['FeeAcctId'])) {
		    Base_Log(array(
		    	'msg' => '汇付返回参数错误'
		    ));	    
		    return;	
	    }
	    $orderId  = strval($_REQUEST['OrdId']);
	    $userid   = intval($_REQUEST['MerPriv']);//取客户私用域中的userid
	    $huifuid  = strval($_REQUEST['UsrCustId']); //用户的huifuid
	    $amount   = floatval($_REQUEST['TransAmt']);
	   
	    //余额是系统余额！改！
	    $bgret    = $this->financeLogic->balance($userid);
	    $balance  = floatval($bgret['userBg']['acctBal']);//用户余额
	    $total    = floatval($bgret['sysBg']['acctBal']);//系统余额
	    
	    $lastip   = Base_Util_Ip::getClientIp();
	    $respCode = $_REQUEST['RespCode'];
	    $respDesc = $_REQUEST['RespDesc'] ;

        if($respCode !== '000') {
        	Base_Log::error(array(
        	    'msg'      => $respDesc,
        	    'respCode' => $respCode,
        	));        	
        	//充值财务订单状态更新为处理失败         	
        	$param = array(
        	    'orderId' => $orderId,
        		'status'  => Finance_TypeStatus::ENDWITHFAIL,
        		'comment' => '财务类充值订单汇付处理失败',
        	);
        	$ret = $this->financeLogic->payOrderEnterDB($param);
        	//充值订单更新失败
        	if(!$ret) {
        		BaseLog::error(array(
        			'msg'     => '充值订单更新失败',
        			'orderId' => $orderId,
        			'status'  => Finance_TypeStatus::ENDWITHFAIL,
        		));
        	} 
        	return ;
        }
        //验签！！
        //充值财务订单状态更新为处理成功
        $param = array(
        	'orderId' => $orderId,
        	'status'  => Finance_TypeStatus::ENDWITHSUCCESS,
        	'comment' => '充值订单处理成功',
        );
        $ret = $this->financeLogic->payOrderEnterDB($param);
        //充值订单更新失败
        if(!$ret) {
        	BaseLog::error(array(
        	    'msg'     => '充值订单更新失败',
        	    'orderId' => $orderId,
        	    'status'  => Finance_TypeStatus::ENDWITHSUCCESS,
        	));
        }
        //充值财务记录入库
        $param = array(
            'orderId'    => intval($orderId),
        	'userId'     => intval($userid),
        	'type'       => Finance_TypeStatus::NETSAVE,
        	'amount'     => $amount,
        	'balance'    => $balance,
        	'total'      => $total,
        	'comment'    => '充值记录',
        	'ip'         => $lastip,        	
        );
        $ret = $this->financeLogic->payRecordEnterDB($param);
        //充值记录入库失败
        if(!$ret) {
        	BaseLog::error(array(
        		'msg'     => '充值记录入库失败',
        		'orderid' => $orderId,
        		'userid'  => $userid,
        	));
        }
		Base::notice($_REQUEST);
		//页面打印
		print('RECV_ORD_ID_'.$orderId);
	}
	
	/**
	 * 汇付回调Action
	 * 主动投标回调URL
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function initiativeTenderAction() {
		if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['OrdDate']) || 
           !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['UsrCustId']) || !isset($_REQUEST['IsFreeze']) || 
           !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue'])) {
           	Base_Log::error(array(
           		'msg' => '汇付返回参数错误',
           	));
        }
		$userid      = intval($_REQUEST['MerPriv']);
		$huifuid     = intval($_REQUEST['UsrCustId']);
		$orderId     = strval($_REQUEST['OrdId']);
		$amount      = floatval($_REQUEST['TransAmt']);
		$freezeOrdId = strval($_REQUEST['FreezeOrdId']);
		$freezeTrxId = strval($_REQUEST['FreezeTrxId']);
		//余额是系统余额！改！
		$bgret    = $this->financeLogic->balance($userid);
		$balance  = floatval($bgret['userBg']['acctBal']);//用户余额
		$total    = floatval($bgret['sysBg']['acctBal']);//系统余额
		$lastip      = Base_Util_Ip::getClientIp();
		$respCode    = $_REQUEST['RespCode'];
		$respDesc    = $_REQUEST['RespDesc'];
		if($respCode !== '000') {
			Base_Log::error(array(
				'msg'     => $respDesc,
				'userid'  => $userid,
				'orderid' => $orderId,
			));
			//财务类主动投标订单状态更改为处理失败
			$param = array(
				'orderId' => $orderId,
				'status'  => Finance_TypeStatus::ENDWITHFAIL,
				'comment' => '财务类主动投标汇付处理失败',
			);
			$ret = $this->financeLogic->payOrderEnterDB($param);
			if(!$ret) {
				Base_Log::error(array(
					'msg'     => '财务类主动投标订单更新失败',
					'orderId' => $orderId,
					'status'  => Finance_TypeStatus::ENDWITHFAIL,
				));
			}			
			return ;
		}
		//验签！！
		//将主动投标订单状态更改为成功
		$param = array(
			'orderId' => $orderId,
			'status'  => Finance_TypeStatus::ENDWITHSUCCESS,
			'comment' => '财务类主动投标汇付处理成功',
		);
		$ret = $this->financeLogic->payOrderEnterDB($param);
		if(!$ret) {
			Base_Log::error(array(
				'msg'     => '财务类主动投标订单更新失败',
				'orderId' => $orderId,
				'status'  => Finance_TypeStatus::ENDWITHSUCCESS,
			));
		}
		//主动投标记录如表pay_record
		$param = array(
	        'orderId'     => $orderId,
			'userId'      => $userid,
		    'freezeOrdId' => $freezeOrdId,
			'freezeTrxId' => $freezeTrxId,
			'type'        => Finance_TypeStatus::INITIATIVETENDER,
			'amount'      => $amount,
			'balance'     => $balance,
			'total'       => $total,
			'comment'     => '主动投标记录',
		);
		$ret = $this->financeLogic->payRecordEnterDB($param);
		//主动投标记录入库失败
		if(!$ret) {
			Base_log::error(array(
				'msg'         => '主动投标记录入库失败',
				'userId'      => $userid,
				'orderId'     => $orderId,
				'freezeOrdId' => $freezeOrdId,
			));
		}		
		Base::notice($_REQUEST);
		print('RECV_ORD_ID_'.$orderId);		
    }
	
	/**
	 * 汇付回调Action
	 * 投标撤销回调URL
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function tenderCancleAction() {
		
	}
	
	/**
	 * 汇付回调Action
	 * 满标打款回调URL
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function loansAction() {
		
		
	}
	
	
		
}
