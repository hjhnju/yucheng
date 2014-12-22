<?php
/**
 * 注册Logic层
 * @author hejunhua
 */
class User_Logic_Login{

    const SESSION_LOGIN_USER = "login_user";
    
    //第三方登录需要的配置信息
    protected $third_login_array = array(
    	'auth_code_redirect_url'         => 'http://www.xingjiaodai.cn:8301/User/Loginapi/thirdLogin',
        'access_token_redirect_url'      => 'http://www.xingjiaodai.cn:8301/User/Loginapi/thirdLogin',
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

    public function __construct(){
    }
    
    /**
     * 判断用户的登录状态
     * @return object || null
     */
    public function checkLogin(){
        $objUser = Yaf_Session::getInstance()->get(self::SESSION_LOGIN_USER);
        if($objUser){
            return $objUser;
        }
       return null;
    }

    /**
     * 设置用户的登陆状态
     * @return boolean
     */ 
    protected function setLogin($objUser){
        if($objUser){
            Yaf_Session::getInstance()->set(self::SESSION_LOGIN_USER, $objUser);
            return true;
        }
        return false;
    }
     /**
     * 根据$intType类型获取auth code
     * 拼接URL的操作，发给前端放在点击授权处
     * @param int $intType,1表示qq,2表示微博
     */
    public function getAuthCodeUrl($strType){
        Yaf_Session::getInstance()->set("third_login_type",$strType);
        $redirect_url = $this->third_login_array['auth_code_redirect_url'];
        $arrData = $this->third_login_array[$strType];
        $host = $arrData['host'];
        $randnum = md5(uniqid(rand(), TRUE));
        Yaf_Session::getInstance()->set("state",$randnum);
        $url = $arrData['authcode_url'].$arrData['appid']."&redirect_uri=".$redirect_url."&scope=get_user_info&state=".$randnum;
        if(empty($host)||empty($url))  {
            return User_RetCode::INVALID_URL;
        }
        return $host.$url;
    }
    
    /**
     * 获取access token
     */
    public function getAccessToken($strAuthCode){
        $strType = Yaf_Session::getInstance()->get("third_login_type");        
        $redirect_url = $this->third_login_array['access_token_redirect_url'];
        $arrData = $this->third_login_array[$strType];
        $host = $arrData['host'];
        $url = $arrData['acctoken_url'].$arrData['appid']."&client_secret=".$arrData['appkey']."&code=$strAuthCode"."&redirect_uri=".$redirect_url;
        $post = Base_Network_Http::instance()->url($host,$url);
        $response = $post->exec();
        if (strpos($response, "callback") !== false){
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error)){
                return $msg->error;
            }
        }
        $params = array();
        parse_str($response, $params);
        return $params['access_token'];
    }
    
    /**
     * 获取open id
     */
    public function getOpenid($access_token){
        $strType = Yaf_Session::getInstance()->get("third_login_type");
        $redirect_url = $this->third_login_array['access_token_url'];
        $arrData = $this->third_login_array[$strType];
        $host = $arrData['host'];
        $redirect_url = $arrData['openid_url'].$access_token;
        $post = Base_Network_Http::instance()->url($host,$redirect_url);
        $response = $post->exec();
        if (strpos($response, "callback") !== false){
            return $this->ajaxError();
        }
        $user = json_decode($response);
        if (isset($user->error)){
            return $this->ajaxError();
        }
        return $user->openid;
    }
    
    /**
     * 获取第三方站点信息, 如果为空返回NULL,否则返回user的json对象
     * 失败返回空串
     */
    public function getUserThirdInfo($openid){
        $strType = Yaf_Session::getInstance()->get("third_login_type");
        if(!isset($_COOKIE['access_key'])){
           return NULL; 
        }
        $key = $_COOKIE['access_key'];
        $access_token = Base_Redis::getInstance()->get("access_token".$strType.$key);
        $arrData = $this->third_login_array[$strType];
        $host = $arrData['host'];
        $redirect_url = $arrData['getinfo_url'].$access_token.'openid='.$openid;
        $post = Base_Network_Http::instance()->url($host,$redirect_url);
        $response = $post->exec();
        $user = json_decode($response);
        if (!isset($user->nickname)){
            return NULL;
        }
        return $user;
    }
    
    /**
     * 检查第三方的绑定状态
     * @param unknown $openid
     * @param unknown $intType
     * 绑定返回true,没绑定返回false
     */
    public function checkBind($openid,$strType){
        $objThird = new User_Object_Third();
        $objThird->fetch(array('openid'=>$openid,'authtype'=>$this->getAuthType($strType)));
        if(empty($objThird->userid)){
            return false;
        }   
        return true;
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
        $objThird     = new User_Object_Third();
        $objThird->authtype = $this->getAuthType($type);
        $objLogin->userid = $objLogin->userid;
        $objThird->nickname = $this->getUserThirdInfo($openid);
        $objThird->openid = $openid;
        $ret = $objThird->save();
        if($ret){
            return true;
        }
        return false;
    }

    /**
     * 获取用户对象
     * @return User_Object
     */
    public function getUserObject($userid) {
        $objUser = new User_Object($userid);
        return $objUser;
    }
    
    /**
     * @param type $type, 用户类型？ //TODO:
     * @param string $strName,用户名
     * @param string $strPasswd,用户密码
     * @return boolean
     */

    public function login($type, $strName, $strPasswd){
        $objLogin         = new User_Object_Login();
        $objRecord        = new User_Object_Record();
        $objRecord->ip    = Base_Util_Ip::getClientIp();
        $objLogin->fetch(array($type=>$strName,'passwd'=>md5($strPasswd)));
        if(empty($objLogin->userid)){
            $objRecord->status = User_RetCode::LOGIN_WRONG;
            $objRecord->save();
            return User_RetCode::INVALID_USER;
        }     
        $objRecord->userid = $objLogin->userid;
        $objRecord->status = User_RetCode::LOGIN_OK;
        $objRecord->save();
        $objLogin->lastip = Base_Util_Ip::getClientIp();
        $objLogin->save();
        $this->setLogin($objLogin);
        return $objLogin->userid;
    }
    
    public function checkType($val){
        if(!User_Api::checkReg('name',$val)){
            return 'name';
        }elseif(!User_Api::checkReg('email',$val)){
            return 'email';
        }elseif(!User_Api::checkReg('phone',$val)){
            return 'phone';
        }else{
            return 'error';
        }
    }
    
    /**
     * 将认证类型从qq web转化成1,2
     * @param unknown $strType
     */
    public function getAuthType($strType){
        $arr = array(
            'qq'    => 1,
            'weibo' => 2,);
        if(isset($arr[$strType])){
            return $arr[$strType];
        }
        return '';
    }
  
}