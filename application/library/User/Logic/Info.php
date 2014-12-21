<?php
/**
 * 用户资料查询修改Logic层
 */
class User_Logic_Info{
    
    public function __construct(){
        $this->modMaterial = new MaterialModel();
    }
    
    /**
     * 
     * @param string $strName,用户名
     */
    public function setRealName($strName){        
        if(empty($strName)||(User_Api::checkReg('name',$strName))){
            return User_RetCode::UNKNOWN_ERROR;
        }
        $data = $this->modMaterial->setRealName($strName);
        if(empty($data)) {
            return User_RetCode::DATA_NULL;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return int,0表示手机存在，1表示手机不存在
     */
    public function setPhone($strPhone){
        if(empty($strPhone)||(User_Api::checkReg('phone',$strPhone))){
            return User_RetCode::UNKNOWN_ERROR;
        }
        $data = $this->modMaterial->setPhone(intval($strPhone));
        if(empty($data)) {
            return User_RetCode::DATA_NULL;
        }
        return User_RetCode::SUCCESS;
    }

    /**
     * 根据用户$arrUid数组获取用户的一些信息
     */
    public function getUserInfo($arrUid){
        $arrResult = array();
        foreach ($arrUid as $uid){
            $data = $this->modMaterial->getUserInfo($uid);
            if(!empty($data)) {
                $arrResult[] = $data;
            }
        }
        return $arrResult;
    }
    
    /**
     * 添加用户信息
     * @param array $arrInfo
     * 'uid' => ''
     * 'name' => ''
     * 'phone' => ''
     */
    public function setUserInfo($arrInfo){
        $ret = $this->modMaterial->updateUserInfo($arrInfo);
        return $ret;
    }

    /**
     * 根据用户id获取用户信息
     * @author  hejunhua
     */
    public function getUserObject($intUserid){
        $objUser = new User_Object($intUserid);
        return $objUser;
    }
}