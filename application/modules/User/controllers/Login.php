<?php
/**
 * 用户登录相关操作
 */
class LoginController extends Base_Controller_Page{
    
    protected $uid = null;
    protected $type = null;
    const REDIS_VALID_TIME = 2592000;
    
    public function init(){
        parent::init();
        $this->loginLogic = new User_Logic_Login();
    }
    
    /**
     * 标准登录过程
     * 状态返回0表示登录成功
     */
    public function loginAction(){
       $strName = trim($_REQUEST['name']);
       $strPasswd = trim($_REQUEST['passwd']);
       $type = $this->loginLogic->checkType($strName);
       if('error' == $type) {
           return $this->ajaxError(User_RetCode::USER_NAME_ERROR,User_RetCode::getMsg(User_RetCode::USER_NAME_ERROR));
       }
       
       $retUid = $this->loginLogic->login($type,$strName,$strPasswd);
       if(User_RetCode::INVALID_USER != $retUid) {
           Yaf_Session::getInstance()->set("LOGIN",$retUid);
           $this->uid = $retUid; 
           return $this->ajax();
       }
       $intWrongTimes = Yaf_Session::getInstance()->get("LOGIN_WRONG");
       if(empty($intWrongTimes)){
           Yaf_Session::getInstance()->set("LOGIN_WRONG",1);
       }else{
           Yaf_Session::getInstance()->set("LOGIN_WRONG",$intWrongTimes+=1);
       }
       if($intWrongTimes >= 3) {
           $data = User_Api::getAuthImage();
           return $this->ajaxError(User_RetCode::NEED_PICTURE,'',array('url'=>$data));
       }
       return $this->ajaxError(User_RetCode::USER_NAME_OR_PASSWD_ERROR,User_RetCode::getMsg(User_RetCode::USER_NAME_OR_PASSWD_ERROR));
    }
    
    /**
     * 根据用户的登录状态，获取用户信息
     * 若用户未登录，返回状态码504表示用户未登录
     */
    /*public function getUserInfoAction(){
        if(Yaf_Session::getInstance()->has("LOGIN")){
            $this->uid = Yaf_Session::getInstance()->get("LOGIN");
            $data = $this->loginLogic->getUserInfo(array($this->uid));
        }
        if(!empty($data)){
            return $this->ajax($data);
        }
        return $this->ajaxError(User_RetCode::DATA_NULL);
    }*/
    

    /**
     * 第三方登录过程,用户点登录后，先判断redis中是否有该用户的
     * access token,如果没有，返回一个授权页面URL供前端放在授权按钮后。
     * 如果有则查找有无此用户绑定状态，如果有直接返回登录成功页面，并将用户设置为
     * 登录状态；如果没有绑定则让用户选择绑定或注册。
     * 
     */
    public function thirdLoginAction(){
        $key = $_COOKIE['access_key'];
        $access_token = Base_Redis::getInstance()->get("access_token".$this->type.$key);
        if(!empty($access_token)){
            $openid = $this->getAccessTokenAction($access_token);
            Yaf_Session::getInstance()->set("openid",$openid);
            Yaf_Session::getInstance()->set("idtype",$intType);
            $ret = $this->loginLogic->checkBind($openid, $intType); //$ret=0表示已经绑定，$ret=1表示未绑定
            if($ret == User_RetCode::BOUND){
                return $this->ajax();         //用户登录成功并已经绑定账号
            }else{
                return $this->ajax('','',User_RetCode::UNBOUND);  //用户未绑定账号
            }
        }else{
            $ret = $this->loginLogic->getAuthCode($intType);
            return $this->ajax($ret);
        }
        return $this->ajaxError(User_RetCode::UNKNOWN_ERROR);
    }
    
    /**
     * 获取auth code的请求URL
     */
    public function getAuthcodeUrlAction(){
        $intType = trim($_REQUEST['type']);
        return $this->loginLogic->getAuthCode($intType);
    }
    
    /**
     * 获取auth code,由前端发起这个请求，请求URL已经通过
     * getAuthcodeUrlAction（）传给前端
     */
    public function getAuthCodeAction(){
        $state = trim($_REQUEST['state']);
        $strAuthCode = trim($_REQUEST['code']);
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['access_token_url'];
        $arrData = $arrData[$this->type];
        $host = $arrData['host'];
        $url = $arrData['acctoken_url'].$arrData['appid']."&client_secret=".$arrData['appkey']."&code=$strAuthCode"."&redirect_uri=".$redirect_url;
        $post = Base_Network_Http::instance()->url($host,$url);
        $post->exec();
    }
    
    /**
     * 获取access token后再用它获取open id，同时将access token存cookie
     */
    public function getAccessTokenAction($access_token=null){
        if(isset($access_token)){
            $strAccessToken = $access_token;
        }else{
            $strAccessToken = trim($_REQUEST['access_token']);
        }
        $key = md5(microtime()); 
        setcookie('access_key',$key,self::REDIS_VALID_TIME);
        Base_Redis::getInstance()->set("access_token".$this->type.$key,$strAccessToken);
        $arrData =  Base_Config::getConfig('login');
        $arrData = $arrData[$this->type];
        $host = $arrData['host'];
        $url = $arrData['openid_url'].$strAccessToken;
        $post = Base_Network_Http::instance()->url($host,$url);
        $ret = $post->exec();
        if(strpos($str, "callback") !== false){
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
        }
        $user = json_decode($str);
        if (isset($user->error)){
            $this->ajaxError(User_RetCode::UNKNOWN_ERROR);
        }
        $ret = $this->loginLogic->checkBind($user->openid, $this->type);
        if($ret == User_RetCode::BOUND){
            return $this->ajax();         //用户登录成功并已经绑定账号
        }else{
            return $this->ajax('','',User_RetCode::UNBOUND);  //用户未绑定账号
        }
    }
    
    /**
     * 对第三方账号进行绑定
     * 0表示绑定成功，其它绑定出错
     */
    public function setBindAction(){
        $strName = trim($_REQUEST['name']);
        $strPasswd = trim($_REQUEST['passwd']);
        $opeid = Yaf_Session::getInstance()->get("openid");
        $type =  Yaf_Session::getInstance()->get("idtype");
        $ret = $this->loginLogic->setBind($openid, $intType,$strName,$strPasswd);
        if(User_RetCode::BOUND == $ret){
            return $this->ajax();
        }
        return $this->ajaxError(User_RetCode::UNBOUND);
    }
}
