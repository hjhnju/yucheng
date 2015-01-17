<?php
class InviteController extends Base_Controller_Response {

    public function init(){
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

        //获取邀请人手机号
        //$objUser = User_Api::getUserObject($userid);
        $objUser = $this->objUser;
        $name = $objUser->name;
        $name = isset($name) ? $name : '';
        $phone = $objUser->phone;
        $phone = isset($phone) ? $phone :'';
        $code = $strCode;
        $uid = $intUserid;
        $data = array(
        	'name'  => $name,
        	'phone' => $phone,
        	'code'  => $code,
        	'uid'   => $uid,
        );
        //判断ua是移动页面还是pc页面, 选择跳转
        $isMobile = Base_Util_Mobile::isMobile();
        if(!$isMobile){//TODO:add ! for test
            //移动端宣传页
            $this->outputView = 'url/mobile.phtml';
            $this->output($data);
        }else{
            //PC端采用注册页
            $this->outputView = APP_PATH . '/application/modules/User/views/regist/index.phtml';
            $this->output($data);
        }
	}

    public function testAction(){
        $userid = 123;
        $inviterid = 124;
        $ret = Awards_Api::registNotify($userid, $inviterid);
        echo $ret;
    }

}
