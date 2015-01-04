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
	
    /**
	 * 生成订单号Order_id YmdHis+随机数
	 * @return string
	 */
    protected function genOrderInfo(){
		$date = date("Ymd",mktime());
		$now = date("Ymdhis",mktime());
		$numbers = range(0,9);
		shuffle($numbers);
		$no = 6;
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
}