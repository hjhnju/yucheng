<?php
class BasicController extends Base_Controller_Page{

    protected $needLogin = false;
    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'province' 	=> '省份不能为空!',
        'city' 		=> '城市不能为空!',
        'type' 		=> '学校类型不能为空!',
        'year'  	=> '建校时间不能为空!',
        'nature'	=> '学校主体性质不能为空!',
        'school_source'	=> '从哪里了解到我们不能为空!',
    );
    public function indexAction() {
		$data = array(
			'province'	  => Apply_Type_Province::$names,
			'city'     	  => Apply_Type_Province::getAllCity(),
			'school_type' => Apply_Type_SchoolType::$names,
			'year'     	  => Apply_Type_Year::getYears(),
			'yesno'	   	  => Apply_Type_YesNo::$names,
			'nature'   	  => Apply_Type_Nature::$names,
			'school_source'  => Apply_Type_Source::$names,
		);
		$this->getView()->assign('data', $data);
    }

    public function submitAction() {
    	//检查值是否合法，合法后记录到cookie，并且跳转到下一步
		if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
			//记录cookie
	    	$logic = new Apply_Logic_School();
	    	$logic->saveCookie($_POST);
            $this->ajax(array('url' => 'apply/school'), '', Apply_RetCode::NEED_REDIRECT);
		}
        
        return $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
    }
}