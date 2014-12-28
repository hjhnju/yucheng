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
        'auth_code_redirect_url'         => '/user/login/third',
        'access_token_redirect_url'      => '/user/login/third',
        'qq'                             => array(
            'host'           => 'https://graph.qq.com',
            'appid'          => '101177204',
            'appkey'         => 'd3aed93ef6e8e009ca30dcd33eb12093',
            'authcode_url'   => '/oauth2.0/authorize?response_type=code&client_id=',
            'acctoken_url'   => '/oauth2.0/token?grant_type=authorization_code&client_id=',
            'openid_url'     => '/oauth2.0/me?access_token=',
            'getinfo_url'    => '/user/get_user_info?format=json&oauth_consumer_key=d3aed93ef6e8e009ca30dcd33eb12093&access_token=',
        ),
        'weibo'                          => array(),
        'weixin'                         => array(),
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
     * @return  User_Object $objUser
     */
    public function login($strAuthCode){
        if(empty($strAuthCode)){
            return false;
        }
        
        $accessToken = $this->getAccessToken($strAuthCode);
        if(empty($accessToken)){
            return false;
        }

        $openid = $this->getOpenidByAccessToken($accessToken);
        if(empty($openid)){
            return false;
        }

        //缓存access_token
        Base_Redis::getInstance()->set(User_Keys::getAccessTokenKey($openid), $accessToken, 30*24*3600);

        //保存openid
        Yaf_Session::getInstance()->set(User_Keys::getOpenidKey(), $openid);

        $objThird = new User_Object_Third();
        $objThird->fetch(array(
            'openid'   => $openid,
            'authtype' => $this->getAuthType($strType))
        );

        if(empty($objThird->userid)){
            return false;
        }   
        $objUser = new User_Object($objThird->userid);

        return $objUser;
    }

     /**
     * 根据$intType类型获取auth code
     * 拼接URL的操作，发给前端放在点击授权处
     * @param string $strType
     */
    public function getAuthCodeUrl($strType){

        $redirectUrl  = Base_Config::getConfig('web')->root . self::$arrConfig['auth_code_redirect_url'];
        $arrData      = self::$arrConfig[$strType];
        $host         = $arrData['host'];
        $randnum      = md5(uniqid(rand(), TRUE));
        Yaf_Session::getInstance()->set("state", $randnum);
        $url          = $arrData['authcode_url'] . $arrData['appid'] . "&redirect_uri="
            . $redirectUrl . "&scope=get_user_info&state=" . $randnum;
        if(empty($host)||empty($url))  {
            return false;
        }
        return $host . $url;
    }
    
    /**
     * 获取access token
     */
    protected function getAccessToken($strAuthCode){
        $strType      = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());     
        $redirect_url = Base_Config::getConfig('web')->root . self::$arrConfig['access_token_redirect_url'];
        $arrData      = self::$arrConfig[$strType];
        $host         = $arrData['host'];
        $url          = $arrData['acctoken_url'].$arrData['appid']."&client_secret=".$arrData['appkey']."&code=$strAuthCode"."&redirect_uri=".$redirect_url;
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
    protected function getOpenidByAccessToken($accessToken){
        $strType      = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
        $redirectUrl  = self::$arrConfig['access_token_url'];
        $arrData      = self::$arrConfig[$strType];
        $host         = $arrData['host'];
        $redirectUrl  = $arrData['openid_url'] . $accessToken;
        $post         = Base_Network_Http::instance()->url($host, $redirectUrl);
        $response     = $post->exec();
        if (strpos($response, "callback") !== false){
            return 0;
        }
        $user = json_decode($response);
        if (isset($user->error)){
            return 0;
        }
        return $user->openid;
    }
    
    /**
     * 获取第三方站点信息, 如果为空返回NULL,否则返回user的json对象
     * 失败返回空串
     */
    protected function getUserThirdInfo($accessToken, $openid){
        $strType      = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
        
        $arrData      = self::$arrConfig[$strType];
        $host         = $arrData['host'];
        $redirect_url = $arrData['getinfo_url'] . $accessToken . 'openid=' . $openid;
        $post         = Base_Network_Http::instance()->url($host,$redirect_url);
        $response     = $post->exec();
        $user         = json_decode($response);
        if (!isset($user->nickname)){
            return null;
        }
        return $user;
    }
    
    /**
     * 检查第三方的绑定状态
     * @param unknown $openid
     * @param unknown $intType
     * 绑定返回true,没绑定返回false
     */
    protected function getBindUser($openid, $strType){
        $objThird = new User_Object_Third();
        $objThird->fetch(array('openid'=>$openid,
            'authtype'=>$this->getAuthType($strType)));
        if(empty($objThird->userid)){
            return false;
        }   
        return new User_Object($objThird->userid);
    }
    
    /**
     * 设置第三方绑定状态
     * @param unknown $openid
     * @param unknown $type
     * @param unknown $strName
     * @param unknown $strPasswd
     * 成功返回true,失败返回false
     */
    public function setBind($openid, $type,$strName,$strPasswd){
        $objLogin         = new User_Object_Login();
        $objLogin->fetch(array($this->checkType($strName)=>$strName,'passwd'=>md5($strPasswd)));
        if(empty($objLogin->userid)){
            return false;
        }
        $this->setLogin($objLogin);
        $objThird           = new User_Object_Third();
        $objThird->authtype = $this->getAuthType($type);
        $objLogin->userid   = $objLogin->userid;
        $objThird->nickname = $this->getUserThirdInfo($openid);
        $objThird->openid   = $openid;
        $ret = $objThird->save();
        if($ret){
            return true;
        }
        return false;
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