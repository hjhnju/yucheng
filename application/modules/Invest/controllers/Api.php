<?php
/**
 * 投资列表
 */
class ApiController extends Base_Controller_Api {
    protected $ajax = true;
    protected $needLogin = false;
	
	public function indexAction() {
	    $page = $this->getInt('page', 1);
	    $pagesize = 10;
	    
	    $filter = array();
	    $filter['type'] = $this->getInt('type');
	    $filter['cat'] = $this->getInt('cat');
	    $filter['period'] = $this->getInt('period');
	    $list = Invest_Api::getInvestList($page, $pagesize, $filter);
	    
	    $this->ajax($list);
	}
}
