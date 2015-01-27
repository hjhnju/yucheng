<?php
/**
 * 投标记录
 */
class ListController extends Base_Controller_Api {
	
	public function indexAction() {
	    $id = intval($_REQUEST['id']);
	    if (empty($id)) {
	        $this->ajaxError(Base_RetCode::PARAM_ERROR);
	    }
	    
	    $list = Invest_Api::getLoanInvests($id);
	    $this->ajax($list);
	}
}
