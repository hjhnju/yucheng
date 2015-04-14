<?php
/**
 * 微站 开通第三方
 */
class OpenController extends Base_Controller_Page {

    protected $loginUrl = '/m/login';

    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
    
    
    /**
     * 开通第三方
     *
     * /m/open
     * @param     
     * @return    
     */
    public function indexAction() {
       $this->getView()->assign('title', "开通汇付天下");
    }

    /**
     * 注册成功
     *
     * /m/open
     * @param
     * @return
     */
    public function successAction() {
        $this->getView()->assign('title', "注册成功");
        $sucHit = (int)Base_Redis::getInstance()->hGet('reg_success_hset', $this->userid);
        if($sucHit === 1){
            $this->getView()->assign('hit', 1);
        }else{
            Base_Redis::getInstance()->hSet('reg_success_hset', $this->userid, 1);
        }
    }
 

}
