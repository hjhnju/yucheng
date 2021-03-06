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

    /**
     * 
     * @param  [type] $num [身份证号]
     * @param  string $sex [性别，1为男，2为女，不填写不验证]
     * @return [type]      [检查身份证是否合法]
     */
    public static function checkIdCard($num) {
        if(Apply_IdCardCheck::checkIdentity($num)) {
            return Apply_RetCode::SUCCESS;
        }
        return Apply_RetCode::ID_CARD_WRONG;;
    }

    /**
     * @param  [type] $page [当前页数]
     * @param  [pagesize] [每页多少条数据]
     */
    public static function getApplyList($page=1, $pagesize=10, $filter){
        $obj  = new Apply_Logic_Apply();
        return $obj->getApplyList($page, $pagesize, $filter);
    }   
}
