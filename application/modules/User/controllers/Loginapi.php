<?php
/**
 * 用户登录相关操作
 */
class LoginApiController extends Base_Controller_Api{
    
    protected $uid = null;
    protected $type = null;
    const REDIS_VALID_TIME = 2592000;
    
    public function init(){
        $this->setAutoJump(false);
        parent::init();
        $this->loginLogic = new User_Logic_Login();
    }
    
    /**
     * 标准登录过程
     * 状态返回0表示登录成功
     */    
    public function indexAction(){
       $strName = trim($_REQUEST['name']);
       $strPasswd = trim($_REQUEST['passwd']);
       $strToken = trim($_REQUEST['token']);
       $type = $this->loginLogic->checkType($strName);
       if('error' == $type) {
           $intWrongTimes = Yaf_Session::getInstance()->get("LOGIN_WRONG");
           if(empty($intWrongTimes)){
               Yaf_Session::getInstance()->set("LOGIN_WRONG",1);
           }else{
               Yaf_Session::getInstance()->set("LOGIN_WRONG",$intWrongTimes+=1);
           }
           if($intWrongTimes >= 3) {
               return $this->ajaxError(User_RetCode::NEED_PICTURE,'',array('url'=>"http://123.57.46.229:8301/User/loginapi/getAuthImage?token=$strToken"));
           }
           return $this->ajaxError(User_RetCode::USER_NAME_ERROR,User_RetCode::getMsg(User_RetCode::USER_NAME_ERROR));
       }
       
       $retUid = $this->loginLogic->login($type,$strName,$strPasswd);
       if(User_RetCode::INVALID_USER != $retUid) {
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
           return $this->ajaxError(User_RetCode::NEED_PICTURE,'',array('url'=>'http://123.57.46.229:8301/User/loginapi/getAuthImage'));
       }
       return $this->ajaxError(User_RetCode::USER_NAME_OR_PASSWD_ERROR,User_RetCode::getMsg(User_RetCode::USER_NAME_OR_PASSWD_ERROR));
    }
    
    /**
     * 获取open id，分为如下几步骤:
     * 1.拿到auth code,当用户点击授权后，将返回auth code
     * 2.拿到auth code后，首先检查access token是否存在，如果不存在执行3，存在执行4
     * 3.通过auth code获取access token
     * 4.通过access token拿到用户的openid
     */
    public function thirdLoginAction(){
        if(!isset($_REQUEST['code'])){   //auth code
            return $this->ajaxError(User_RetCode::GET_AUTHCODE_FAIL,User_RetCode::getMsg(User_RetCode::GET_AUTHCODE_FAIL));    
        }
        $strType = Yaf_Session::getInstance()->get("third_login_type");
        $state = trim($_REQUEST['state']);
        $strAuthCode = trim($_REQUEST['code']);      
        $key = $_COOKIE['access_key'];
        $access_token = Base_Redis::getInstance()->get("access_token".$strType.$key);
        if(empty($access_token)){
            $access_token = $this->loginLogic->getAccessToken($strAuthCode);
        }
        $openid = $this->loginLogic->getOpenid($access_token);
        Yaf_Session::getInstance()->set("openid",$openid);
        Yaf_Session::getInstance()->set("idtype",$strType);
        $ret = $this->loginLogic->checkBind($openid, $strType); //$ret=0表示已经绑定，$ret=1表示未绑定
        if($ret){
            return $this->ajax();         //用户登录成功并已经绑定账号
        }else{
            return $this->ajaxError(User_RetCode::UNBOUND,User_RetCode::getMsg(User_RetCode::UNBOUND));  //用户未绑定账号
        }       
    }
    
  
    
    /**
     * 对第三方账号进行绑定
     */
    public function setBindAction(){
        $strName = trim($_REQUEST['name']);
        $strPasswd = trim($_REQUEST['passwd']);
        $openid = Yaf_Session::getInstance()->get("openid");
        $strType =  Yaf_Session::getInstance()->get("idtype");
        $ret = $this->loginLogic->setBind($openid, $strType,$strName,$strPasswd);
        if($ret){
            return $this->ajax();
        }
        return $this->ajaxError(User_RetCode::UNBOUND);
    }
    
    /**
     * 返回获取图片验证码的URL
     */
    public function getAuthImageUrlAction(){
        $strToken = trim($_REQUEST['token']);
        return $this->ajaxError(User_RetCode::NEED_PICTURE,'',array('url'=>"http://123.57.46.229:8301/User/loginapi/getAuthImage?&token=$strToken"));
    }
    
    /**
     * 获取图片验证码
     */
    public function getAuthImageAction(){
        $strToken = trim($_REQUEST['token']);
        User_Logic_Api::getAuthImage($strToken);
    }
    
    /**
     * 验证图片验证码
     */
    public function checkAuthImageAction(){
        $strToken = trim($_REQUEST['token']);
        $strImageCode= trim($_REQUEST['imagecode']);
        $storedImageCode = Base_Redis::getInstance()->get($strToken);
        if(strtolower($storedImageCode) == strtolower($strImageCode)){
            $this->ajax();
        }
        $this->ajaxError(User_RetCode::IMAGE_CODE_WRONG,User_RetCode::getMsg(User_RetCode::IMAGE_CODE_WRONG));
    }
    
    public function testAction(){
        print $this->loginLogic->getAuthCodeUrl('qq');
    }
}
