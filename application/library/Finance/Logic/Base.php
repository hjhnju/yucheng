<?php 
/**
 * 财务模块基础逻辑类
 * @author lilu
 */
class Finance_Logic_Base {
	
	//汇付平台版本
	CONST VERSION_10 = "10";
	CONST VERSION_20 = "20";
	//本平台mercustid
	CONST MERCUSTID  = "6000060000677575";
	//BusiCode 营业执照编号
	CONST BUSICODE = "";
	
	private function getMillisecond() {
		list($s1, $s2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
	}
	
    /**
	 * 生成订单号Order_id YmdHis+随机数
	 * @return string
	 */
    protected function genOrderInfo(){
    	$timeStr = $this->getMillisecond();
    	$time = substr($timeStr,0,10);
    	$ms = substr($timeStr,10,3);
    	
		$date = date("Ymd",$time);
		$now = date("YmdHis",$time) . $ms ;
		
		$numbers = range(0,9);
		shuffle($numbers);
		$no = 1;
		$ranNum = array_slice($numbers,0,$no);
		foreach($ranNum as $key=>$value){
			$now .= $value;
		}
		$ret = array(
			'date' => $date,
			'orderId' => $now,
		);
		return $ret;
	}
	
	/**
	 * 组装调用字符串
	 * @param method
	 * @param array 
	 * @return array
	 */
	protected function genCmdStr($method,$field) {
		$cmd= '$result= $chinapnr->'.$method.'(';
		foreach ($field as $k => $v){
			$cmd.= '"'.$v.'",';
		}
		$cmd= substr($cmd, 0, strlen($cmd)-1);
		$cmd.=');';
		return $cmd;
	}
	
	/**
	 * 获取登录用户的userid
	 * @return integer
	 */
	protected function getUserId() {
		$objUser = User_Api::checkLogin();
		$userid = !empty($objUser) ? $objUser->userid : 0;
		return $userid;
	}
	
	/**
	 * 通过用户userid获取用户汇付id
	 */
	protected function getHuifuid($userid){
		$objUser = User_Api::getUserObject($userid);
		$huifuid = !empty($objUser) ? $objUser->huifuid : '';
		return $huifuid;
	}
	
	/**
	 * 财务类pay_order表入库统一入口
	 * @param array $param 参数数组
	 * @return array || boolean
	 */
	public function payOrderEnterDB($param) {
		$regOrder = new Finance_Object_Order();
		$logParam = array();		
		if(is_null($param)) {
			//未给出参数，无法插入或者更新
			Base_Log::error(array(
			    'msg'=>'no params',
			));
			return false;
		}		
		foreach ($param as $key => $value) {
			
			$regOrder->$key =  $value;
			$logParam[$key] = $value;
		}
		$ret = $regOrder->save();
	
 		if(!$ret) {			
			$logParam['msg'] = '创建财务类订单表失败';
			Base_Log::error($logParam);
			return false;
		}  			
		return true;
	}
	
	/**
	 * 财务类pay_record表入库统一入口
	 * @param array $param 参数数组
	 * @return array || boolean
	 */
	public function payRecordEnterDB($param) {
		$regRecord = new Finance_Object_Record();
		$logParam = array();
		if(is_null($param)) {
			//未给出参数，无法插入或者更新
			Base_Log::error(array(
			    'msg'=>'参数错误'
			));
			return false;
		}
		foreach ($param as $key => $value) {
			$regRecord->$key = $value;
			$logParam[$key] = $value;
		}
		$ret = $regRecord->save();
		if(!$ret){
			$logParam['msg'] = '创建财务类记录表失败';
			Base_Log::error($logParam);
			return false;
		}
		return true;
		
	}  	
	
	/**
	 * 财务类pay_order表更新状态
	 * @param string $orderId
	 * @param integer $status
	 * @return boolean
	 */
	public function payOrderUpdate($orderId,$status) {
		$regOrder = new Finance_Object_Order();
		$regOrder->orderId = $orderId;
		$regOrder->status = intval($status);
		$ret = $regOrder->save();
		
		
		if(!$ret){
			$logParam['msg'] = '更新财务类订单表失败';
			Base_Log::error(array(
				'msg'     => '更新财务类订单表失败',
				'orderId' => $orderId,
				'status'  => $status,
			));
			return false;
		}
		return true;
	}

	/**
	 * 获取用户的余额+系统余额
	 * @param string userid 
	 * @return array || false
	 * 
	 */
	public function balance($userid){
		if($userid <= 0) {
			Base_Log::error(array(
				'msg'    => '请求参数错误',
				'userid' => $userid,
			));
			return false;
		}
		$mercustId = self::MERCUSTID;
		$ret = array();
		$huifuid = $this->getHuifuid($userid);
		$userBg = Finance_Api::queryBalanceBg($huifuid);
		if($userBg['status'] === Finance_RetCode::REQUEST_API_ERROR) {
			Base_Log::error(array(
			    'msg'    => Finance_RetCode::getMsg($userBg['status']),
			    'userid' => $userid,
			));
			$ret['userBg'] = $userBg['data'];			
		} else if ($userBg['status'] !== '000') {
			Base_Log::error(array(
			    'msg'    => $userBg['statusInfo'],
			    'userid' => $userid,
			));
			$ret['userBg'] = $userBg['data'];
		} else {
			$ret['userBg'] = $userBg['data'];
		}
		
		$sysBg = Finance_Api::queryAccts();
		if($sysBg['status'] === Finance_RetCode::REQUEST_API_ERROR) {
			Base_Log::error(array(
			    'msg'    => Finance_RetCode::getMsg($userBg['status']),
			    'userid' => $userid,
			));
			$ret['sysBg']['avlBal']  = '0.00';
			$ret['sysBg']['acctBal'] = '0.00';
			$ret['sysBg']['frzBal']  = '0.00';
		} else if($sysBg !== '000') {
			Base_Log::error(array(
			    'msg'    => $sysBg['statusInfo'],
			    'userid' => $userid,
			));
			$ret['sysBg']['avlBal']  = '0.00';
			$ret['sysBg']['acctBal'] = '0.00';
			$ret['sysBg']['frzBal']  = '0.00';
		} else {
			$details = $sysBg['data']['AcctDetails'];
			foreach ($details as $key => $value) {
				if($value['AcctType'] === 'BASEDT') {
					$ret['sysBg']['avlBal']  = $value['AvlBal'];
			        $ret['sysBg']['acctBal'] = $value['AcctBal'];
			        $ret['sysBg']['frzBal']  = $value['FrzBal'];
				}
			}
			 
		}
		return $ret;		
	}
	
	/**
	 * 手续费计算Logic层
	 * @param string riskLevel
	 * @param float transAmt 
	 * @param months
	 * @return float fee
	 */
	public function getFee($riskLevl,$transAmt,$months) {
	    $serviceFee = floatval($transAmt * floatval(Finance_Fee::$finance_service_fee[$riskLevl]));
	    $monthlyRate = floatval(Finance_Fee::$risk_reserve[$riskLevl]) / 12;
	    $prepareFee = floatval($transAmt * $monthlyRate * $months );
	    $manageFee = floatval($transAmt * floatval(Finance_Fee::$acc_manage_fee[$riskLevl]) * $months);  
	    $retFee = $serviceFee + $prepareFee + $manageFee;
	    		
	}
}