<?php
/**
 * 公告列表
 * @author huwei
 *
 */
class EditAction extends Yaf_Action_Abstract {
    public function execute() {
        $name = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 10;
        $arrUser  = User_Api::getPrivUsers($page, $pagesize, $name);
        $this->getView()->assign('arrUser', $arrUser);
    }
}