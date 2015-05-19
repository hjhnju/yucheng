<?php
class VerifyController extends Base_Controller_Page{
    protected $needLogin = false;

    const IMAGECODE = 'apply';
    /**
     * 需要验证的参数值
     * @var array
     */
    private $param = array(
        'amount' 	=> '贷款额度不能为空!',
        'duration' 	=> '贷款期限不能为空!',
        'name' 		=> '学校名字不能为空!',
        'realname'  => '申请人姓名不能为空!',
    );

    /**
     * @param null
     * @return 加载申请第一步页面信息
     */
    public function indexAction() {
        //获取当前用户身份
        $objUser = User_Api::checkLogin();
        $usertype = 0;
        if($objUser->usertype){
            $usertype = $objUser->usertype;
        }
        $this->getView()->assign('url',$this->webroot . '/user/imagecode/getimage?type='.self::IMAGECODE.'&timestmp='.time());
        $this->getView()->assign('usertype', $usertype);
    }

    /**
     * @param  null
     * @return  
     */
    public function submitAction() {
    	//判断用户是否是一个已经登录用户，如果没有登录要检查用户提交的注册信息，如果登录用户是一个普通用户，
    	//那么需要将提交信息注册成新的用户，并且帮助其登录
        $objUser = User_Api::checkLogin();
        //如果是一个已经登录的用户
        if($objUser) {
            //如果该登录用户不是一个申请贷款用户
            if($objUser->usertype != Apply_Type_Roles::TYPE_FINA){
                //将该用户的登录状态设置成false
                $logic = new User_Logic_Login();
                $ret   = $logic->signOut();
            }
        }
        //再次获取用户的登录状态
        $objUser = User_Api::checkLogin();
        //如果还是登录状态说明该用户是一个申请贷款用户，不需要对其进行注册
        if(!$objUser) {
            //用户是一个未登录的用户,这就需要对用户的注册信息进行验证
            $strName   = trim($_POST['name']);
            $strPasswd = trim($_POST['passwd']);
            $strCode   = isset($_POST['imagecode']) ? trim($_POST['imagecode']) : null;
            //检查验证码
            $bolRet = User_Logic_ImageCode::checkCode(self::IMAGECODE, $strCode);
            if(!$bolRet){
                return $this->ajaxError(User_RetCode::IMAGE_CODE_WRONG, 
                    User_RetCode::getMsg(User_RetCode::IMAGE_CODE_WRONG));
            }
            //注册成一个新的账户，注册里面会验证用户名和密码是否合法，返回状态已经转化成数组格式
            $objRet = User_Api::regist('fina', $strName, $strPasswd);
            if(User_RetCode::SUCCESS !== $objRet['status']){
                return $this->ajaxError($objRet->status, $objRet->statusInfo); 
            }
            //注册成功后设置用户为登录状态并将登录信息入库
            $logic   = new User_Logic_Login();
            if(!empty($strName)){
                $logic->login('name', $strName, $strPasswd);
            }elseif(!empty($strPhone)){
                $logic->login('phone', $strPhone, $strPasswd);
            }
            Base_Log::notice($_REQUEST);
            
            //注册后的系统消息
            Msg_Api::sendmsg($userid, Msg_Type::SYSTEM);
        }

    	//检查值是否合法，合法后记录到cookie，并且跳转到下一步
		if (!empty($_POST) && $this->checkParam($this->param, $_POST)) {
            $school_name = $_POST['name'];
            unset($_POST['name']);
			//记录cookie
	    	$logic = new Apply_Logic_Apply();
            if(!$logic->saveCookie($_POST)) {
                $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            }

            $logic = new Apply_Logic_School();
            if($logic->saveCookie(array('name', $school_name))) {
                $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            }

            $this->ajax(array('url' => 'apply/basic'), '', Apply_RetCode::NEED_REDIRECT);
		}

        return $this->ajaxError(Apply_RetCode::PARAM_ERROR, 
                    Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
    }
}