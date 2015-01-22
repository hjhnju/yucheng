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
		$no = 2;
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
	public function getHuifuid($userid){
		$userid = intval($userid);
		$objUser = User_Api::getUserObject($userid);
		$huifuid = !empty($objUser) ? $objUser->huifuid : '';
		return $huifuid;
	}
	
	/**
	 * 财务类finance_order表入库统一入口
	 * @param array $param 参数数组
	 * @return boolean
	 */
	public function payOrderEnterDB($param) {		
		if(is_null($param) || !isset($param)) {
			//未给出参数，无法插入或者更新
			Base_Log::error(array(
			    'msg'=>'请求参数错误',
			));
			return false;
		}		
		$regOrder = new Finance_Object_Order();
		$logParam = array();
		foreach ($param as $key => $value) {			
			$regOrder->$key =  $value;
			$logParam[$key] = $value;
		}
		$ret = $regOrder->save();	
 		if(!$ret) {			
 			$logParam['msg'] = '财务类交易类型订单入库失败';
 			Base_Log::error($logParam);
			return false;
		}  			
		return true;
	}
	
	/**
	 * 财务类finance_record表入库统一入口
	 * @param array $param 参数数组
	 * @return boolean
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
			$logParam['msg'] = '财务类交易类型记录入库失败';
			Base_Log::error($logParam);
			return false;
		}
		return true;
		
	}  	
	
	/**
	 * 财务类finance_tender表插入入口
	 * @param array $param 参数数组
	 * @return boolean
	 */
	public function payTenderEnterDB($param) {
		$tender = new Finance_Object_Tender();
		$logParam = array();
		if(is_null($param)) {
			//未给出参数，无法插入或者更新
			Base_Log::error(array(
			    'msg'=>'参数错误'
			));
			return false;
		}
		foreach ($param as $key => $value) {
			$tender->$key = $value;
			$logParam[$key] = $value;
		}
		$ret = $tender->save();
		if(!$ret){
			$logParam['msg'] = '投标记录入库失败';
			Base_Log::error($logParam);
			return false;
		}
		return true;
		
	}
	
	/**
	 * 财务类pay_record表删除记录
	 * @param string $orderId
	 * $return boolean
	 */
	public function payRecordDelete($orderId) {
		$orderId = intval($orderId);
		$regRecord = new Finance_Object_Record($orderId);
		$ret = $regRecord->remove();
		if(!ret) {
			Base_Log::error(array(
				'msg'     => '财务类交易类型记录删除失败',
				'orderId' => $orderId,
			));
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
	public function payOrderUpdate($orderId,$status,$type) {
		$regOrder = new Finance_Object_Order();
		$orderId = intval($orderId);
		$status = intval($status);
		$type = intval($type);
		$regOrder->orderId = $orderId;
		$regOrder->status = $status;
		
		$statusDesc = Finance_TypeStatus::getStatusDesc(intval($status));
		$type = Finance_TypeStatus::getType(intval($type));
		$regOrder->comment = "$type".'订单'."$statusDesc";
		$ret = $regOrder->save();		
		if(!$ret){
			Base_Log::error(array(
				'msg'     => "$type".'订单状态更新失败',
				'orderId' => $orderId,
				'status'  => $status,
				'type'    => $type,
			));
			return false;
		}
		return true;
	}
	
	/**
	 * 财务类finance_tender表更新状态
	 * @param int orderId 
	 * @param int status
	 * @return boolean
	 */
	public function payTenderUpdate($orderId,$status) {
		$orderId = intval($orderId);
		$status = intval($status);
		$regTender = new Finance_Object_Tender();
		$regTender->orderId = $orderId;
		$regTender->status = $status;
		$statusDesc = Finance_TypeStatus::getStatusDesc($status);
		$regTender->comment = '订单'."$statusDesc";
		$ret = $regTender->save();
		if(!ret) {
			Base_Log::error(array(
				'msg'     => 'tender表状态更新失败',
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
	public function balance($userid) {
        if(!isset($userid) || $userid <= 0) {
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
		} else if($sysBg['status'] !== '000') {
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
				if($value['AcctType'] === 'MERDT') {
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
	 * 单开文件算手续费
	 * @param string riskLevel
	 * @param float transAmt 
	 * @param months
	 * @return float fee
	 */
	public function getFee($riskLevl,$transAmt,$days) {
		$riskLevl = intval($riskLevl);
		$transAmt = floatval($transAmt);
		$days = intval($days);
	    $serviceFee = $transAmt * (Finance_Fee::$finance_service_fee[$riskLevl]);
	    $serviceFee = sprintf('%.2f', $serviceFee);
	    
	    $dailyRate = Finance_Fee::$risk_reserve[$riskLevl] / 365;
	    $prepareFee = $transAmt * $dailyRate * $days;
	   
	    $prepareFee = sprintf('%.2f', $prepareFee);
	    
	    $retFee = array(
	    	'serviceFee' => $serviceFee,
	    	'prepareFee' => $prepareFee,
	    	'all'        => floatval($serviceFee)+floatval($prepareFee),
	    );
	    return $retFee;	    		
	}
	
	/**
	 * 根据orderId获取投标的相关信息
	 * @param int orderId
	 * @return array || boolean
	 */
	public function getTenderInfo($orderId) {
		$orderId = intval($orderId);
		$tender = new Finance_List_Tender();
		$filters = array('orderId' => $orderId);
		$tender->setFilter($filters);
		$list = $tender->toArray();
		$tenderInfo = $list['list'][0];
		$ret = array(
		    'orderId'    => $tenderInfo['orderId'],
			'orderDate'  => $tenderInfo['orderDate'],
			'proId'      => $tenderInfo['proId'],
			'freezeTrxId'=> $tenderInfo['freezeTrxId'],
			'amount'     => $tenderInfo['amount'],
			'status'     => $tenderInfo['status'],
		);
		return $ret;
	}
	
	/**
	 * 获取借款详情
	 * @param int loanId
	 * @return array || boolean
	 */
	public function getLoanInfo($loanId) {
		if($loanId <= 0) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
			));
			return;
		}
		$loanId = intval($loanId);
		$return = Loan_Api::getLoanInfo($loanId);
		$borrUserId = intval($return['user_id']);//借款人uid
		$borrTotAmt = floatval($return['amount']);//借款总金额
		$yearRate = floatval($return['interest']);//年利率
		$retType = $return['refund_type'];//还款方式
		$now = time();
		$bidStartDate = $now;//投标创建时间
		$bidEndDate = $return['deadline'];//标的截止时间
		$proArea = $return['proArea'];//投标地区
		$ret = array(
			'borrUserId'   => $borrUserId,
			'borrTotAmt'   => $borrTotAmt,
		    'yearRate'     => $yearRate,
			'retType'      => $retType,
			'bidStartDate' => $bidStartDate,
			'bidEndDate'   => $bidEndDate,
			'proArea'      => $proArea,
		);
		return $ret;		
	}
	
    /**
     * 获取充值提现列表数据
     * @param int userid
     * @param int startTime
     * @param int endTime
     * @param int queryType 0--全部  1--充值  2--提现
     * @return array || boolean
     */
	public function getReWiRecord($userid,$startTime,$endTime,$queryType) {
		if(!isset($userid) || !isset($startTime) || !isset($endTime) || !isset($queryType) ) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
			));
			return;
		}
		if($userid <= 0) {
			Base_Log::error(array(
		    	'msg' => '请求参数错误',
			));
			return;
		}
		if($startTime > $endTime) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
			));
			return ;
		}
		if(($queryType !== 0) || ($queryType !== 1) || ($queryType !== 2)) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
			));
			return ;
		}
		$userid = intval($userid);
		$recharge = intval(Finance_TypeStatus::NETSAVE);
		$withdraw = intval(Finance_TypeStatus::CASH);
		$record = new Finance_List_Order();
		//获取全部数据
		if($queryType === 0) {
			$filters = array(
				'userId'      => $userid,
				'create_time' => array("create_time > $startTime and create_time < $endTime"),
				'type'        => array("type=$recharge or type=$withdraw"),
			);
			$record->setFilter($filters);
			$record->setPagesize(PHP_INT_MAX);
			$list = $record->toArray();			
			$data = $list['list'];
			$ret = array();
			foreach ($data as $key => $value) {
				$ret[$key]['time'] = $value['create_time'];//交易时间
				$ret[$key]['transType'] = $value['type'];//交易类型
				$ret[$key]['serialNo'] = $value['orderId'];//序列号
				$ret[$key]['tranAmt'] = $value['amount'];//交易金额
				$ret[$key]['avalBg'] = $value['avlBal'];//可用余额				
			}
            return $ret;
		}
		//获取充值数据
		if($queryType === 1) {
			$filters = array(
				'userId'      => $userid,
				'create_time' => array("create_time > $startTime and create_time < $endTime"),
				'type'        => $recharge,
			);
			$record->setFilter($filters);
			$record->setPagesize(PHP_INT_MAX);
			$list = $record->toArray();
			$data = $list['list'];
			$ret = array();
			foreach ($data as $key => $value) {
				$ret[$key]['time'] = $value['create_time'];//交易时间
				$ret[$key]['transType'] = $value['type'];//交易类型
				$ret[$key]['serialNo'] = $value['orderId'];//序列号
				$ret[$key]['tranAmt'] = $value['amount'];//交易金额
				$ret[$key]['avalBg'] = $value['avlBal'];//可用余额
			}
			return $ret;			
		}
		//获取提现数据
		if($queryType === 2) {
			$filters = array(
				'userId'      => $userid,
				'create_time' => array("create_time > $startTime and create_time < $endTime"),
				'type'        => $withdraw,
			);
			$record->setFilter($filters);
			$record->setPagesize(PHP_INT_MAX);
			$list = $record->toArray();
			$data = $list['list'];
			$ret = array();
			foreach ($data as $key => $value) {
				$ret[$key]['time'] = $value['create_time'];//交易时间
				$ret[$key]['transType'] = $value['type'];//交易类型
				$ret[$key]['serialNo'] = $value['orderId'];//序列号
				$ret[$key]['tranAmt'] = $value['amount'];//交易金额
				$ret[$key]['avalBg'] = $value['avlBal'];//可用余额
			}
			return $ret;
		}		
	}
	
	/**
	 * 对$_REQUEST进行urldecode
	 * @param array
	 * @return array || flase
	 */
    public function arrUrlDec($array) {
        if(!isset($array)) {
            Base_Log::error(array(
                'msg' => '请求参数为空',
            ));
            return flase;
        }
        foreach ($array as $key => &$value) {
        	if(!is_array($value)) {
        		$value = urldecode($value);
        	}                     	 
        }
        return $array;
    }	
}