<?php
/**
 * 注册Logic层
 */
class User_Logic_Regist{
    
    public function __construct(){
    }
    
    /**
     * 
     * @param string $strName,用户名
     * @return int $retCode
     * SUCCESS 表示用户名可用
     * USERNAME_EXIST 表示用户名已存在
     * USERNAME_SYNTEX_ERROR 表示输入的用户名不合法
     */
    public function checkName($strName){        
        if(empty($strName) || !User_Logic_Validate::checkName($strName)){
            return User_RetCode::USERNAME_SYNTEX_ERROR;
        }

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('name'=>$strName));

        if(!empty($objLogin->name)) {
            return User_RetCode::USERNAME_EXIST;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return 
     * SUCCESS 表示手机可用
     * USERPHONE_EXIST 表示手机不存在
     * USERPHONE_SYNTEX_ERROR 表示手机格式错误
     */
    public function checkPhone($strPhone){
        if(empty($strPhone) || !User_Logic_Validate::checkPhone($strPhone)){
            return User_RetCode::PHONE_FORMAT_ERROR;
        }

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('phone'=>$strPhone));
        if(!empty($objLogin->phone)) {
            return User_RetCode::USERPHONE_EXIST;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return 标准json格式
     */
    public function checkInviter($strPhone){  
        //邀请人可为空      
        if(empty($strPhone)){
            return new Base_Result(User_RetCode::SUCCESS);
        }
        if(!User_Logic_Validate::checkPhone($strPhone)){
            return new Base_Result(User_RetCode::PHONE_FORMAT_ERROR, null,
                User_RetCode::getMsg(User_RetCode::PHONE_FORMAT_ERROR));
        }

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('phone'=>$strPhone));
        if(empty($objLogin->userid)) {
            return new Base_Result(User_RetCode::INVITER_NOT_EXIST, null,
                User_RetCode::getMsg(User_RetCode::INVITER_NOT_EXIST));
        }
        return new Base_Result(User_RetCode::SUCCESS, array('inviterid'=>$objLogin->userid));
    }
    
    /**
     * 注册的同时要添加信息进`user_info`表
     * @param  usertype $usertype 用户类型 'priv' | 'corp'
     * @param  username $username 用户名
     * @return $userid | false
     */
    public function regist($usertype, $username, $passwd, $phone, $inviter = ''){
        //企业用户手机前面加0
        $usertype = $this->getUserType($usertype);
        //如果新注册用户不是融资用户，要进行用户名和电话的认证
        if($usertype == User_Type_Roles::TYPE_FINA) {
            //各字段再验证过一遍
            //允许用户名为空
            if(!empty($username)) {
                $retCode = $this->checkName($username);
                if(User_RetCode::SUCCESS !== $retCode){
                    return new Base_Result($retCode, null, User_RetCode::getMsg($retCode));
                }
            }
            
            if($usertype === User_Type_Roles::TYPE_CORP){
                $phone = '0' . $phone;
            }
            $retCode = $this->checkPhone($phone);
            if(User_RetCode::SUCCESS !== $retCode){
                return new Base_Result($retCode, null, User_RetCode::getMsg($retCode));     
            }
        }

        $passwd = Base_Util_Secure::encrypt($passwd);
        $objLogin           = new User_Object_Login();
        if(!empty($username)){
            if($usertype == User_Type_Roles::TYPE_FINA) {
                $objLogin->email = $username;
            }else {
                $objLogin->name  = $username;
                $objLogin->phone    = $phone;
            }  
        }
        $objLogin->passwd   = $passwd;
        $objLogin->usertype = $usertype;
        
        $ret = $objLogin->save();
        if(!$ret){
            return new Base_Result(User_RetCode::REGIST_FAIL, null, User_RetCode::getMsg($retCode));
        }

        $objInfo         = new User_Object_Info();
        $objInfo->userid = $objLogin->userid;
        $objInfo->save();

        return new Base_Result(User_RetCode::SUCCESS,
            array('userid' => $objLogin->userid));
    }
    
    /**
     * 用户忘了密码后重置密码
     * @param string $strName
     * @param string $strPhone
     * @param string $strPasswd
     * @return int 0:表示成功，其它为相应错误码
     */
    public function modifyPwd($strName,$strPhone,$strPasswd){
        $objLogin = new User_Object_Login();
        $ret = $objLogin->fetch(array('name'=>$strName,'phone'=>$strPhone));
        if($ret){
            $objLogin->passwd = Base_Util_Secure::encrypt($strPasswd);
            $ret = $objLogin->save();
            if($ret){
                $ret = User_RetCode::SUCCESS;
            }else{
                $ret = User_RetCode::UNKNOWN_ERROR;
            }
        }else{
            $ret = User_RetCode::USER_NAME_OR_PHONE_ERROR;
        }
        return $ret;
    }

    /**
     * 返回用户类型
     */
    private function getUserType($usertype){
        $usertype = strtolower($usertype);
        $type     = null;
        switch ($usertype) {
            case 'priv':
                $type = User_Type_Roles::TYPE_PRIV;
                break;
            case 'corp':
                $type = User_Type_Roles::TYPE_CORP;
                break;
            case 'fina':
                $type = User_Type_Roles::TYPE_FINA;
                break;
            default:
                $type = User_Type_Roles::TYPE_CORP;
                break;
        }
        return $type;
    }

    public function setInviter($userid, $inviterid){
	if($inviterid <= 0){
	    return false;
	}
        $invite          = new User_Object_Invite();
        $invite->userid  = intval($inviterid);
        $invite->invitee = intval($userid);
        $bolRet          = $invite->save();
        if(!$bolRet){
            Base_Log::error(array(
                'msg'       => 'set invite error',
                'userid'    => $userid,
                'inviterid' => $inviterid,
            ));
            return false;
        }
        return true;
    }
}
