<?php
/**
 * 借款的审核信息
 * @author jiangsongfang
 *
 */
class AuditController extends Base_Controller_Admin {
    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'loan_id' => '借款ID不能为空!',
        'user_id' => '创建人不能为空!',
        'type' => '认证类型不能为空!',
        'name' => '认证项不能为空!',
        'comment' => '备注不能为空!',
        'status' => '状态不能为空!',
        'create_time' => '创建时间不能为空!',
        'update_time' => '更新时间不能为空!',

    );
      
	/**
	 * 借款的审核信息
	 */
	public function indexAction() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->outputError();
            return false;
        }
        
        if (!empty($_POST['audit'])) {
            foreach ($_POST['audit'] as $key => $data) {
                $audit = Loan_Object_Audit::init($data);
                $audit->loanId = $id;
                $audit->userId = $this->getAdminId();
                $audit->save();
            }
        }
        
        $list = new Loan_List_Audit();
        $list->setFilter(array('loan_id' => $id));
        $list->setPagesize(PHP_INT_MAX);
        
        $this->_view->assign('list', $list->toArray());
	}
}