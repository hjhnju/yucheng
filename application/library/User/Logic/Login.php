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
        Yaf_Session::getInstance()->set("third_login_type",$strType);
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['auth_code_url'];
        $arrData = $arrData[$strType];
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
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['access_token_url'];
        $arrData = $arrData[$strType];
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
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['access_token_url'];
        $arrData = $arrData[$strType];
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
     * 获取第三方站点信息,目前只返回nickname
     */
    public function getUserThirdInfo($openid){
        $strType = Yaf_Session::getInstance()->get("third_login_type");
        $key = $_COOKIE['access_key'];
        $access_token = Base_Redis::getInstance()->get("access_token".$strType.$key);
        $strType = Yaf_Session::getInstance()->get("third_login_type");
        $arrData =  Base_Config::getConfig('login');
        $arrData = $arrData[$strType];
        $host = $arrData['host'];
        $redirect_url = $arrData['getinfo_url'].$access_token.'openid='.$openid;
        $post = Base_Network_Http::instance()->url($host,$redirect_url);
        $response = $post->exec();
        $user = json_decode($response);
        if (!isset($user->nickname)){
            return $this->ajaxError();
        }
        return $user->nickname;
    }
    
    /**
     * 检查第三方的绑定状态
     * @param unknown $openid
     * @param unknown $intType
     */
    public function checkBind($openid,$strType){
        $objThird = new User_Object_Third();
        $objThird->fetch(array('openid'=>$openid,'authtype'=>$this->getAuthType($strType)));
        if(empty($objThird->userid)){
            return User_RetCode::UNBOUND;
        }   
        return User_RetCode::BOUND;
    }
    
    /**
     * 设置第三方绑定状态
     * @param unknown $openid
     * @param unknown $type
     * @param unknown $strName
     * @param unknown $strPasswd
     */
    public function setBind($openid, $type,$strName,$strPasswd){
        $objLogin         = new User_Object_Login();
        $objLogin->fetch(array($this->checkType($strName)=>$strName,'passwd'=>md5($strPasswd)));
        if(empty($objLogin->userid)){
            return User_RetCode::UNBOUND;
        }
        $this->setLogin($objLogin);
        $objThird     = new User_Object_Third();
        $objThird->authtype = $this->getAuthType($type);
        $objLogin->userid = $objLogin->userid;
        $objThird->nickname = $this->getUserThirdInfo($openid);
        $objThird->openid = $openid;
        $ret = $objThird->save();
        if($ret){
            return User_RetCode::BOUND;
        }
        return User_RetCode::UNBOUND;
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
        return $arr[$strType];
    }
  
}