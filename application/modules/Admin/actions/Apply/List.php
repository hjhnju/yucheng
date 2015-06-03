<?php
/**
 * 申请借款V2
 * @author guojinli
 *
 */
class ListAction extends Yaf_Action_Abstract {
    public function execute() {
    	$page 		= $_REQUEST['page']? $_REQUEST['page'] : 1;
    	$pagesize 	= 30;
        $data = Apply_Api::getApplyList($page, $pagesize);
        $this->_view->assign('data', $data);
    }
}
