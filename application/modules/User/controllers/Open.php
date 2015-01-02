<?php
/**
 * 用户注册相关操作
 */
class OpenController extends Base_Controller_Page{
    
    public function init(){
<<<<<<< HEAD
        //TODO:remove
        $this->setNeedLogin(false);
=======
        $this->setNeedLogin(true);
>>>>>>> b3806bcef9a903e4d3c7c7609934d8d94763427b
        parent::init();
    }
    
    /**
     * 用户开通汇付
     */
    public function IndexAction(){
        //是否开通汇付
        $huifuId = $this->objUser->huifuid;

        Base_Log::notice(array('huifuId' => $huifuId));

        if(!empty($huifuId)){
            $this->redirect('/account/overview');
        }
        
    }
}
