<?php
/**
 * 编辑借款
 * @author jiangsongfang
 *
 */
class EditController extends Base_Controller_Admin {

    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'id' => '借款ID不能为空！',
        'title' => '标题不能为空！',
        'amount' => '借款金额不能为空！',
        'interest' => '利率不能为空！',
        'duration' => '借款时间不能为空！',
    );
    
    /**
     * 编辑借款
    */
    public function indexAction() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->outputError();
            return false;
        }
        $loan = new Loan_Object_Loan($id);
        if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
            $loan->setData($_POST);
            if (!$loan->save()) {
                $this->outputError();
                return false;
            }
        }
        $this->_view->assign('loan', $loan->toArray());
    }
}