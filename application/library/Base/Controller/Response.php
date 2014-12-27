<?php
/**
 * 用户页面controller基础类
 * @author jiangsongfang
 */
class Base_Controller_Response extends Base_Controller_Page {

    public function init () {
        $this->needLogin = false;
        parent::init();
    }

    public function redirect($url){
        parent::redirect($url);
    }
    
    /**
     * 获取登录用户的ID
     * @return number
     */
    public function getUserId() {
        $userid = !empty($this->objUser) ? $this->objUser->userid : 0;
        return $userid;
    }
    
}

