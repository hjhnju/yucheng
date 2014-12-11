<?php
/**
 * 发送短信客户端
 */
interface Base_Sms_Interface {
	
	/**
	 * 发送短信接口1
	 * 按模版id发送数据
     * TODO:添加支持发送手机数的限制
	 * @param $mixPhone, 支持一个手机str 和 str数组
     * @param $strTplid, 模版id
	 * @param $arrArgs, 模版的多个参数
	 * @param $timing, 定时发送指定时间，如：2012-10-11 18:30:00
	 * 
	 * @return string | false
	 */
	public function send($mixPhone, $strTplid, Array $arrArgs = null, $timing = null); 
	/**
	 * 发送短信接口2
     * 发送原始数据
	 * 
	 * @param $mixPhone, 支持一个手机str 和 str数组
     * @param $strTplid, 模版id
	 * @param $arrArgs, 模版的多个参数
	 * @param $timing, 定时发送指定时间，如：2012-10-11 18:30:00
	 * 
	 * @return string | false
	 */

    public function sendRaw($mixPhone, $strCtx, $timing = null);
}
