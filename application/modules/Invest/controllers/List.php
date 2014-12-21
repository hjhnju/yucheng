<?php
/**
 * 投标记录
 */
class ListController extends Base_Controller_Response {
    protected $ajax = true;
	
	public function indexAction() {
	    $id = intval($_GET['id']);
	    if (empty($id)) {
	        $this->outputError(Base_RetCode::PARAM_ERROR);
	    }
	    
	    $list = Invest_Api::getLoanInvests($id);
	    $this->output($list);
	}
}
