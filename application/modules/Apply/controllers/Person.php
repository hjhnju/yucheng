<?php
class PersonController extends Base_Controller_Page{

    protected $needLogin = false;
	/**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'certificate' 	=> '身份证号码不能为空!',
        'house_type' 	=> '住房类型不能为空!',
        'detail_address'=> '住房详细地址不能为空!',
        'cellphone'  	=> '手机号码不能为空!',
        'telephone'		=> '固定电话不能为空!',
        'is_criminal'	=> '是否有犯罪记录!',
        'is_lawsuit'	=> '是否有未决诉讼!',
    );

    public function indexAction() {
        $data = array(
			'yesno'	   	  	=> Apply_Type_YesNo::$names,
			'scope_cash'   	=> Apply_Type_Cash::$names,
			'scope_stock'	=> Apply_Type_Stock::$names,
		);

		$this->getView()->assign('data', $data);
    }

    public function submitAction() {
    	//检查值是否合法，合法后记录到cookie，并且跳转到下一步
		if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
			//记录cookie
	    	$logic = new Apply_Logic_Personal();
	    	$logic->saveCookie($_POST);
            $this->ajax(array('url' => 'apply/review'), '', Apply_RetCode::NEED_REDIRECT);
		}
        return $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
    }
}