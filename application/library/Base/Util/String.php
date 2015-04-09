<?php 
class Base_Util_String {
	
	/**
	 * 邮箱加星加密
	 * @param string $email 需要加密的字符串
	 * @return string
	 */
	public static function starEmail($email) {
		
		if(empty($email)) {			
			return '';
		}
		$email = explode('@',$email);
		$username = $email[0];
		$domain = $email[1];
		$len = strlen($username);
		
		if($len === 1) {
			return $username.'@'.$domain;
		}
		if($len === 2 || $len === 3) {
			$username = substr_replace($username,'***',1,1);
			return $username.'@'.$domain;
		}
		if($len === 4) {
			$username = substr_replace($username,'***',2,1);
			return $username.'@'.$domain;
		}
		if($len > 4) {
			$username = substr_replace($username,'***',2,$len-4);			
			return $username.'@'.$domain;				
		}		
	}
	
	/**
	 * 用户名加密
	 * @param string username 
	 * @return string
	 */
	public static function starUsername($username) {
		if(empty($username)) {
			return '';
		}
		$len = strlen($username);
		if($len === 1) {
			return $username;
		}
		if($len === 2 || $len ===3 ){
			$username = substr_replace($username,'***',1,1);
			return $username;
		}
		if($len === 3) {
			$username = substr_replace($username,'***',2,1);
			return $username;
		}
		if($len === 4) {
			$username = substr_replace($username,'***',2,1);
			return $username;
		}
		if($len > 4) {
			$username = substr_replace($username,'***',2,$len-4);
			return $username;
		}		
	}
	
	/**
	 * 手机号加星 186***043
	 * @param string $phone
	 * @return string
	 */
	public static function starPhone($phone) {
		if(empty($phone)) {
			return '';
		}
		return substr_replace($phone,'***',3,5);
	}
}