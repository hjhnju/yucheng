<?php
/**
 * 注册Logic层
 */
class User_Logic_Regist{
    
    public function __construct(){
        $this->modRegist = new RegistModel();
    }
    
    /**
     * 
     * @param string $strName,用户名
     * @return int,0表示用户名不存在，1表示用户名存在，2表示输入的用户名不合法
     */
    public function checkName($strName){        
        if(empty($strName)||(User_Api::checkReg('name',$strName))){
            return User_RetCode::UNKNOWN_ERROR;
        }
        $data = $this->modRegist->checkUserName($strName);
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
        if(empty($strPhone)||(User_Api::checkReg('phone',$strPhone))){
            return User_RetCode::UNKNOWN_ERROR;
        }
        $data = $this->modRegist->checkPhone(intval($strPhone));
        if(empty($data)) {
            return User_RetCode::DATA_NULL;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     * @param array $arrParam注册所需要的信息
     * @return int $uid,成功注册返回用户id，否则返回0
     */
    public function regist($arrParam){
        $uid = $this->modRegist->addUser($arrParam);
        return $uid;
    }
    
}