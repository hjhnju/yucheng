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
	    $filter['type_id'] = $this->getInt('type_id');
	    $filter['cat_id'] = $this->getInt('cat_id');
	    $filter['duration'] = $this->getInt('duration');
	    $list = Invest_Api::getInvestList($page, $pagesize, $filter);
	    
	    $this->ajax($list);
	}
}
