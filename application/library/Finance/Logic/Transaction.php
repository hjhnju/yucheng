<?php
/**
 * 财务模块公共逻辑层
 * @author lilu
 */
class Finance_Logic_Transaction extends Finance_Logic_Base{
    
    /**
     * 根据userId获取用户充值提现记录
     * @param String $userId
     * @return array
     */
    public function getRechargeWithDrawRecord($userId,$type) {
    	$recordList = new Finance_List_Record();
    	if($type === 0) {
    		$filterStr = '`type` IN (1,2)';
    	}
    	else {
    		$filterStr = "`type` = `{$type}`";
    	}
    	$recordList->setFilterString($filterStr);
    	$recordList->setOrder('create_time desc');
    	$recordList->setPagesize(PHP_INT_MAX);
    	$list = $recordList->toArray();
    	
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
     * 签名生成方法
     * @param array $param 将需要组合起来生成签名的参数置入array作为参数
     * @return string 返回签名字符串
     */
    public function generateSign($param) {
    	
    }

}




