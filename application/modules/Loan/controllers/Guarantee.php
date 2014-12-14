<?php
/**
 * 借款担保信息
 * @author jiangsongfang
 *
 */
class GuaranteeController extends Base_Controller_Admin {

    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'loan_id' => '借款ID不能为空',
        'user_id' => '操作员ID不能为空',
        'name' => '担保人不能为空',
    );
    
	/**
	 * 借款担保信息
	 */
	public function indexAction() {
        $laonId = intval($_GET['id']);
        if (empty($laonId)) {
            $this->outputError();
            return false;
        }
        
        $gua = new Loan_Object_Guarantee();
        $gua->loanId = $laonId;
        $gua->status = 1;
        $gua->fetch();
        
        if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
            $gua->setData($_POST);
            $gua->userId = $this->getAdminId();
            
            if (!$gua->save()) {
                $this->outputError();
                return false;
            }
        }
        $this->_view->assign('guarantee', $gua->toArray());
	}
}