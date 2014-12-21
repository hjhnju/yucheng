<?php
/**
 * 注册Logic层
 * @author hejunhua
 */
class User_Logic_Login{

    const SESSION_LOGIN_USER = "login_user";

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
        $arrType = array('qq' => 1,
                         'weibo' => 2,
        );
        $intType = $arrType[$strType];
        Yaf_Session::getInstance()->set("third_login_type",$intType);
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['auth_code_url'];
        $arrData = $arrData[$intType];
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
    public function getAccessToken($strAuthCode,$intType){
        $intType = Yaf_Session::getInstance()->get("third_login_type");
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['access_token_url'];
        $arrData = $arrData[$intType];
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
        $intType = Yaf_Session::getInstance()->get("third_login_type");
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['access_token_url'];
        $arrData = $arrData[$intType];
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
     * 获取第三方站点信息
     */
    public function getUserThirdInfo(){
        
    }
    
    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function checkBind($openid,$intType){
        $ret = $this->modLogin->checkBing($openid,$intType);
        return $ret;
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
        $objLogin->name   = $strName;
        $objLogin->passwd = md5($strPasswd); 
        $objLogin->lastip = Base_Util_Ip::getClientIp();
        $ret              = $objLogin->save();
        //todo:纪录登陆纪录
        return $ret;
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
  
}