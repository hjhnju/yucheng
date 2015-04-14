<?php
/**
 * 用户注册相关操作
 */
class OpenController extends Base_Controller_Page{
    
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
    
    /**
     * 用户开通汇付
     */
    public function IndexAction(){
        //是否开通汇付
        $huifuId = $this->objUser->huifuid;
        
        if(!empty($huifuId)){
            return $this->redirect('/account/overview');
        }

        //移动端跳转
        $isMobile = Base_Util_Mobile::isMobile();
        if($isMobile){
            return $this->redirect('/m/open');
        }
    }
}
