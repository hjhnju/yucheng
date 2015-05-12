<?php
/**
 * 客户详细信息
* @author hejunhua
*
*/
class DetailAction extends Yaf_Action_Abstract {
    public function execute() {
        $userid = isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : 0;
        if(!empty($userid)){
            $userObj = User_Api::getUserObject($userid);
        }
        echo '用户名:'.$userObj->name;
        Yaf_Dispatcher::getInstance()->disableView();
    }
}