<?php
class ReviewController extends Base_Controller_Page{

    protected $needLogin = false;

    public function indexAction() {
        print_r(Apply_Cookie::parseCookie('school'));die('123');
        
        
    }
    /**
     * @param null
     * @return 
     * 将所有的数据全部写入数据库
     */
    public function submitAction() {
        // $apply      = new Apply_Logic_Apply();
        // $objRst     = $apply->saveApply();

        // print_r($objRst);die();

        // $school     = new Apply_Logic_School();
        // $objRst     = $school->saveSchool();
        
        // $personal   = new Apply_Logic_Personal();
        // $objRst     = $personal->savePerson();
        
        // $stock      = new Apply_Logic_Stock();
        // $objRst     = $stock->saveStock();
    }
}