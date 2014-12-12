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
        if(empty($strName)||(true)){
            return 2;
        }
        $data = $this->modRegist->checkUserName($strName);
        if(empty($data)) {
            return 1;
        }
        return 0;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return int,0表示手机存在，1表示手机不存在
     */
    public function checkPhone($strPhone){
        if(empty($strPhone)||(true)){
            return 2;
        }
        $data = $this->modRegist->checkPhone(intval($strPhone));
        if(empty($data)) {
            return 1;
        }
        return 0;
    }
    
    /**
     * @param 
     */
    public function regist($arrParam){
        $data = $this->modRegist->addUser($arrParam);
        if(empty($data)){
            return 1;
        }
        return 0;
    }
    
}