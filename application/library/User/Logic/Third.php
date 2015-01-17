<?php
/**
 * 第三方登录Logic层
 * @author hejunhua
 */
class User_Logic_Third {

    //第三方登陆类型
    const TYPE_QQ     = 1;
    const TYPE_WEIBO  = 2;
    const TYPE_WEIXIN = 3;

    //第三方登录需要的配置信息
    protected static $arrConfig = array(
        'auth_code_redirect_url'    => '/user/login/third',
        'access_token_redirect_url' => '/user/login/third',
        'qq'               => array(
            'host'         => 'https://graph.qq.com',
            'appid'        => '101180983',
            'appkey'       => 'c8cbea93631b75d55d4e5423aeec89f1',
            'authcode_url' => '/oauth2.0/authorize?response_type=code&client_id=',
            'acctoken_url' => '/oauth2.0/token?grant_type=authorization_code&client_id=',
            'openid_url'   => '/oauth2.0/me?access_token=',
            'getinfo_url'  => '/user/get_user_info',
        ),
        'weibo'   => array(),
        'weixin'  => array(),
    );
    
    private $userid;
    //列表保存User_Object_Third的信息
    private $objs = array();

    public function __construct($userid = null){
        if(!empty($userid)){
            $this->userid = $userid;
            $list         = new User_List_Third();
            $filters      = array('userid' => $this->userid);
            $list->setFilter($filters);
            foreach ($list->toArray() as $value) {
                $this->objs[$value['authtype']] = $value;
            }
        }
    }
    
    /**
     * 获取OpenID, 也可以用于判断是否绑定某个类型
     * @param  $authtype 'qq' or 'weibo' or 'weixin'
     * @return $openid || false
     */ 
    public function getOpenid($authtype){
        if(!$this->userid){
            return false;
        }
        $arrInfo = $this->objs[$this->getType($authtype)];
        $openid  = isset($arrInfo['openid']) ? $arrInfo['openid'] : false;
        return $openid;
    }

    /**
     * 获取Nickname
     * @param  $authtype 'qq' or 'weibo' or 'weixin'
     * @return $nickname || false
     */
    public function getNickname($authtype){
        if(!$this->userid){
            $arrInfo  = $this->objs[$this->getType($authtype)];
            $nickname = isset($arrInfo['nickname']) ? $arrInfo['nickname'] : false;
        } else {

        }
        return $nickname;
    }

    /**
     * 第三方登陆
     * @param   $strAuthCode 授权code
     * @return  $openid || false
     */
    public function login($authtype, $strAuthCode){
        if(empty($authtype) || empty($strAuthCode)){
            return false;
        }
        $accessToken = $this->getAccessToken($authtype, $strAuthCode);
        if(empty($accessToken)){
            Base_Log::warn(array(
                'msg' => 'cannot get access_token',
                'code'=> $strAuthCode,
            ));
            return false;
        }

        $openid = $this->getOpenidByAccessToken($authtype, $accessToken);
        if(empty($openid)){
            Base_Log::warn(array(
                'msg' => 'cannot get openid',
                'code'=> $accessToken,
            ));
            return false;
        }
        //缓存access_token
        Base_Redis::getInstance()->set(User_Keys::getAccessTokenKey($authtype, $openid), $accessToken, 30*24*3600);

        return $openid;
    }

    /**
     * 获取绑定的userid
     * @param string $openid
     * @param string $authtype, qq|weibo|weixin
     * @return $userid || false
     */
    public function getBindUserid($openid, $authtype){
        $objThird = new User_Object_Third();
        $objThird->fetch(array(
            'openid'   => $openid,
            'authtype' => $this->getAuthType($authtype),
        ));
        return isset($objThird->userid) ? $objThird->userid : false;
    }

    public function getUserNickname($openid, $authtype){
        $accessToken = Base_Redis::getInstance()->get(User_Keys::getAccessTokenKey($authtype, $openid));
        if(empty($accessToken)){
            Base_Log::warn(array(
                'msg'    => 'access_token not exists in redis',
                'openid' => $openid,
            ));
            return false;
        }
        $objThird = $this->getUserInfo($accessToken, $openid, $authtype);
        return isset($objThird->nickname) ? $objThird->nickname : false;
    }


     /**
     * 根据$intType类型获取auth code
     * 拼接URL的操作，发给前端放在点击授权处
     * @param string $strType
     */
    public function getAuthCodeUrl($strType){

        $redirectUrl  = Base_Config::getConfig('web')->root . self::$arrConfig['auth_code_redirect_url'];
        $arrData = self::$arrConfig[$strType];
        $host    = $arrData['host'];
        $randnum = md5(uniqid(rand(), TRUE));
        Yaf_Session::getInstance()->set("state", $randnum);
        $url     = $arrData['authcode_url'] . $arrData['appid'] . "&redirect_uri=" .$redirectUrl . "&scope=get_user_info&state=" . $randnum;
        if(empty($host)||empty($url))  {
            return false;
        }
        return $host . $url;
    }
        
    /**
     * 第三方账号绑定
     * @param unknown $openid
     * @param unknown $authtype
     * @param unknown $userid
     * 成功返回true,失败返回false
     */
    public function binding($userid, $openid, $authtype){

        $objThird           = new User_Object_Third();
        $objThird->userid   = intval($userid); 
        $objThird->authtype = $this->getAuthType($authtype);
        $objThird->openid   = $openid;

        $accessToken = Base_Redis::getInstance()->get(User_Keys::getAccessTokenKey($authtype, $openid));
        if(empty($accessToken)){
            Base_Log::warn(array('msg'=>'No access_token in redis',
                'userid'=>$userid, 'openid'=>$openid, 'authtype'=>$authtype));
            return false;
        }
        $thirdUser   = $this->getUserInfo($accessToken, $openid, $authtype);
        $objThird->nickname = $thirdUser->nickname;
        $ret = $objThird->save();
        if(!$ret){
            Base_Log::warn(array('msg'=>'save objThrid failed',
                'userid'=>$userid, 'openid'=>$openid, 'authtype'=>$authtype));
            return false;
        }
        return true;
    }
   
    /**
     * 获取access token
     */
    private function getAccessToken($strType, $strAuthCode){
        $redirectUri = Base_Config::getConfig('web')->root . self::$arrConfig['access_token_redirect_url'];
        $arrData     = self::$arrConfig[$strType];
        $host        = $arrData['host'];
        $url         = $arrData['acctoken_url'].$arrData['appid']."&client_secret=".$arrData['appkey']."&code=$strAuthCode"."&redirect_uri=".$redirectUri;
        $post         = Base_Network_Http::instance()->url($host, $url);

        $accessToken  = '';
        try{
            $response = $post->exec();
            if (strpos($response, "callback") !== false){
                $lpos     = strpos($response, "(");
                $rpos     = strrpos($response, ")");
                $response = substr($response, $lpos + 1, $rpos - $lpos -1);
                $msg      = json_decode($response);
                if (isset($msg->error)){
                    return $msg->error;
                }
            }
            $params = array();
            parse_str($response, $params);
            $accessToken = $params['access_token'];
        } catch (Exception $ex){
            Base_Log::warn($ex->getMessage());
        }
        return $accessToken;
    }
    
    /**
     * 获取open id
     */
    private function getOpenidByAccessToken($strType, $accessToken){
        $arrData   = self::$arrConfig[$strType];
        $host      = $arrData['host'];
        $openidUrl = $arrData['openid_url'] . $accessToken;
        $post      = Base_Network_Http::instance()->url($host, $openidUrl);
        $response  = $post->exec();
        //trim callback for jsonp
        $response  = preg_replace("/[^{]*({.*})[^}]*/", "$1", $response);
        $resp = json_decode($response);
        
        Base_Log::debug(array('resp'=>$resp));
        if (isset($resp->error)){
            return false;
        }
        return $resp->openid;
    }
    
    /**
     * 获取第三方站点信息, 如果为空返回NULL,否则返回user的json对象
     * 失败返回空串
     */
    private function getUserInfo($accessToken, $openid, $authtype){

        //redis先取，否则api取
        $arrData = self::$arrConfig[$authtype];
        $host    = $arrData['host'];
        $infoUrl = $arrData['getinfo_url'] . '?' . http_build_query(array(
            'oauth_consumer_key' => $arrData['appid'],
            'access_token' => $accessToken,
            'openid' => $openid, 
        ));
        $post     = Base_Network_Http::instance()->url($host, $infoUrl);
        $response = $post->exec();
        $resp     = json_decode($response);
        Base_Log::debug(array(
            'response' => $response,
            'infourl' => $infoUrl,
        ));
        if (!isset($resp->nickname)){
            return null;
        }
        return $resp;
    }
      
    /**
     * @param  $authtype = 'weibo', 'qq', 'weixin'
     * @return intType
     */
    private function getAuthType($authtype) {
        $authtype = strtolower($authtype);
        $type     = null;
        switch ($authtype) {
            case 'qq':
                $type = self::TYPE_QQ;
                break;
            case 'weibo':
                $type = self::TYPE_WEIBO;
                break;
            case 'weixin':
                $type = self::TYPE_WEIXIN;
                break;
            default:
                # code...
                break;
        }
        return $type;
    }
  
}
