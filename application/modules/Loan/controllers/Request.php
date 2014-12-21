<?php
/**
 * 借款申请
 * @author jiangsongfang
 *
 */
class RequestController extends Base_Controller_Admin {
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