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
        if(empty($mixPhone)){
            Base_Log::warn(array(
                'msg'    => '手机号为空',
                'phone'  => $mixPhone, 
                'tplid'  => $strTplid,
                'args'   => $arrArgs,
                'timing' => $timing,
            ));
            return false;
        }
        //检查当天是否还可以继续对该手机号发送验证码
        if(!$this->checkRecord($mixPhone)) {
            Base_Log::error(array(
                'msg'    => '该号码当天不能在继续发送短信',
                'phone'  => $mixPhone, 
                'tplid'  => $strTplid,
                'args'   => $arrArgs,
                'timing' => $timing,
            ));
            return false;
        }
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
        //发送完短信后，要记录住该手机号的发送状态信息,不管成功或者失败
        $this->sendRecord($mixPhone);
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

    /**
     * 记录该手机的请求次数，不管成功发送还是失败发送都会记录
     * @param  string $phone 手机号码
     * @return [type] null
     */
    public function sendRecord($phone=''){
        $redis  = Base_Redis::getInstance();
        $key    = Base_Keys::getPhoneLimitKey($phone); 
        //每次记录都要给总数加1
        $redis->hincrby($key, 'count', 1);
        //将操作时间设置为当前时间
        $redis->hset($key, 'time', time());

        //判断是否要写入过期时间，如果有过期时间，就不要在重复写入
        $check = intval($redis->ttl($key));
        if($check < 0) {
            //获取时间间隔
            $interval = mktime(23, 59, 59, date('m', time()), date('d', time()), date('Y', time())) - time();
            //第一次写入的时候需要制定过期时间
            $redis->expire($key, $interval);
        }  
    }

    /**
     * 检查该手机号码是否还有继续请求发送的权利
     * @param  string $phone 手机号码
     * @return [type] true | false
     */
    public function checkRecord($phone=''){
        $redis  = Base_Redis::getInstance();
        $key    = Base_Keys::getPhoneLimitKey($phone); 
        //获取限制条件
        $config = Base_Config::getConfig('sms', CONF_PATH . '/sms.ini');

        //获取当天的总数
        $count  = intval($redis->hget($key, 'count'));
        $time   = intval($redis->hget($key, 'time'));
        //检查是否可以继续写入,如果总数 - 已发数大于0，并且当前时间-上次发送的最后时间大于时间间隔，那么可以写入
        if(($config['limit']['count']-$count) > 0 && (time()-$time) > $config['limit']['time'] ) {
            return true;
        }
        return false;
    }
}
