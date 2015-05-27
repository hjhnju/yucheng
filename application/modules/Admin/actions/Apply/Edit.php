<?php
/**
 * 借款编辑页面
 * @author guojinli
 *
 */
class EditAction extends Yaf_Action_Abstract {
    public function execute() {
    	$filter = array('id' => $_REQUEST['id']);
        $data = Apply_Api::getApplyList(1, 1, $filter);
        $this->_view->assign('data', $data['list'][0]);
    }
}
