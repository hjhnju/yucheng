<?php
class IndexController extends Base_Controller_Page{

    protected $needLogin = false;

    public function indexAction() {
        $obj = new Apply_Logic_Apply();
        $obj->saveApply();
    }
    /**
     * @param null
     * @return 
     */ 
    public function addAction() {
        //User_Api::regist('fina', 'beta004@xingjiaodai.com', '123456', '');
     //    print_r('123');die();
     //    $_POST = array(
     //        'name'     => 'guojinli',
     //        'weight'   => '0.25',
     //        'apply_id' => '11',
     //    );
    	// if (!empty($_POST)) {
	    //     $apply = Apply_Object_Stock::init($_POST);
	    //     if ($apply->save()) {
	    //         $this->output();
	    //     } else {
	    //         $this->outputError();
	    //     }
	    // } else {
     //        $this->outputError();
	    // }
    }
}