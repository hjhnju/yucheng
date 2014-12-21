<?php
/**
 * 借款的附件
 * @author jiangsongfang
 *
 */
class AttachController extends Base_Controller_Admin {
    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'loan_id' => '借款ID不能为空!',
        'type' => '类别不能为空!',
        'title' => '标题不能为空!',
        'url' => '地址不能为空!',
    );
    
	/**
	 * 借款的附件列表
	 */
	public function indexAction() {
	    $id = intval($_GET['id']);
	    if (empty($id)) {
	        $this->outputError();
	        return false;
	    }
	    $list = new Loan_List_Attach();
	    $filters = array(
	        'loan_id' => $id,
	    );
	    $list->setFilter($filters);
	    $list->setPagesize(PHP_INT_MAX);
	    $this->_view->assign('data', $list->toArray());
	}
	
	/**
	 * 添加附件
	 * @return boolean
	 */
	public function addAction() {
	    if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
	        $attach = Loan_Object_Attach::init($_POST);
	        $attach->userId = $this->getAdminId();
	        if ($attach->save()) {
	            $this->output();
	        } else {
	            $this->outputError();
	        }
	    } else {
            $this->outputError();
	    }
	}
	
	/**
	 * 删除附件
	 * @return boolean
	 */
	public function delAction() {
	    $id = intval($_GET['id']);
	    if (empty($id)) {
	        $this->outputError();
	        return false;
	    }
	    $attach = new Loan_Object_Attach($id);
        if ($attach->remove()) {
            $this->output();
        } else {
            $this->outputError();
        }
	}
}