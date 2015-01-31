<?php
/**
 * 用户注册相关操作
 */
class RegistApiController extends Base_Controller_Api{
    
    public function init(){
       
        $this->setNeedLogin(false);

        parent::init();
        $this->loginLogic = new User_Logic_Login();
    }
    
    /**
     * 检查用户名是否存在
     * 
     */
    public function checkNameAction(){
        $strName = trim($_REQUEST['name']);
        $logic   = new User_Logic_Regist();
        $retCode = $logic->checkName($strName);

        Base_Log::notice(array('retCode'=>$retCode));
        if(User_RetCode::SUCCESS !== $retCode){
            return $this->ajaxError($retCode, User_RetCode::getMsg($retCode));
        }
     
        return $this->ajax();
    }
    
    /**
     * 检查手机号中否存在，返回值同上
     */
    public function checkPhoneAction(){
        $strPhone = trim($_REQUEST['phone']);

        $logic   = new User_Logic_Regist();
        $retCode = $logic->checkPhone($strPhone);
        if(User_RetCode::SUCCESS !== $retCode){
            return $this->ajaxError($retCode, User_RetCode::getMsg($retCode));     
        }
        
        return $this->ajax();
    }
    
    /**
     * 获取验证码信息
     * @param  string $phone 
     * @param  string $type, 自定义类型, e.g:'regist'-注册
     */
    public function sendSmsCodeAction(){
        $strPhone = trim($_REQUEST['phone']);
        $strType  = trim($_REQUEST['type']);
        $ret      = User_Api::sendSmsCode($strPhone, $strType);
        if(!$ret){
            return $this->ajaxError(User_RetCode::GETVERICODE_FAIL,
                User_RetCode::getMsg(User_RetCode::GETVERICODE_FAIL));
        }
        return $this->ajax();
        
    }
    
    /**
     * 验证用户输入的验证码是否正确
     * @param  string $phone 
     * @param  string $type, 自定义类型,e.g'regist':注册
     * @param  string $vericode, 验证码 
     */
    public function checkSmsCodeAction(){
        $strPhone = trim($_REQUEST['phone']);
        $strType  = trim($_REQUEST['type']);
        $strCode  = trim($_REQUEST['vericode']);
        $ret      = User_Api::checkSmsCode($strPhone, $strCode, $strType);
        if(!$ret){
            return $this->ajaxError(User_RetCode::VERICODE_WRONG,
                User_RetCode::getMsg(User_RetCode::VERICODE_WRONG));
        }
        return $this->ajax();
    }
    
    /** 
    * 接口4: /user/registapi/checkinviter
    * 检查推荐人是否存在
    * @param string $inviter
    * @param string $token
    * @return 标准Json格式
    * status 0:成功，表示推荐人存在
    * status 1031:推荐人不存在
    */
    public function checkInviterAction(){
        $strInviter = trim($_REQUEST['inviter']);
        $logic   = new User_Logic_Regist();
        $objRet = $logic->checkInviter($strInviter);
        if(User_RetCode::SUCCESS !== $objRet->status){
            return $this->ajaxError($objRet->status, $objRet->statusInfo); 
        }
        return $this->ajax();
    }
    
    /** 
    * 接口5: /user/registapi/index
    * 用户注册接口
    * @param string $name
    * @param string $passwd
    * @param string $phone
    * @param string $vericode
    * @param int $isthird, 0|1 是否第三方绑定注册
    * @param string $inviter, 推荐人手机号
    * @return 标准Json格式
    * status 0:成功
    * status 1033:注册失败
    * 
    * 
    */
    public function indexAction(){
        $strName   = trim($_REQUEST['name']);
        $strPasswd = trim($_REQUEST['passwd']);
        $strPhone  = trim($_REQUEST['phone']);
        $strCode   = isset($_REQUEST['vericode']) ? $_REQUEST['vericode'] : '';
        $strInviter= isset($_REQUEST['inviter']) ? $_REQUEST['inviter'] : '';
        $isThird   = isset($_REQUEST['isthird']) ? intval($_REQUEST['isthird']) : 0;

        $ret = User_Api::checkSmsCode($strPhone, $strCode, 'regist');
        if(!$ret){
            return $this->ajaxError(User_RetCode::VERICODE_WRONG,
                User_RetCode::getMsg(User_RetCode::VERICODE_WRONG));
        }
        $logic = new User_Logic_Regist();
        $objRet = $logic->checkInviter($strInviter);
        if(User_RetCode::SUCCESS !== $objRet->status){
            return $this->ajaxError($objRet->status, $objRet->statusInfo); 
        }
        $inviterid = isset($objRet->data['inviterid']) ? $objRet->data['inviterid'] : false;
        
        //进行注册
        $objRet  = $logic->regist('priv', $strName, $strPasswd, $strPhone);
        if(User_RetCode::SUCCESS !== $objRet->status){
            return $this->ajaxError($objRet->status, $objRet->statusInfo); 
        }
        $userid = $objRet->data['userid'];

        //登记邀请人
        Base_Log::debug(array('userid'=>$userid, 'inviterid'=>$inviterid));
        Awards_Api::registNotify($userid, $inviterid);

        //进行绑定第三方账户
        if($isThird > 0){
            $openid   = Yaf_Session::getInstance()->get(User_Keys::getOpenidKey());
            $authtype = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
            if(!empty($openid) && !empty($authtype)){
                $logic    = new User_Logic_Third();
                $bolRet   = $logic->binding($userid, $openid, $authtype);
                if(!$bolRet){
                    Base_Log::warn(array(
                        'openid' => $openid,
                        'authtype' => $authtype,
                    ));
                    return $this->ajaxError(User_RetCode::BINDING_FAIL,
                        User_RetCode::getMsg(User_RetCode::BINDING_FAIL));
                }
    
                Base_Log::notice(array(
                    'msg'    => 'binding success',
                    'openid' => $openid,
                    'authtype' => $authtype,
                ));
            }
        }
        $objUser = new User_Object($userid);
        $logic   = new User_Logic_Login();
        $logic->setLogin($objUser);
        Base_Log::notice($_REQUEST);
        
        //注册后的系统消息
        Msg_Api::sendmsg(0, $userid, 1, '系统消息', array());
        Msg_Api::sendmsg(0, $userid, 2, '奖励发放', array('data'=>30));
        return $this->ajaxJump('/user/open');
    }
    
    /** 
     * 忘记密码重置密码接口，重置后设置用户为登录状态
     * @param $user, 用户名
     * @param $phone, 手机号
     * @param $vericode,手机验证码
     * @param $passwd,新密码
     * @return json
     */
    public function modifypwdAction(){
        $strName     = trim($_REQUEST['user']);
        $strPasswd   = trim($_REQUEST['passwd']);
        $strPhone    = trim($_REQUEST['phone']);
        $strType     = trim($_REQUEST['type']);
        $strVericode = trim($_REQUEST['vericode']);
        $ret = User_Api::checkSmscode($strPhone, $strVericode, $strType);
        if(!$ret){
            $this->ajaxError(User_RetCode::VERICODE_WRONG,
                    User_RetCode::getMsg(User_RetCode::VERICODE_WRONG));
        }
        $logic   = new User_Logic_Regist();
        $ret = $logic->modifyPwd($strName,$strPhone,$strPasswd);
        if(User_RetCode::SUCCESS === $ret){
            $objLogin = new User_Object_Login();
            $objLogin->fetch(array('name'=>$strName,'phone'=>$strPhone));
            $objUser = new User_Object($objLogin->userid);
            $objLogic = new User_Logic_Login();
            $objLogic->setLogin($objUser);
            Base_Log::notice($_REQUEST);
            return $this->ajaxJump('/user/open');
        }
        $this->ajaxError($ret,User_RetCode::getMsg($ret));
    }
}
