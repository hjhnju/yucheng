<?php
/**
 * 用户资料查询修改Logic层
 */
class User_Logic_Info{
    
    public function __construct(){
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