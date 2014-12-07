<?php
/**
 * 发送短信客户端
 */
class Base_Util_Sms {

	protected static $_arrConf = array();
	
	/**
	 * 配置信息
	 * 
	 * @param array
	 * 		host 	=> array | string
	 * 		path 	=> string
	 * 		username=> string	不能包含':'
	 * 		password=> string	不能包含':'
	 * 		timeout => int
	 * 		retry	=> int
	 */	
	static function setOption(Array $arrConf)
	{
		self::$_arrConf = $arrConf;
	}
	
	/**
	 * 发送短信
	 * 
	 * @param $strPhone
	 * @param $strContent
	 * @param $intPriority
	 * 
	 * @return string | false
	 */
	static function send($strPhone, $strContent, $intPriority = null)
	{
		$mixUrl 	= self::$_arrConf['host'];
		$strPath 	= self::$_arrConf['path'];
		$intAppid   = self::$_arrConf['appid'];
		$intTimeout = isset(self::$_arrConf['timeout']) ? self::$_arrConf['timeout'] : 1;
		$intRetry 	= isset(self::$_arrConf['retry']) ? self::$_arrConf['retry'] : 1;
		
		if(! $mixUrl || ! $strPath){
			return false;
		}
		
		$arrParam = array(
			'appid'		=> $intAppid,
			'phone' 	=> $strPhone,
			'content'	=> $strContent,
			'priority'	=> $intPriority
		);

		$objHttp=Base_Util_Http::instance()->url($mixUrl, $strPath)->post($arrParam);
		if(isset(self::$_arrConf['username']) && isset(self::$_arrConf['password'])){
			$objHttp->auth(self::$_arrConf['username'], self::$_arrConf['password']);
		}

		$ret = $objHttp->timeout($intTimeout)->exec($intRetry, true);
		$ret = json_decode($ret,true);
		if ($ret['status'] == 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
