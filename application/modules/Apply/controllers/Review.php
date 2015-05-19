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
        //保存apply
        // $apply      = new Apply_Logic_Apply();
        // $objRst     = $apply->saveApply();
        //如果第一步保存成功，并且返回一个保存的apply id
        if($objRst['data']['userid'] && Apply_RetCode::SUCCESS == $objRst['status']) {
            //保存学校信息
            $school     = new Apply_Logic_School();
            $objSchool  = $school->saveSchool();
        }

        
        
        // $personal   = new Apply_Logic_Personal();
        // $objRst     = $personal->savePerson();
        
        // $stock      = new Apply_Logic_Stock();
        // $objRst     = $stock->saveStock();
    }
}