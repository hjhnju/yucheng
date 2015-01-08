<?php
/**
 * 借款申请
 * @author jiangsongfang
 *
 */
class RequestController extends Base_Controller_Page {
    protected $needLogin = false;
    
    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'title' => '标题不能为空！',
        'amount' => '借款金额不能为空！',
        'interest' => '利率不能为空！',
        'name' => '借款人不能为空！',
        'contact' => '联系方式不能为空！',
    );
    
	/**
	 * 借款申请
	 */
	public function indexAction() {
	    if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
	        $request = Loan_Object_Request::init($_POST);
	        if ($request->save()) {
	            $this->output();
	        } else {
	            $this->outputError();
	        }
	    }
	    
	    $list = new Area_List_Area();
	    $filters = array(
	        'pid' => 0,
	    );
	    $list->setFilter($filters);
	    $list->setFields(array('id', 'name'));
	    $list->setPagesize(PHP_INT_MAX);
	    $list->setOrder('id');
	    $province = $list->getData();
	    
	    $this->_view->assign('school', Loan_Type_SchoolType::$names);
	    $this->_view->assign('usage', Loan_Type_Usage::$names);
	    $this->_view->assign('refund_type', Loan_Type_RefundType::$names);
	    $this->_view->assign('province', $province);
	}
	
	/**
	 * 借款列表
	 */
	public function listAction() {
	    $page = intval($_REQUEST['page']);
	    $list = new Loan_List_Request();
	    $list->setPage($page);
	    $this->_view->assign('data', $list->toArray());
	    
	    $this->_response->setBody(json_encode($list->toArray()));
	    Yaf_Dispatcher::getInstance()->disableView();
	}
}