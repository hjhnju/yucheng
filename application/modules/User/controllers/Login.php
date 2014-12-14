<?php
/**
 * 用户注册相关操作
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
     * 返回0表示登录出错，否则返回非0的正确用户id
     */
    public function loginAction(){
       $strName = trim($_REQUEST['name']);
       $strPasswd = trim($_REQUEST['passwd']);
       $retUid = $this->loginLogic->login($strName,$strPasswd);
       if(!empty($retUid)) {
           Yaf_Session::getInstance()->set("LOGIN",$retUid);
           $this->uid = $retUid; 
           return $this->ajax($this->uid);
       }
       return $this->ajaxError(User_RetCode::UNKNOWN_ERROR);
    }
    
    /**
     * 根据用户的登录状态，获取用户信息
     * 若用户未登录，返回状态码504表示用户未登录
     */
    public function getUserInfoAction(){
        if(Yaf_Session::getInstance()->has("LOGIN")){
            $this->uid = Yaf_Session::getInstance()->get("LOGIN");
            $data = $this->loginLogic->getUserInfo(array($this->uid));
        }
        if(!empty($data)){
            return $this->ajax($data);
        }
        return $this->ajaxError(User_RetCode::DATA_NULL);
    }
    

    /**
     * 第三方登录过程,用户点登录后，先判断cookie中是否有该用户的
     * access token,如果有则直接返回登录成功页面，并将用户设置为
     * 登录状态；否则，返回一个授权页面URL供前端放在授权按钮后
     * 
     * access_token,不能存于session中，不能存于
     */
    public function thirdLoginAction(){
        $intType = trim($_REQUEST['type']);
        $key = $_COOKIE['access_key'];
        $access_token = Base_Redis::getInstance()->get("access_token".$this->type.$key);
        if(!empty($access_token)){
            $openid = $this->getAccessTokenAction($access_token);
            Yaf_Session::getInstance()->set("openid",$openid);
            Yaf_Session::getInstance()->set("idtype",$intType);
            $ret = $this->loginLogic->thridLogin($openid, $intType);
            return $this->ajax();
        }else{
            $ret = $this->loginLogic->getAuthCode($intType);
            return $this->ajax($ret);
        }
        return $this->ajaxError(User_RetCode::UNKNOWN_ERROR);
    }
    
    
    
    /**
     * 获取auth code,由前端发起这个请求，请求URL已经通过
     * thirdLoginAction（）传给前端
     */
    public function getAuthCodeAction(){
        $state = trim($_REQUEST['state']);
        if($state == Yaf_Session::getInstance()->get("state")){
            $strAuthCode = trim($_REQUEST['code']);
            $arrData =  Base_Config::getConfig('login');
            $redirect_url = $arrData['access_token_url'];
            $arrData = $arrData[$this->type];
            $host = $arrData['host'];
            $url = $arrData['acctoken_url'].$arrData['appid']."&client_secret=".$arrData['appkey']."&code=$strAuthCode"."&redirect_uri=".$redirect_url;
            $post = Base_Network_Http::instance()->url($host,$url);
            $post->exec();
        }else{
            $this->ajaxError("csrf");
        }
    }
    
    /**
     * 获取access token后再用它获取open id，同时将access token存cookie
     */
    public function getAccessTokenAction(){
        $strAccessToken = trim($_REQUEST['access_token']);
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
        $ret = $this->loginLogic->thridLogin($user->openid, $this->type);
        $this->ajax();
    }
}
