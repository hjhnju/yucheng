<?php
class FilesController extends Base_Controller_Page{

    protected $needLogin = false;
	/**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'apply_id'	=> '申请ID不能为空!',
        'type'		=> '类别不能为空!',
        'title'		=> '标题不能为空!',
        'url'		=> '地址不能为空!',
    );
    public function indexAction() {
        
    }

    public function submitAction() {
    	if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
	        $attach = Apply_Object_Attach::init($_POST);
	        $objUser = User_Api::checkLogin();
	        $attach->userid = $objUser->userid;
	        if ($attach->save()) {
	            $this->output();
	        } else {
	            $this->outputError();
	        }
	    } else {
            $this->outputError();
	    }
    }
}