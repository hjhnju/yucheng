<?php
/**
 * 投资列表
 */
class IndexController extends Base_Controller_Page {
    public function init() {
        $this->needLogin = false;
        parent::init();
    }
	
	public function indexAction() {
	    $page = $this->getInt('page', 1);
	    $pagesize = 10;
	    
	    $filter = array();
	    $filter['type'] = $this->getInt('type');
	    $filter['cat'] = $this->getInt('cat');
	    $filter['period'] = $this->getInt('period');
	    $list = Invest_Api::getInvestList($page, $pagesize, $filter);
	    $this->_view->assign('data', $list);
	}
}
