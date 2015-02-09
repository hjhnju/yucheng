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
        return substr(strval($orderId), 0, 6);
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
            'orderDate' => $orderDate,
            'orderId'   => $orderId,
        );
        return $arrInfo;
    }

    /**
     * 财务类finance_order表入库统一入口
     * @param array $arrOrder 参数数组
     * @return boolean
     */
    public static function saveOrder($arrOrder) {       
        if(empty($arrOrder) || !isset($arrOrder['userId'])) {
            //未给出参数，无法插入或者更新
            Base_Log::error(array(
                'msg'   =>'请求参数错误',
                'order' => $arrOrder,
            ));
            return false;
        }

        $userId = $arrOrder['userId'];
        $order  = null;
        if(!isset($arrOrder['orderId'])){
            $orderInfo = Finance_Logic_Order::genOrderInfo();
            $arrOrder['orderId']   = $orderInfo['orderId'];
            $arrOrder['orderDate'] = $orderInfo['orderDate'];
            $order = new Finance_Object_Order();
        }else{
            $order = new Finance_Object_Order(intval($arrOrder['orderId']));
        }
        //统一修改用户当前可用余额
        $arrOrder['avlBal'] = Finance_Api::getUserAvlBalance($userId);

        foreach ($arrOrder as $key => $value) {            
            $order->$key = $value;
        }
        $ret = $order->save();   
        if(!$ret) {         
            $arrOrder['msg'] = '财务类交易类型订单入库失败';
            Base_Log::error($arrOrder);
            return false;
        }           
        return $order->toArray();
    }
    
    /**
     * 财务类finance_record表入库统一入口
     * @param string $orderid 订单号
     * @param int $userid
     * @param int $type 订单类型
     * @param float $transAmt
     * @param string $comment
     * @return boolean
     */
    public static function saveRecord($orderId, $userid, $type, $transAmt, $comment = '') {
        $arrBal   = Finance_Api::getUserBalance($userid);
        $balance  = $arrBal['AcctBal'];//用户余额
        $avlBal   = $arrBal['AvlBal']; //用户可用余额
        $total    = Finance_Api::getPlatformBalance();//系统余额
        $param    = array(
            'orderId'   => $orderId,
            'orderDate' => self::getOrderDate($orderId),
            'userId'    => $userid,
            'type'      => $type,
            'amount'    => $transAmt,
            'balance'   => $balance,
            'total'     => $total,
            'comment'   => $comment,
            'ip'        => Base_Util_Ip::getClientIp(),
        );

        $regRecord = new Finance_Object_Record();
        foreach ($param as $key => $value) {
            $regRecord->$key = $value;
        }
        $ret = $regRecord->save();
        if(!$ret){
            $param['msg'] = '快照记录入库失败';
            Base_Log::error($param);
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
     * 财务类finance_order表更新订单状态
     * @param string $orderId
     * @param integer $status
     * @param string $failCode
     * @param string $failDesc
     * @param string $arrExts, 额外保存的字段，e.g array('freezeTrxId'=>)
     * @return boolean
     */
    public static function updateOrderStatus($orderId, $status, $failCode='', $failDesc='', $arrExts=false) {
        Base_Log::notice(array(
                'msg'     => '订单状态开始更新',
                'orderId' => $orderId,
                'status'  => $status,
                'failCode'=> $failCode,
                'failDesc'=> $failDesc,
                'arrExts' => $arrExts,
        ));

        $regOrder          = new Finance_Object_Order(intval($orderId));
        $status            = intval($status);
        if($status === $regOrder->status){
            Base_Log::warn(array(
                'msg' => '订单状态一致时不再更新, 防止余额计算非实时不一致',
                'orderId' => $orderId,
                'status'  => $status,
            ));
            return false;
        }

        $regOrder->orderId = $orderId;
        $regOrder->status  = $status;
        $arrBal            = Finance_Api::getUserBalance($regOrder->userId);
        $balance           = $arrBal['AcctBal'];//用户余额
        $regOrder->avlBal  = $arrBal['AvlBal']; //用户可用余额

        $statusDesc        = Finance_Order_Status::getTypeName(intval($status));
        $regOrder->comment = '订单'."$statusDesc";
        if(!empty($failCode) && !empty($failDesc)) {
            $regOrder->failCode = strval($failCode);
            $regOrder->failDesc = strval($failDesc);
        }
        if(is_array($arrExts)){
            foreach ($arrExts as $key => $value) {
                $regOrder->$key = $value;
            }
        }
        $ret = $regOrder->save();       
        if(!$ret){
            Base_Log::error(array(
                'msg'     => '订单状态更新失败',
                'orderId' => $orderId,
                'status'  => $status,
                'failCode'=> $failCode,
                'failDesc'=> $failDesc,
                'arrExts' => $arrExts,
            ));
            return false;
        }
        return true;
    }

    /**
     * 根据orderId获取订单信息
     * @param int orderId
     * @return array || bool
     */
    public static function getOrderInfo($orderId) {
        if(!isset($orderId)) {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'orderId' => $orderId,
            ));
            return false;
        }
        $orderId = intval($orderId);
        $order   = new Finance_Object_Order($orderId);
        $ret     = $order->toArray();
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
        $loanId       = intval($loanId);
        $return       = Loan_Api::getLoanInfo($loanId);
        $borrUserId   = intval($return['user_id']);//借款人uid
        $borrTotAmt   = floatval($return['amount']);//借款总金额
        $yearRate     = floatval($return['interest']);//年利率
        $retType      = $return['refund_type'];//还款方式
        $now          = time();
        $bidStartDate = $now;//投标创建时间
        $bidEndDate   = $return['deadline'];//标的截止时间
        $proArea      = $return['proArea'];//投标地区
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
    public static function getRecords($userid, $startTime, $endTime, $queryType,
        $page, $pageSize) {
        $userid    = intval($userid);
        $queryType = intval($queryType);
        $page      = intval($page);
        $pageSize  = intval($pageSize);
        $startTime = intval($startTime);
        $endTime   = intval($endTime);
        if($userid<=0 || ($startTime > $endTime) || $queryType<=0) {
            Base_Log::error(array(       
                'msg'  => '请求参数错误',
                'args' => func_get_args(),
            ));
            return false;
        }
        $list = new Finance_List_Order();
        $list->setFilter(array('userId' => $userid));
        	$list->appendFilterString("(`create_time` between '$startTime' and '$endTime')");
        $strSt = implode(',', array(Finance_Order_Status::SUCCESS, Finance_Order_Status::PROCESSING, 
            Finance_Order_Status::FAILED));
        $list->appendFilterString("`status` IN ($strSt)");
        if($queryType !== 1){
            $list->appendFilter(array('type' => $queryType));
        }
        $list->setPagesize($pageSize);
        $list->setPage($page);
        $list->setOrder("`create_time` desc");
        $list    = $list->toArray();

        $arrData = array();
        foreach ($list['list'] as $key => $value) {         
            $arrData[$key]['time']      = date("Y-m-d H:i",$value['create_time']);//交易时间                
            $arrData[$key]['transType'] = intval($value['type']);
            $arrData[$key]['typeName']  = Finance_Order_Type::getTypeName($value['type']);       
            $arrData[$key]['status']    = Finance_Order_Status::getTypeName($value['status']);
            $arrData[$key]['serialNo']  = strval($value['orderId']);//序列号
            $plusOrMinus                = Finance_Order_Type::getPlusMinusChar($value['type']);       
            $arrData[$key]['tranAmt']   = $plusOrMinus . $value['amount'];//交易金额
            $arrData[$key]['avalBg']    = $value['avlBal'];//可用余额
        }

        $arrRet['page']     = $list['page'];
        $arrRet['pagesize'] = $list['pagesize'];
        $arrRet['pageall']  = $list['pageall'];
        $arrRet['total']    = $list['total'];
        $arrRet['list']     = $arrData;
        return $arrRet;         
    }
}
