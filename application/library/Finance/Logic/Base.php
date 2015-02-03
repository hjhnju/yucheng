<?php 
/**
 * 财务模块基础逻辑类
 * @author lilu
 */
class Finance_Logic_Base {
    
    //汇付平台版本
    CONST VERSION_10 = "10";
    CONST VERSION_20 = "20";

<<<<<<< HEAD
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
=======
    protected $merCustId;
>>>>>>> 81cc5c0983b2567ae372d78ebe0c62714589d55a

    protected $chinapnr;

    public function __construct(){
        $arrConf          = Base_Config::getConfig('huifu', CONF_PATH . '/huifu.ini');
        $this->merCustId  = $arrConf['merCustId'];
        $this->privateKey = $arrConf['merchantPrivateKey'];
        $this->publicKey  = $arrConf['chinapnrPublicKey'];
        $this->chinapnr   = Finance_Chinapnr_Client::getInstance();
    }
    
    /**
     * 通过用户userid获取用户汇付id
     * @return string $huifuid
     */
    public function getHuifuid($userid){
        $userid  = intval($userid);
        $objUser = User_Api::getUserObject($userid);
        $huifuid = !empty($objUser) ? $objUser->huifuid : '';
        return $huifuid;
    }

}
    
