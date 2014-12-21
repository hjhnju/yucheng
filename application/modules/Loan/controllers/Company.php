<?php
/**
 * 借款的企业信息
 * @author jiangsongfang
 *
 */
class CompanyController extends Base_Controller_Admin {
    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'school' => '学校不能为空!',
        'area' => '区域不能为空!',
        'assets' => '总资产不能为空!',
        'employers' => '员工数不能为空!',
        'years' => '注册年限不能为空!',
        'funds' => '注册资金不能为空!',
        'students' => '学生数不能为空!',
    );
     
	/**
	 * 借款的企业信息
	 */
	public function indexAction() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->outputError(Base_RetCode::PARAM_ERROR);
            return false;
        }
        $company = new Loan_Object_Company();
        $company->loanId = $id;
        $company->fetch();
        if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
            $company->setData($_POST);
            $company->userId = $this->getAdminId();
            if (!$company->save()) {
                $this->outputError();
                return false;
            }
        }
        $this->_view->assign('company', $company->toArray());
	}
}