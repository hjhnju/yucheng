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
    CONST PRIVATEKEY = '35333034333000000000000000000000000000003030303031303234303031333937353132303138AC16BABD43DF71C18A10EF2284D4CECE8CDA746066A4273D489866D28D873CC02908C3AD55068F0FCABD4C2D07DBDA314968B81CFED57F7A3512F0659D62CB16C754A8B0BB8F8CC2FD4A78C8375536B68F88FC31069AA91E11117450BA68448CC258FB7A0B462730FBC49D4DBC87693466662FF7022D75834E4C0CD26B439BF370AD20057458000BA6FB1CEDFD1C6CDB1037A86CFD1CDE2D463A453756B1E34858D121C8F8562778D3861AAA997372052256C1D65B5D492B582F84FF047BABA2448EC3B52C45427C80E2C173ED735807DCFBF13349016D2DFFC7C814E15A9C5991D5E240D54A3BB8529631460D4D2E38A6E052BACD3F9DB14097B567C8798E7E00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000100015B9E57F039CB9D5F60D568FAF139F0B1B9C0E2899C0A9B3C682EBB89676F74B708898BA4DB04B0F879710C7DFBE83C4FA51C14BC394729282948554D06680C9740C2DF9FEDD8C2A99C1280A5B79C8A80A7955C38941D0D3926F090B78CA95B6E1BEF8B8CF7F1CE097BBAF675C99664DFD75A195021026CE9E551E986B51CBF71DFE3778C4181688C8A543477ACFCD4527FA56DEFA8E08E9ABCA5FC0B96B29BD251234F2ABAC5BA4FA39E834FF2D47C2A2A845830ECEB006463B1A3BFC2077B363DE6DA81C2E5F440FB359FFAB62FD373905AE60D16CCBAA5F7375BD9B8B5DD0D68A12B33C9E54406D68DC316C33CE4036F2559A8449B23FBA54546BE15A756EFFD4D8F5ABDC3A4BBC2E8BB38D3BF95D947C3BA49763F83EA1EEB9BC9AC33E6CAC804BFB45F24DA38CA9BB79FBD7A65DE282B268EE80C4EF808B228CD201CC761E23B4D7734652642';
    CONST PUBLICKEY = '393939393939000000000000000000000000000030303031F60CB7B659222AEB12654EBB05C43CC2408154D57EED62D8F46FB946815A631D4A708DBA667673F69A279E371CA16064296643CBB0785E18FFDA84DB065DCA42D48349D3839B6723B604AC0BF19994147E56C6EFFD7BF6CF37E766D58E6CC6EF023B2A03E00D85829C51550012B1ABBF5710D6D9BED03A69BEC144D73EE2154F000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001000133D851D515306218';
    
    
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
	 * @param int $type 
	 * @param string $failCode
	 * @param string $failDesc
 	 * @return boolean
	 */
	public function payOrderUpdate($orderId,$status,$type,$avlBal,$failCode='',$failDesc='') {
		Base_Log::debug(array(
			$orderId,
			$status,
			$type,
			$avlBal,
			Finance_TypeStatus::getStatusDesc(intval($status)),
			$failCode,
			$failDesc,
		));
		$regOrder = new Finance_Object_Order();
		$orderId = intval($orderId);
		$status = intval($status);
		$type = intval($type);
		$avlBal = floatval($avlBal);
		$regOrder->orderId = $orderId;
		$regOrder->status = $status;
        $regOrder->avlBal = $avlBal;		
		$statusDesc = Finance_TypeStatus::getStatusDesc(intval($status));
		$type = Finance_TypeStatus::getType(intval($type));
		$regOrder->comment = "$type".'订单'."$statusDesc";
		if(!empty($failCode) && !empty($failDesc)) {
			$regOrder->failCode = strval($failCode);
			$regOrder->failDesc = strval($failDesc);
		}		
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
    public function payTenderUpdate($orderId, $status) {
        if(!isset($orderId) || !isset($status)) {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'orderId' => $orderId,
                'status'  => $status,
            ));
            return false;
        }
        $orderId            = intval($orderId);
        $status             = intval($status);
        $regTender          = new Finance_Object_Tender();
        $regTender->orderId = $orderId;
        $regTender->status  = $status;
        $statusDesc         = Finance_TypeStatus::getStatusDesc($status);
        $regTender->comment = '订单'."$statusDesc";
        $ret                = $regTender->save();
        if(!$ret) {
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
            $ret['sysBg']['avlBal']  = 0.00;
            $ret['sysBg']['acctBal'] = 0.00;
            $ret['sysBg']['frzBal']  = 0.00;
        } else if($sysBg['status'] !== '000') {
            Base_Log::error(array(
                'msg'    => $sysBg['statusInfo'],
                'userid' => $userid,
            ));
            $ret['sysBg']['avlBal']  = 0.00;
            $ret['sysBg']['acctBal'] = 0.00;
            $ret['sysBg']['frzBal']  = 0.00;
        } else {
            $details = $sysBg['data']['AcctDetails'];
            foreach ($details as $key => $value) {
                if($value['AcctType'] === 'MERDT') {
                    $ret['sysBg']['avlBal']  = floatval(str_replace(',', '', $value['AvlBal']));
                    $ret['sysBg']['acctBal'] = floatval(str_replace(',', '', $value['AcctBal']));
                    $ret['sysBg']['frzBal']  = floatval(str_replace(',', '', $value['FrzBal']));
                }
            }            
        }
        return $ret;        
    }
        
    /**
     * 根据orderId获取投标的相关信息
     * @param int orderId
     * @return array || boolean
     */
    public function getTenderInfo($orderId) {
        if(!isset($orderId)) {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'orderId' => $orderId,
            ));
            return false;
        }
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
        if(!isset($loanId) || $loanId <= 0) {
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
     * @param int queryType 1--全部  2--充值  3--提现
     * @return array || boolean
     */
	public function getReWiRecord($userid,$startTime,$endTime,$queryType,$page,$pageSize) {
		if(!isset($userid) || !isset($startTime) || !isset($endTime) || !isset($queryType) || 
		   !isset($page) || !isset($pageSize)) {
		   	echo 0;die;
			Base_Log::error(array(			
				'msg' => '请求参数错误',
			));
			return false;
		}
		$userid = intval($userid);
		$queryType = intval($queryType);
		$page = intval($page);
		$pageSize = intval($pageSize);
		$userid = intval($userid);
		if($userid <= 0) {
			echo 1;die;
			Base_Log::error(array(
		    	'msg' => '请求参数错误',
			));
			return false;
		}
		if($startTime > $endTime) {
			echo 2;die;
			Base_Log::error(array(
				'msg' => '请求参数错误',
			));
			return false;
		}
		$recharge = intval(Finance_TypeStatus::NETSAVE);
		$withdraw = intval(Finance_TypeStatus::CASH);
		$tender = intval(Finance_TypeStatus::INITIATIVETENDER)   ;  //主动投标
		$cancel = intval(Finance_TypeStatus::TENDERCANCEL);  //投标撤销
		$loans = intval(Finance_TypeStatus::LOANS);  //满标打款
		$repay = intval(Finance_TypeStatus::REPAYMENT);  //还款
		$transfer = intval(Finance_TypeStatus::TRANSFER);  //商户用自动扣款转账
		$merCash = intval(Finance_TypeStatus::MERCASH);
		$receAwd = intval(Finance_TypeStatus::RECE_AWD);
		$moneyBack = intval(Finance_TypeStatus::MONEY_BACK);
		$record = new Finance_List_Order();
		//获取全部数据
		if($queryType === 1) {
			$startTime = strval($startTime);
			$endTime = strval($endTime);
			$filters = array(
				array("(`userId`='$userid')"),
				array("(`create_time` between '$startTime' and '$endTime')"),
				array("(												
					(`type`= '$recharge' and `status`=3)						
					or 						
					(`type`= '$withdraw' and (`status` IN (1,3)))
				    or
					(`type`= '$tender' and `status`=3) 
				    or
					(`type`= '$cancel' and `status`=3) 
				    or
					(`type`= '$loans' and `status`=3)
					or
					(`type`= '$repay' and `status`=3)    
					or
					(`type`= '$transfer' and `status`=3) 	
					or
					(`type`= '$merCash' and `status`=3)
					or
					(`type`= '$receAwd' and `status`=3)
					or
					(`type`= '$moneyBack' and `status`=3)		
				)"),
			);
			$record->setFilter($filters);
			$record->setPagesize($pageSize);
			$record->setPage($page);
			$record->setOrder("`create_time` desc");
			$list = $record->toArray();			
			$data = $list['list'];            
			if(empty($data)) {
				$ret = array();
				$ret['page'] = 0;
				$ret['pagesize'] = 0;
				$ret['pageall'] = 0;
				$ret['total'] = 0;
				$ret['list'] = array();
				return $ret;
			}			
			//var_dump($list);die;
			$_ret = array();
			foreach ($data as $key => $value) {			
				$status = $value['status'];
				$_ret[$key]['time'] = date("Y-m-d H:i",$value['create_time']);//交易时间                
                $_ret[$key]['transType'] = intval($value['type']);
                $_ret[$key]['typeName'] = Finance_TypeStatus::getType($value['type']);                  
                $_ret[$key]['status'] = Finance_TypeStatus::getType($value['type']).Finance_TypeStatus::getStatusDesc(intval($value['status']));                               
                $_ret[$key]['serialNo'] = strval($value['orderId']);//序列号
                $_ret[$key]['tranAmt'] = Base_Util_Number::tausendStyle(floatval($value['amount']));//交易金额
                $_ret[$key]['avalBg'] = Base_Util_Number::tausendStyle(floatval($value['avlBal']));//可用余额
			}
			
			$ret = array();
			$ret['page'] = $list['page'];
			$ret['pagesize'] = $list['pagesize'];
			$ret['pageall'] = $list['pageall'];
			$ret['total'] = $list['total'];
			$ret['list'] = $_ret;
            return $ret;
		}
		//获取充值数据
		if($queryType === 2) {
			$filters = array(
				'userId'      => array("(`userId`='$userid')"),
				'create_time' => array("(create_time between '$startTime' and '$endTime')"),
				'type'        => $recharge,
				'status'      => array("(`status`= 3)"),//只拉取成功的数据
			);
			$record->setFilter($filters);
			$record->setPagesize($pageSize);
			$record->setPage($page);
			$record->setOrder("create_time desc");
			$list = $record->toArray();
			$data = $list['list'];
			if(empty($data)) {
				$ret = array();
				$ret['page'] = 0;
				$ret['pagesize'] = 0;
				$ret['pageall'] = 0;
				$ret['total'] = 0;
				$ret['list'] = array();
				return $ret;
			}
			$_ret = array();
			foreach ($data as $key => $value) {
				$status = $value['status'];
				$_ret[$key]['time'] = date("Y-m-d H:i",$value['create_time']);//交易时间
				$_ret[$key]['transType'] = intval($value['type']);
                $_ret[$key]['typeName'] = Finance_TypeStatus::getType($value['type']);                  
                $_ret[$key]['status'] = Finance_TypeStatus::getType($value['type']).Finance_TypeStatus::getStatusDesc(intval($value['status']));                               
				$_ret[$key]['serialNo'] = strval($value['orderId']);//序列号
				$_ret[$key]['tranAmt'] = Base_Util_Number::tausendStyle(floatval($value['amount']));//交易金额
				$_ret[$key]['avalBg'] = Base_Util_Number::tausendStyle(floatval($value['avlBal']));//可用余额
			}
			$ret = array();
			$ret['page'] = $list['page'];
			$ret['pagesize'] = $list['pagesize'];
			$ret['pageall'] = $list['pageall'];
			$ret['total'] = $list['total'];
			$ret['list'] = $_ret;		
			return $ret;			
		}
		//获取提现数据
		if($queryType === 3) {
			$filters = array(
				'userId'      => array("(`userId`='$userid')"),
				'create_time' => array("(create_time between '$startTime' and '$endTime')"),
				'type'        => $withdraw,
				'status'      => array("(`status`= 3 or `status`=1)"),//只拉取成功的数据
			);
			$record->setFilter($filters);
			$record->setPagesize($pageSize);
			$record->setPage($page);
			$record->setOrder("`create_time` desc");
			$list = $record->toArray();
			$data = $list['list'];
			if(empty($data)) {
				$ret = array();
				$ret['page'] = 0;
				$ret['pagesize'] = 0;
				$ret['pageall'] = 0;
				$ret['total'] = 0;
				$ret['list'] = array();
				return $ret;
			}
			$_ret = array();
			foreach ($data as $key => $value) {
				$status = $value['status'];
				$_ret[$key]['time'] = date("Y-m-d H:i",$value['create_time']);//交易时间
				$_ret[$key]['transType'] = intval($value['type']);
                $_ret[$key]['typeName'] = Finance_TypeStatus::getType($value['type']);                  
                $_ret[$key]['status'] = Finance_TypeStatus::getType($value['type']).Finance_TypeStatus::getStatusDesc(intval($value['status']));                               
				$_ret[$key]['serialNo'] = strval($value['orderId']);//序列号
				$_ret[$key]['tranAmt'] = Base_Util_Number::tausendStyle(floatval($value['amount']));//交易金额
				$_ret[$key]['avalBg'] = Base_Util_Number::tausendStyle(floatval($value['avlBal']));//可用余额
			}
			$ret = array();
			$ret['page'] = $list['page'];
			$ret['pagesize'] = $list['pagesize'];
			$ret['pageall'] = $list['pageall'];
			$ret['total'] = $list['total'];
			$ret['list'] = $_ret;
			return $ret;
		}		
	}
    
    /**
     * 对$_REQUEST进行urldecode
     * @param array
     * @return array || flase
     */   
    public function arrUrlDec($arrParam) {
        $ret = array();
        foreach ($arrParam as $key => $value) {
            if(!is_array($value)) {
                $ret[$key] = urldecode($value);
            } else {
                $ret[$key] = $this->arrUrlDec($value);//对数组值进行递归解码
            }
        }
        return $ret;
    }
    
    /**
     * 验签
     * 
     */
    public function verify($originStr, $sign) {
        $scureTool = new Finance_Chinapnr_SecureTool(self::PRIVATEKEY,self::PUBLICKEY);
        return $scureTool->verify($originStr, $sign);
    }

    /**
     * 验签
     * @param  $arrFields 字段 array('field1', 'field2')
     * @param  $arrParams array('field' => 'value')
     */
    public function verifySign($arrFields, $arrParams, $sign) {
        $originStr = $this->getSignContent($arrParams, $arrFields);
        $scureTool = new Finance_Chinapnr_SecureTool(self::PRIVATEKEY,self::PUBLICKEY);
        Base_Log::debug(array(
            'originStr' => $originStr,
        ));
        return $scureTool->verify($originStr, $sign);
    }
    
    /**
     * @desc 指定验签报文的主键，自动拼接验签原文
     * @param  $params
     * @param  $keys
     * @return string
     */
    public function getSignContent($params=array(), $keys=array()) {
        $ret="";
        foreach ($keys as $key){
            $ret.= isset($params[$key])?(trim($params[$key])):"";
        }
        return $ret;
    }
}