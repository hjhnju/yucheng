<?php
/**
 * 微站邀请页
 */
class InviteController extends Base_Controller_Page {

    protected $loginUrl = '/m/login';

    public function init(){
        $this->setNeedLogin(true);
        
        parent::init();
    }
    
    /**
     * /m/invite
     * @assign   
     */
    public function indexAction() {
        $name  = $this->objUser->displayname;
        $phone = $this->objUser->phone;
        $data  = array(
            'invname' => !empty($name) ? $name : '',
            'inviter' => !empty($phone) ? $phone : '',
        );
        $this->getView()->assign(array('data'=>$data));
    }

}
