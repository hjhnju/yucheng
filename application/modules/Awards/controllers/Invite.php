<?php
class InviteController extends Base_Controller_Page {

    public function init(){
    	$this->setNeedLogin(false);
        parent::init();
        $this->ajax = false;
        
    }
    
	/**
     * 链接: /i/{$code}
     * mapto: /awards/invite/index?code={$code}
	 * 个人邀请链接
     * 逻辑：1.解析邀请码识别邀请人
     * 2.判断ua是移动页面还是pc页面, 选择跳转
	 */
	public function indexAction() {
        $strCode = $this->getRequest()->getParam('code');
        //解析邀请码识别邀请人
        $logic     = new Awards_Logic_Invite();
        $intUserid = $logic->decode($strCode);
        //邀请人用户名/手机号/邀请码
        $objUser = User_Api::getUserObject($intUserid);
        $name = $objUser->displayname;
        $phone = $objUser->phone;
        $data = array(
        	'invname' => !empty($name) ? $name : '',
        	'inviter' => !empty($phone) ? $phone : '',
        	'invcode' => $strCode,
        );
        //判断ua是移动页面还是pc页面, 选择跳转
        $isMobile = Base_Util_Mobile::isMobile();
        //$isMobile = true;
        if($isMobile){
            //移动端宣传页
            $this->outputView = 'url/mobile.phtml';
            $this->output($data);
        }else{
            //PC端采用注册页
            $this->outputView = APP_PATH . '/application/modules/User/views/regist/index.phtml';
            $this->output($data);
        } 
	}

}
