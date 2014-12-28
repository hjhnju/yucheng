<?php
class AuthImageController extends Base_Controller_Api {

    /**
     * 获取图片验证码
     */
    public function getAuthImageAction(){
        $strType = trim($_REQUEST['type']);
        $strId   = session_id().$strType;
        User_Logic_AuthImage::genImage($strId);
    }
    
    /**
     * 验证图片验证码
     */
    public function checkAuthImageAction(){
        $strType         = trim($_REQUEST['type']);
        $strImageCode    = trim($_REQUEST['imagecode']);
        $strId           = session_id() . $strType;
        $storedImageCode = Base_Redis::getInstance()->get($strId);
        if(strtolower($storedImageCode) !== strtolower($strImageCode)){
            $this->ajax();
        }else{
            $this->ajaxError(User_RetCode::IMAGE_CODE_WRONG,
                User_RetCode::getMsg(User_RetCode::IMAGE_CODE_WRONG));
        }
    }
}