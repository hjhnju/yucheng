<?php
/**
 *
 * 财务模块公共逻辑层
 *
 */
class Finance_Logic_Base {

    public function init(){
    
    }
 
    public function __construct(){

    }

    /**
     * 生成订单号Order_id YmdHis+随机数
     * @return string
     */
    public function generateOrderId(){

        $now = date("Ymdhis",mktime());
        $numbers = range(0,9);
        shuffle($numbers);
        $no = 6;
        $ranNum = array_slice($numbers,0,$no);
        foreach($ranNum as $key=>$value){
            $now .= $value;
        }
        return $now;   
    }
    
    /**
     * 根据userId获取用户充值提现记录
     * @param String $userId
     * @return array
     */
    public function getRechargeWithDrawRecord($userId,$type) {
    	$recordList = new Finance_List_Record();
    	if(is_null($type)) {
    		$filterStr = '`type` IN (0,1)';
    	}
    	else {
    		$filterStr = "`type` = `{$type}`";
    	}
    	$recordList->setFilterString($filterStr);
    	$recordList->setOrder('create_time desc');
    	$recordList->setPagesize(PHP_INT_MAX);
    	$list = $refunds->toArray();
    	
    	var_dump($list);
    	return $list;
    }
   
    /**
     * 根据userId获取某个用户的总投资额
     * @param String $userId
     * @return array
     */
    public function fetchTenderAmonut($userId) {
    	$recordList = new Finance_List_Record();
    	$filters = array('user_id' => $userId,
    	                 'type' => 2);//type=2 为主动投标
    	$recordList->setFilter($filters);
    	// $list = array(
        //    'page' => $this->page,
        //    'pagesize' => $this->pagesize,
        //    'pageall' => $this->pageall,
        //    'total' => $this->total,
        //    'list' => $this->data,
        // );
    	$list = $refunds->toArray();
    	$totle = $list['total'];//取出的记录总条数
    	$data = $list['list'];
    	$sum = 0;
    	if(empty($data)){
    		$ret = array('data' => 0,
    		);
    	} else {
    		foreach ($data as $key => $val) {
    			$sum += $val['amount'];
    		}
    		$ret = array('data' => $sum);
    	}
    	   	   	
    	return $ret;
    }
    
    /**
     * 订单表的入库统一入口
     * @param array $param 参数数组
     * @return array
     */
    public function payOrderEnterDB($param) {
    	$regOrder = new Finance_List_Order();
    	$filters = array();
    	$logParam = array();
    	if(is_null($param)) {
    		//未给出参数，无法插入或者更新
    		Base_Log::fatal(array('msg'=>'no params'));
    		return false;
    	}
    	foreach ($param as $key => $value) {
    		$regOrder->$key =  $value;
    		$logParam[$key] = $value;
    	}
    	$ret = $regOrder->save();
    	if(!$ret){
    		$logParam['msg'] = 'Fail to create fiance order';   		
    		Base_Log::fatal($logParam);
    	} else {   		
    		$logParam = array();
    		$logParam['msg'] = 'Success to create finance order';  		
       		Base_Log::notice($logParam);
            return true;
    	}
    	return false;
    	
    }
    
    /**
     * 订单资金记录表入库统一入口
     * @param array $param 参数数组
     * @return array
     */
    public function payRecordEnterDB($param) {
    	$regRecord = new Finance_List_Record();
    	$filters = array();
    	$logParam = array();
    	if(is_null($param)) {
    		//未给出参数，无法插入或者更新
    		Base_Log::fatal(array('msg'=>'no params'));
    		return false;
    	}
    	foreach ($param as $key => $value) {
    		$regOrder->$key =  $value;
    		$logParam[$key] = $value;
    	}
    	$ret = $regOrder->save();
    	if(!$ret){
    		$logParam['msg'] = 'Fail to create fiance order';
    		Base_Log::fatal($logParam);
    	} else {
    		$logParam['msg'] = 'Success to create finance order';
    		Base_Log::notice($logParam);
    		return true;
    	}
    	return false;
    }
    
    /**
     * 签名生成方法
     * @param array $param 将需要组合起来生成签名的参数置入array作为参数
     * @return string 返回签名字符串
     */
    public function generateSign($param) {
    	
    }

}




