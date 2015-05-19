<?php
class SchoolController extends Base_Controller_Page{

    protected $needLogin = false;
	/**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'address'		=> '学校地址不能为空!',
        'total_student'	=> '学校学生不能为空!',
        'staff'			=> '学校教职工不能为空!',
        'branch_school'	=> '分校不能为空!',
        'purpose'		=> '款使用用途不能为空!',
    );
    public function indexAction() {
        $data = array(
			'guarantee'		=> Apply_Type_Guarantee::getGuarantes(),
			'branch_school'	=> Apply_Type_BranchSchool::getYears(),
			'purpose'		=> Apply_Type_Purpose::$names,
		);
		$this->getView()->assign('data', $data);
    }

    public function submitAction() {
    	//检查值是否合法，合法后记录到cookie，并且跳转到下一步
		if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
			//记录cookie
	    	$logic = new Apply_Logic_School();
	    	$logic->saveCookie($_POST);
            $this->ajax(array('url' => 'apply/person'), '', Apply_RetCode::NEED_REDIRECT);
		}

        return $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
    }
}