<?php
class Finance_Logic_Order {   

    private static function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 生成订单号Order_id YmdHis + 微秒 + 随机数
     * 19位
     * @return string
     */
    public static function genOrderId(){
        $arrInfo = self::genOrderInfo();
        return strval($arrInfo['orderId']);
    }

    /**
     * 获取订单日期
     * @param $orderId 订单号
     */
    public static function getOrderDate($orderId){
        return substr(strval($orderId), 0, 4);
    }

    /**
     * 生成订单号Order信息
     * 
     * @return array('orderId'=>, 'date'=>)
     */
    public static function genOrderInfo(){
        $timeStr = self::getMillisecond();

        $time    = substr($timeStr, 0, 10);
        $ms      = substr($timeStr, 10, 3);
        $orderDate = date("Ymd", $time);
        $orderId = date("YmdHis", $time) . $ms ;

        $numbers = range(0, 9);
        shuffle($numbers);
        $no     = 2;
        $ranNum = array_slice($numbers, 0, $no);
        foreach($ranNum as $key=>$value){
            $orderId .= $value;
        }

        $arrInfo = array(
            'date'    => $orderDate,
            'orderId' => $orderId,
        );
        return $arrInfo;
    }

    /**
     * 财务类finance_order表入库统一入口
     * @param array $param 参数数组
     * @return boolean
     */
    public static function payOrderEnterDB($param) {       
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
    public static function payRecordEnterDB($param) {
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
    public static function payTenderEnterDB($param) {
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
    public static function payRecordDelete($orderId) {
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
    public static function payOrderUpdate($orderId,$status,$type,$avlBal,$failCode='',$failDesc='') {
        Base_Log::debug(array(
            $orderId,
            $status,
            $type,
            $avlBal,
            Finance_TypeStatus::getStatusDesc(intval($status)),
            $failCode,
            $failDesc,
        ));
        $regOrder          = new Finance_Object_Order();
        $orderId           = intval($orderId);
        $status            = intval($status);
        $type              = intval($type);
        $avlBal            = floatval($avlBal);
        $regOrder->orderId = $orderId;
        $regOrder->status  = $status;
        $regOrder->avlBal  = $avlBal;        
        $statusDesc        = Finance_TypeStatus::getStatusDesc(intval($status));
        $type              = Finance_TypeStatus::getType(intval($type));
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
    public static function payTenderUpdate($orderId, $status) {
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
     * 根据orderId获取投标的相关信息
     * @param int orderId
     * @return array || bool
     */
    public static function getTenderInfo($orderId) {
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
     * 根据orderId获取订单的相关信息
     * @param int orderId 
     * @return array || bool
     */
    public static function getOrderInfo($orderId) {
    	if(!isset($orderId)) {
    		Base_Log::error(array(
    			'msg' => '请求参数错误',
    		));
    		return false;
    	}
    	$orderId  = intval($orderId);
    	$order = new Finance_List_Order();
    	$filters = array('orderId' => $orderId);
    	$order->setFilter($filters);
    	$list = $order->toArray();
    	$orderInfo = $list['list'][0];
    	$ret = array(
    		'orderId'   => $orderInfo['orderId'],
    		'orderDate' => $orderInfo['orderDate'],
    		'userid'    => $orderInfo['userId'],
    		'type'      => $orderInfo['type'],
    		'amount'    => $orderInfo['amount'],
    		'avlBal'    => $orderInfo['avlBal'],
    		'status'    => $orderInfo['status'],
    		'failCode'  => $orderInfo['failCode'],
    		'failDesc'  => $orderInfo['failDesc'],    			
    	);
    	return $ret;
    }
    
    /**
     * 获取借款详情
     * @param int loanId
     * @return array || boolean
     */
    public static function getLoanInfo($loanId) {
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
    public static function getReWiRecord($userid,$startTime,$endTime,$queryType,$page,$pageSize) {
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
            $_ret = array();
            foreach ($data as $key => $value) {         
                $status = $value['status'];
                $_ret[$key]['time'] = date("Y-m-d H:i",$value['create_time']);//交易时间                
                $_ret[$key]['transType'] = intval($value['type']);
                $_ret[$key]['typeName'] = Finance_TypeStatus::getType($value['type']);       
                switch ($value['status']) {
                	case Finance_TypeStatus::ORDER_INITIALIZE:
                		$_ret[$key]['status'] = "初始化";
                	    break;
                	case Finance_TypeStatus::PROCESSING:
                		$_ret[$key]['status'] = "处理中";
                		break;
                	case Finance_TypeStatus::ENDWITHFAIL:
                		$_ret[$key]['status'] = "失败";
                		break;
                	case Finance_TypeStatus::ENDWITHSUCCESS:
                		$_ret[$key]['status'] = "成功";
                		break;
                	case Finance_TypeStatus::FREEZING:
                		$_ret[$key]['status'] = "资金冻结中";
                		break;   
                	case Finance_TypeStatus::PAYING:
                		$_ret[$key]['status'] = "打款中";
                		break;
                	case Finance_TypeStatus::HAVEPAYED:
                		$_ret[$key]['status'] = "打款中";
                		break;
                	case Finance_TypeStatus::PAYFAIDED:
                		$_ret[$key]['status'] = "已打款";
                		break;
                	case Finance_TypeStatus::CANCELD:
                		$_ret[$key]['status'] = "投标已撤销";
                		break;
                }           
                $_ret[$key]['serialNo'] = strval($value['orderId']);//序列号
                $_ret[$key]['tranAmt'] = $value['amount'];//交易金额
                $_ret[$key]['avalBg'] = $value['avlBal'];//可用余额
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
                switch ($value['status']) {
                	case Finance_TypeStatus::ORDER_INITIALIZE:
                		$_ret[$key]['status'] = "初始化";
                		break;
                	case Finance_TypeStatus::PROCESSING:
                		$_ret[$key]['status'] = "处理中";
                		break;
                	case Finance_TypeStatus::ENDWITHFAIL:
                		$_ret[$key]['status'] = "失败";
                		break;
                	case Finance_TypeStatus::ENDWITHSUCCESS:
                		$_ret[$key]['status'] = "成功";
                		break;
                	case Finance_TypeStatus::FREEZING:
                		$_ret[$key]['status'] = "资金冻结中";
                		break;
                	case Finance_TypeStatus::PAYING:
                		$_ret[$key]['status'] = "打款中";
                		break;
                	case Finance_TypeStatus::HAVEPAYED:
                		$_ret[$key]['status'] = "打款中";
                		break;
                	case Finance_TypeStatus::PAYFAIDED:
                		$_ret[$key]['status'] = "已打款";
                		break;
                	case Finance_TypeStatus::CANCELD:
                		$_ret[$key]['status'] = "投标已撤销";
                		break;
                }                          
                $_ret[$key]['serialNo'] = strval($value['orderId']);//序列号
                $_ret[$key]['tranAmt'] = $value['amount'];//交易金额
                $_ret[$key]['avalBg'] = $value['avlBal'];//可用余额
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
                switch ($value['status']) {
                	case Finance_TypeStatus::ORDER_INITIALIZE:
                		$_ret[$key]['status'] = "初始化";
                		break;
                	case Finance_TypeStatus::PROCESSING:
                		$_ret[$key]['status'] = "处理中";
                		break;
                	case Finance_TypeStatus::ENDWITHFAIL:
                		$_ret[$key]['status'] = "失败";
                		break;
                	case Finance_TypeStatus::ENDWITHSUCCESS:
                		$_ret[$key]['status'] = "成功";
                		break;
                	case Finance_TypeStatus::FREEZING:
                		$_ret[$key]['status'] = "资金冻结中";
                		break;
                	case Finance_TypeStatus::PAYING:
                		$_ret[$key]['status'] = "打款中";
                		break;
                	case Finance_TypeStatus::HAVEPAYED:
                		$_ret[$key]['status'] = "打款中";
                		break;
                	case Finance_TypeStatus::PAYFAIDED:
                		$_ret[$key]['status'] = "已打款";
                		break;
                	case Finance_TypeStatus::CANCELD:
                		$_ret[$key]['status'] = "投标已撤销";
                		break;
                }                
                $_ret[$key]['serialNo'] = strval($value['orderId']);//序列号
                $_ret[$key]['tranAmt'] = $value['amount'];//交易金额
                $_ret[$key]['avalBg'] = $value['avlBal'];//可用余额
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
}