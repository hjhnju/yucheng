<?php
/**
 * 借款客户列表
 * @author hejunhua
 *
 */
class BorrowerAction extends Yaf_Action_Abstract {
    public function execute() {
        $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 10;
        $arrUser  = User_Api::getBorrowers($page, $pagesize);
        $this->getView()->assign('arrUser', $arrUser);
    }
}