<?php
class SchoolController extends Base_Controller_Page{

    protected $needLogin = true;
	/**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'address'		=> '学校地址不能为空!',
        'total_student'	=> '学校学生不能为空!',
        'staff'			=> '学校教职工不能为空!',
        'branch_school'	=> '分校不能为空!',
        'purpose'		=> '贷款使用用途不能为空!',
    );
    public function indexAction() {
        //如果用户点击了修改，我们需要在这里得到之前添加过的cookie
        $cookies = Apply_Cookie::parseCookie('school');
        $cookies['stock'] = Apply_Cookie::parseCookie('stock');
        $data = array(
			'guarantee'		=> Apply_Type_Guarantee::getGuarantes(),
			'branch_school'	=> Apply_Type_BranchSchool::$names,
			'purpose'		=> Apply_Type_Purpose::$names,
            'edit'          => $cookies,
            'duration' => Apply_Type_Duration::$names,
            'minmax'   => Apply_Type_MinMax::$values,
		);

		$this->getView()->assign('data', $data);
    }

    public function submitAction() {
    	//检查值是否合法，合法后记录到cookie，并且跳转到下一步
		if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
            $_POST['stock'] = json_encode($_POST['stock']);

			//记录cookie
	    	$logic = new Apply_Logic_School();
	    	$logic->saveCookie($_POST);

            $stockLogic = new Apply_Logic_Stock();
            $stockLogic->saveCookie($_POST);

            $this->ajax(array('url' => '/apply/person'), '', Apply_RetCode::NEED_REDIRECT);
		}

        return $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
    }

    /**
     * 检验字符串是否合法
     * @param  [type] $param [需要检验的字段数组]
     * @param  [type] $data  [检验字段值的数组]
     * @return [type]        [如果成功返回true,否则返回相应的header包含code和文本信息]
     */
    protected function checkParam($param, $data) {
        foreach ($param as $key => $msg) {
            if ($data[$key] == '') {
                $this->ajaxError(Apply_RetCode::PARAM_ERROR, $msg);
                return false;
            }
        }
        return true;
    }
}