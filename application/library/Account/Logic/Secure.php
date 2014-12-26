<?php 
/**
 * 安全中心逻辑类
 * @author lilu
 */
class Account_Logic_Secure {
	
	/**
	 * 计算该用户的安全总分与安全等级
	 * @param array
	 * @return array
	 */
	public function scoreDegree($paramData){
		$ret = array();
		$sum = 0;
		foreach ($paramData as $k=>$v) {
			if($v==1) {
				$sum += 25;
			}
		}
		$ret['score'] = $sum;
		if($sum==0 || $sum==25 || $sum==50) {
			$ret['secureDegree'] = 1;
		} elseif ($sum==75) {
			$ret['secureDegree'] = 2;
		} else {
			$ret['secureDegree'] = 3;
		}
		return $ret;		
	}
}