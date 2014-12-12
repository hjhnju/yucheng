<?php
/**
 * 用户资料查询修改Logic层
 */
class User_Logic_Material{
    
    public function __construct(){
        $this->modRegist = new MaterialModel();
    }
    
    /**
     * 
     * @param string $strName,用户名
     * @return int,0表示用户名存在，1表示用户名不存在
     */
    public function checkName($strName){
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