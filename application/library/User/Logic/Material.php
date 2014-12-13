<?php
/**
 * 用户资料查询修改Logic层
 */
class User_Logic_Material{
    
    public function __construct(){
        $this->modMaterial = new MaterialModel();
    }
    
    /**
     * 
     * @param string $strName,用户名
     * @return int,0表示用户名不存在，1表示用户名存在，2表示输入的用户名不合法
     */
    public function checkName($strName){        
        if(empty($strName)||(User_RegCheck::checkReg('name',$strName))){
            return User_RetCode::UNKNOWN_ERROR;
        }
        $data = $this->modMaterial->checkUserName($strName);
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
    public function checkPhone($strPhone){
        if(empty($strPhone)||(User_RegCheck::checkReg('phone',$strPhone))){
            return User_RetCode::UNKNOWN_ERROR;
        }
        $data = $this->modMaterial->checkPhone(intval($strPhone));
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
    public function addUserInfo($arrInfo){
        $ret = $this->modMaterial->addUserInfo($arrInfo);
        return $ret;
    }
}