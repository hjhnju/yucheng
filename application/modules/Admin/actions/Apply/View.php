<?php
/**
 * 单个apply显示详细信息
 * @author guojinli
 *
 */
class ViewAction extends Yaf_Action_Abstract {
    public function execute() {
    	$filter = array('id' => $_REQUEST['id']);
        $data = Apply_Api::getApplyList(1, 1, $filter);
        $this->_view->assign('data', $data['list'][0]);
    }
}
