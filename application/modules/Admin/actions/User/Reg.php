<?php
/**
 * 添加私人用户
 * @author huwei
 *
 */
class RegAction extends Yaf_Action_Abstract {
    public function execute() {
        if(!empty($_POST) && !empty($_REQUEST['add'])){  //添加图像，注册成天使
            $objUser  = new User_Object_Login($_REQUEST['add']);
            $objUser->usertype = User_Type_Roles::TYPE_ANGEL;
            $ret1 = $objUser->save();
            $userInfo = new User_Object_Info();
            $userInfo->fetch(array('userid'=>$_REQUEST['add']));
            $ext = substr($_FILES['file']['name'], -3);
            if (!in_array($ext, array('jpg', 'gif', 'png'))) {
                return '上传文件类型错误';
            }
             
            $hash = md5(microtime(true));
            $hash = substr($hash, 8, 16);
            $filename = $hash . '.jpg';
             
            $oss = Oss_Adapter::getInstance();
            $res = $oss->writeFile($filename, $_FILES['file']['tmp_name']);
            if ($res) {
                @unlink($_FILES['file']['tmp_name']);
                $headurl =  Base_Util_Image::getUrl($hash);
            }
            $userInfo->headurl = $headurl;
            $ret2 = $userInfo->save();
            return $ret1&&$ret2;
        }
        if (!empty($_POST)) {
            $username = isset($_POST['username']) ? trim($_POST['username']) : null;
            $passwd   = isset($_POST['password']) ? trim($_POST['password']) : null;
            $phone    = isset($_POST['phone']) ? trim($_POST['phone']) : null;
            $busicode = isset($_POST['busicode']) ? trim($_POST['busicode']) : null;
            $corpname = isset($_POST['corpname']) ? trim($_POST['corpname']) : '';
            $headurl  = isset($_POST['headurl']) ? trim($_POST['headurl']) : '';
            $type     = isset($_POST['type']) ? trim($_POST['type']) : 2;//默认企业用户注册
            if($type == User_Type_Roles::TYPE_CORP ){
                //企业开户
                $arrRet = User_Api::regist('corp', $username, $passwd, $phone);
                if(Base_RetCode::SUCCESS !== $arrRet['status']){
                    $this->getView()->assign('error_msg', $arrRet['statusInfo']);
                    return;
                }
                $userid = $arrRet['data']['userid'];
                //保存企业基本信息
                $arrInfo = array(
                    'corpname' => $corpname,
                    'busicode' => $busicode,
                );
                
                //TODO: User_Api::saveCorpInfo($userid, $arrInfo);
                
                Base_Log::notice(array('msg'=>'To corp reg.',
                'userid'   => $userid,
                'username' => $username,
                'passwd'   => $passwd,
                'phone'    => $phone,
                'busicode' => $busicode,
                'corpname' => $corpname,
                ));
                //跳转至汇付开企业户
                Finance_Api::corpRegist($userid, $username, $busicode, $corpname);
            }elseif( $type == User_Type_Roles::TYPE_ANGEL ){
            	if (empty($_FILES['file'])) {
            		return false;
            	}
            	
            	$ext = substr($_FILES['file']['name'], -3);
            	if (!in_array($ext, array('jpg', 'gif', 'png'))) {
            		return '上传文件类型错误';
            	}
            	
            	$hash = md5(microtime(true));
            	$hash = substr($hash, 8, 16);
            	$filename = $hash . '.jpg';
            	
            	$oss = Oss_Adapter::getInstance();
            	$res = $oss->writeFile($filename, $_FILES['file']['tmp_name']);
            	if ($res) {
            		@unlink($_FILES['file']['tmp_name']);
            		$headurl =  Base_Util_Image::getUrl($hash);        		
            	}
                $userLogin = new User_Object_Login();
                $userLogin->name     = $username;
                $userLogin->phone    = $phone;
                $userLogin->passwd   = Base_Util_Secure::encrypt($passwd);
                $userLogin->usertype = User_Type_Roles::TYPE_ANGEL;
                $ret1 = $userLogin->save();
                
                $userInfo = new User_Object_Info();               
                $userInfo->headurl = $headurl;
                $userInfo->userid  = $userLogin->userid;
                $ret2 = $userInfo->save();
                return $ret1&&$ret2;
            }     
        }
    }
}