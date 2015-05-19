<?php
/**
 * 申请模块API接口
 * @author guojinli
 *
 */
class Apply_Api {
    /**
     * @param $email 
     * @return true | false
     * 检查油箱是否被占用
     */
    public static function checkEmail($email){
    	$email = trim($email);
    	if($email == '') {
			return Apply_RetCode::EMAIL_FORMAT;
    	}else if(!User_Logic_Validate::check('email', $email)) {
    		//检查邮箱是否合法
    		return Apply_RetCode::EMAIL_FORMAT;
    	}else {
    		//如果合法查看该油箱是否被占用
    		$objLogin = new User_Object_Login();
	        $objLogin->fetch(array('email'=>$email));

	        if(!empty($objLogin->userid)) {
	            return Apply_RetCode::EMAIL_EXIST;
	        }
    	}

    	return Apply_RetCode::SUCCESS;
    } 
}
