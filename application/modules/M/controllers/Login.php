<?php
/**
 * 微站登录
 */
class LoginController extends Base_Controller_Page {

    protected $loginUrl = '/m/login';
    
    public function init(){
        //未登录不跳转
        $this->setNeedLogin(false);

        parent::init();
    }
    /**
     * 登录
     * 微信授权链接为：https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8caa620ee9e4503a&redirect_uri=http://www.xingjiaodai.com/m/login/weixin&response_type=code&scope=snsapi_login#wechat_redirect
     * /m/login
     * @param   
     * @assign   
     */
    public function indexAction() {
    	$this->getView()->assign('title', "登录兴教贷");
        $logic  = new User_Logic_Login();
        $userid = $logic->checkLogin();
        if($userid){
            $this->redirect('/account/overview');
        }
        $u = isset($_REQUEST['u'])?trim($_REQUEST['u']):null;
        if(!empty($u)){
            $strRedirect = $u;
        }else{
            $strRedirect = $logic->loginRedirect($_SERVER['HTTP_REFERER']);
        }        
        Yaf_Session::getInstance()->set(User_Keys::LOGIN_REFER,$strRedirect);
        $intFails = Yaf_Session::getInstance()->get(User_Keys::getFailTimesKey());
        if($intFails >= 3) {
            $this->getView()->assign('url',$this->webroot . '/user/imagecode/getimage?type=login');
        }
    }
    
    /**
     * 微信登录或绑定账号
     */
    public function WeixinAction(){
        $strType     = 'weixin';
        $strAuthCode = isset($_REQUEST['code'])?$_REQUEST['code']:'';
        $logicThird       = new User_Logic_Third();
        if(!empty($strAuthCode)){           
            $arrRet  = $logicThird->WeixinLogin($strAuthCode);      
            $openid  = $arrRet['openid'];
            $token   = $arrRet['token'];
        }
        if(empty($openid)){
            return $this->ajaxError(User_RetCode::GET_OPENID_FAIL,
                    User_RetCode::getMsg(User_RetCode::GET_OPENID_FAIL));
        }
        Yaf_Session::getInstance()->set(User_Keys::getAuthTypeKey(),$strType);
        Yaf_Session::getInstance()->set(User_Keys::getOpenidKey(), $openid);
        Base_Redis::getInstance()->set(User_Keys::getAccessTokenKey($strType, $openid), $token, 30*24*3600);
        //是否已有绑定账号
        $userid = $logicThird->getBindUserid($openid, $strType);
        
        if(!empty($userid)){
            //已绑定的用户登录成功
            $objUser = new User_Object($userid);
            $logic   = new User_Logic_Login();
            $logic->setLogin($objUser);
            Base_Log::notice(array(
            'msg'  => 'success',
            'type' => $strType,
            'code' => $strAuthCode,
            'openid' => $openid,
            ));
            $this->redirect('/account/overview');
        }else{
            //用户未绑定账号
            //session 中已存openid
            Base_Log::notice(array(
            'msg' => 'redirect to regist bind',
            'type' => $strType,
            'code' => $strAuthCode,
            'openid' => $openid,
            ));            
            $this->redirect('/m/regist?type=weixin');
        }       
    }
}
