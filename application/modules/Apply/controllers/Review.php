<?php
class ReviewController extends Base_Controller_Page{

    protected $needLogin = true;

    public function indexAction() {
        //读出所有的cookie，用来显示
        $data = Apply_Cookie::showCookieValue();
        $data['duration'] = Apply_Type_Duration::$names;
        $data['minmax']   = Apply_Type_MinMax::$values;
        $this->getView()->assign('data', $data);
    }
    /**
     * @param null
     * @return 
     * 将所有的数据全部写入数据库
     */
    public function submitAction() {
        //保存apply
        $apply      = new Apply_Logic_Apply();
        $objRst     = $apply->saveApply();
        $apply_id   = $objRst['data']['id'];
        //如果第一步保存成功，并且返回一个保存的apply id
        if($apply_id) {
            //保存学校信息
            $school     = new Apply_Logic_School();
            $objSchool  = $school->saveSchool($apply_id);
            if($objSchool['status'] != Apply_RetCode::SUCCESS) {
                return $this->ajaxError(Apply_RetCode::SCHOOL_PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::SCHOOL_PARAM_ERROR));
            }
            //保存股权结构信息
            $stock = new Apply_Logic_Stock();
            $objStock = $stock->saveStock($apply_id);
            if($objStock['status'] != Apply_RetCode::SUCCESS) {
                return $this->ajaxError(Apply_RetCode::STOCK_PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::STOCK_PARAM_ERROR));
            }
            //保存个人信息
            $personal   = new Apply_Logic_Personal();
            $objPersonal= $personal->savePerson($apply_id);
            if($objPersonal['status'] != Apply_RetCode::SUCCESS) {
                return $this->ajaxError(Apply_RetCode::PERSONAL_PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PERSONAL_PARAM_ERROR));
            }
            //保存后将所有cookie删除，避免二次插入
            // Apply_Cookie::erasureCookie();
            $this->ajax(array('url' => '/apply/finish'), '', Apply_RetCode::NEED_REDIRECT);
        }
        return $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
    }
}