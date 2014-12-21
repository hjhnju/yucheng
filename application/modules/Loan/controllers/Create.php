<?php
/**
 * 创建借款
 * @author jiangsongfang
 *
 */
class CreateController extends Base_Controller_Admin {

    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'title' => '标题不能为空！',
        'amount' => '借款金额不能为空！',
        'interest' => '利率不能为空！',
        'duration' => '借款时间不能为空！',
    );
    
    /**
     * 创建借款
    */
    public function indexAction() {
        if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
            $loan = Loan_Object_Loan::init($_POST);
            if ($loan->save()) {
                $this->output(array('id' => $loan->id));
            } else {
                $this->outputError();
            }
        }
    }
}