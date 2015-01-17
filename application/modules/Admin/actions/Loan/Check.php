<?php
/**
 * 借款审核通过
 * @author jiangsongfang
 *
 */
class CheckAction extends Base_Controller_Action {
    public function execute() {
        $param = array(
            'id' => '借款ID不能为空',
            'checked' => '审核状态不能为空',
        );
        if (!$this->checkParam($param, $_REQUEST)) {
            return false;
        }
        
        $logic = new Admin_Logic_Loan();
        if ($_REQUEST['checked'] == 1) {
            if ($logic->publish()) {
                $this->ajax();
            }
        } else {
            if ($logic->disable()) {
                $this->ajax();
            }
        }
        
        $this->ajaxError();
    }
}