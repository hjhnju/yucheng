<?php
/**
 * 用户注册相关操作
 */
class LoginController extends Base_Controller_Page{
    
    protected $uid = null;
    protected $type = null;
    
    public function init(){
        parent::init();
        $this->loginLogic = new User_Logic_Login();
    }
    
    /**
     * 标准登录过程
     * success=>0表示成功,success=>1表示失败
     */
    public function loginAction(){
       $strName = trim($_REQUEST['name']);
       $strPasswd = trim($_REQUEST['passwd']);
       $retUid = $this->loginLogic->login($strName,$strPasswd);
       if(!empty($retUid)) {
           Yaf_Session::getInstance()->set("LOGIN",$retUid);
           $this->uid = $retUid; 
           return $this->ajax(User_RetCode::getMsg(User_RetCode::SUCCESS));
       }
       return $this->ajaxError(User_RetCode::UNKNOWN_ERROR,User_RetCode::getMsg(User_RetCode::UNKNOWN_ERROR));
    }
    
    /**
     * 第三方登录过程
     * 先从cookie中拿access token,没有的话则调用一遍接口取
     */
    public function thirdLoginAction(){
        $intType = trim($_REQUEST['type']);
        if(!empty($_COOKIE["access_token".$this->type])){
            $openid = $this->getAccessTokenAction($_COOKIE["access_token".$this->type]);
        }else{
            $openid = $this->getOpenId($intType);
        }
        Yaf_Session::getInstance()->set("openid",$openid); 
        Yaf_Session::getInstance()->set("idtype",$intType);
        $ret = $this->loginLogic->thirdLogin($openid,$intType);
        if(!ret) {
            return $this->ajax(User_RetCode::getMsg($ret));
        }
        return $this->ajaxError($ret,User_RetCode::getMsg($ret));
    }
    
    /**
     * 
     */
    public function getUserInfoAction(){
        if(Yaf_Session::getInstance()->has("LOGIN")){
            $this->uid = Yaf_Session::getInstance()->get("LOGIN");
            $data = $this->loginLogic->getUserInfo($this->uid);
        }
        if(!empty($data)){
            return $this->ajax(array(
                'data'=> $data,
            ));
        }
        return $this->ajaxError(User_RetCode::DATA_NULL,User_RetCode::getMsg(User_RetCode::DATA_NULL));
    }
    
    /**
     * 根据$intType类型获取auth code
     * @param int $intType,1表示qq,2表示微博
     */
    public function getOpenId($intType){
        $this->type = $intType;
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['auth_code_url'];
        $arrData = $arrData[$intType];
        $host = $arrData['host'];
        $randnum = md5(uniqid(rand(), TRUE)); 
        Yaf_Session::getInstance()->set("state",$randnum);
        $url = $arrData['authcode_url'].$arrData['appid']."&redirect_uri=".$redirect_url."&scope=get_user_info&state=".$randnum;    
        $post = Base_Network_Http::instance()->url($host,$url);
        $post->exec();
    }
    
    /**
     * 获取access token
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
    public function getAccessTokenAction($access_token){
        $strAccessToken = trim($_REQUEST['access_token']);
        if(!empty($access_token)){
            $strAccessToken = $access_token;
        }
        setcookie("access_token".$this->type,$strAccessToken, time()+3600*24*30);
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
            $this->ajaxError("test");
        }
        return $user->openid;
    }
}
