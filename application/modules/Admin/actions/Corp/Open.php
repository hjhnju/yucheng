<?php
/**
 * 开通汇付
 * @author hejunhua
 *
 */
class OpenAction extends Yaf_Action_Abstract {
    public function execute() {
        $userid = isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : 0;
        if($userid<=0){
        	return;
        }
        $user  = User_Api::getUserObject($userid);
        if(!$user->huifuid){
	        //跳转至汇付开企业户
	        Finance_Api::corpRegist($user->userid, $user->name, $user->busicode);
	    }
    }
}