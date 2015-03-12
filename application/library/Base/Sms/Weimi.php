<?php
/**
 * 发送短信客户端
 */
class Base_Sms_Weimi implements Base_Sms_Interface{

    protected $uid;

    protected $pas;

    protected $api;

    public function __construct(Array $arrArgs){
        $this->uid = isset($arrArgs['uid']) ? $arrArgs['uid'] : null;
        $this->pas = isset($arrArgs['pas']) ? $arrArgs['pas'] : null;
        $this->api = isset($arrArgs['api']) ? $arrArgs['api'] : null;
    }
	
	/**
	 * 发送短信接口1
	 * 按模版id发送数据
     * $arrArgs = array('576581', '5');
     * $tplid   = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
     * $smsRet = Base_Sms::getInstance()->send('18611015043', $tplid, $arrArgs);
	 * @param $mixPhone, 支持一个手机str 和 str数组
     * @param $strTplid, 模版id
	 * @param $arrArgs, 模版的多个参数,不管key,只会按序取value
	 * @param $timing, 定时发送指定时间，如：2012-10-11 18:30:00
	 * 
	 * @return string | false
	 */
    public function send($mixPhone, $strTplid, Array $arrArgs = null, $timing = null) {
        $arrPost = array();
        $arrPost['uid'] = $this->uid;
        $arrPost['pas'] = $this->pas;
        if(is_array($mixPhone)){
            $mixPhone = implode(',',$mixPhone);
        }
        $arrPost['mob'] = $mixPhone;
        $arrPost['cid'] = $strTplid;
        if(!empty($arrArgs)){
            $i = 0;
            foreach($arrArgs as $value){
                $i = $i + 1;
                $arrPost['p'.$i] = $value;
            }
        }
        $arrPost['type'] = 'json';

        $http = Base_Network_Http::instance()->url($this->api)->post($arrPost);
        $ret = $http->exec();
        if($ret){ 
            $ret = json_decode($ret, true);
            if($ret['code'] === 0){
                Base_Log::notice(array(
                    'msg'    => '短信发送成功',
                    'ret'    => $ret,
                    'phone'  => $mixPhone,
                    'tplid'  => $strTplid,
                    'args'   => $arrArgs,
                    'timing' => $timing,
                ));
                return $ret;
            }
        }
        Base_Log::error(array(
            'msg'    => '短信发送不成功',
            'ret'    => $ret,
            'phone'  => $mixPhone, 
            'tplid'  => $strTplid,
            'args'   => $arrArgs,
            'timing' => $timing,
        ));
        return false;
    }

	/**
	 * 发送短信接口2
     * 发送原始数据
	 * $smsRet = Base_Sms::getInstance()->sendRaw('18611015043', '【兴教贷】测试短信fromxjd');
     * var_dump($smsRet);
	 * @param $mixPhone, 支持一个手机str 和 str数组
     * @param $strTplid, 模版id
	 * @param $arrArgs, 模版的多个参数
	 * @param $timing, 定时发送指定时间，如：2012-10-11 18:30:00
	 * 
	 * @return string | false
	 */
    public function sendRaw($mixPhone, $strCtx, $timing = null) {
        $arrPost = array();
        $arrPost['uid'] = $this->uid;
        $arrPost['pas'] = $this->pas;
        if(is_array($mixPhone)){
            $mixPhone = implode(',',$mixPhone);
        }
        $arrPost['mob'] = $mixPhone;
        $arrPost['con'] = $strCtx;
        $arrPost['type'] = 'json';

        $http = Base_Network_Http::instance()->url($this->api)->post($arrPost);
        $ret = $http->exec();
         if($ret){ 
            $ret = json_decode($ret, true);
            if($ret['code'] === 0){
                Base_Log::notice(array('ret'=>$ret, 'phone'=>$mixPhone, 'content'=>$strCtx, 'timing'=>$timing));
                return $ret;
            }
        }
        Base_Log::warn(array('ret'=>$ret, 'phone'=>$mixPhone, 'content'=>$strCtx, 'timing'=>$timing));
        return false;
    }
}
