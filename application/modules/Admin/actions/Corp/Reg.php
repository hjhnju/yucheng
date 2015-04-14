<?php
/**
 * 企业开户
 * @author hejunhua
 *
 */
class RegAction extends Yaf_Action_Abstract {
    public function execute() {

        if (!empty($_POST)) {
            $username = isset($_POST['username']) ? trim($_POST['username']) : null;
            $passwd   = isset($_POST['password']) ? trim($_POST['password']) : null;
            $phone    = isset($_POST['phone']) ? trim($_POST['phone']) : null;
            $busicode = isset($_POST['busicode']) ? trim($_POST['busicode']) : null;
            $corpname = isset($_POST['corpname']) ? trim($_POST['corpname']) : '';
            
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

            User_Api::saveCorpInfo($userid, $arrInfo);

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
        }


    }
}