<?php 
/**
 * 每个页面左上角获取用户信息逻辑公共类
 * 
 */
class Account_Logic_UserInfo {
    
    /**
     * 返回账户中心页面左上角信息
     * @param userid 用户id
     * @return false||array=
     * (
     *     'username'
     *     'realname'=array(
     *         'realnameValue'
     *         'certType'
     *         'certValue' 
     *         'isopen' 1--开通  2--未开通
     *         'url' 跳转链接
     *      )
     *      'email'=array(
     *         'value'
     *         'isopen' 1--开通  2--未开通
     *         'url' 跳转链接
     *      )
     *      'phone'=array(
     *          'value'
     *          'isopen' 1--开通  2--未开通
     *          'url' 跳转链接
     *      )
     *      'huifu'=array(
     *          'value'
     *          'isopen' 1--开通  2--未开通
     *          'url' 跳转链接
     *      )
     *      'securedegree'=array(
     *          'score' 分数
     *          'degree' 1--低  2--中  3--高
     *          'up' 提升安全等级链接
     *      )     
     *  )
     */
    public function getUserInfo($objUser){
        $webroot = Base_Config::getConfig('web')->root;
        $ret = array();
        $userid = $objUser->userid;
        $unreadMsg = Msg_Api::getUnreadMsgNum($userid);
        if($unreadMsg === 0) {
        	$ret['unreadMsg'] = 0;
        } else {
        	$ret['unreadMsg'] = 1;
        }      
        //用户名
        $username        = $objUser->name;
        $username        = isset($username) ? $username : '';
        $ret['username'] = $username;
        
        //用户email
        $email                  = $objUser->email;
        $ret['email']['value']  = isset($email) ? $email : '';
        $ret['email']['isopen'] = isset($email) ? 1 : 2;
        $ret['email']['url']    = $webroot.'/account/secure' ;
        
        //用户手机  应该不存在手机号码不存在的情况
        $phone                  = $objUser->phone;       
        $ret['phone']['value']  = isset($phone) ? $phone : '';
        $ret['phone']['isopen'] = isset($phone) ? 1 : 2;
        $ret['phone']['url']    = $webroot.'/account/secure' ;
        
        //用户托管账户
        $huifuid                = $objUser->huifuid;
        $ret['huifu']['value']  = isset($huifuid) ? 'bnxj_'.$username : '';
        $ret['huifu']['isopen'] = isset($huifuid) ? 1 : 2;
        $ret['huifu']['url']    = $webroot.'/account/secure';
        
        //用户实名
        $realname  = $objUser->realname;
        $certType  = $objUser->certificateType;
        $certValue = $objUser->certificateContent;
        $ret['realname']['realnameValue'] = isset($realname) ? $realname : '';
        $ret['realname']['certType']      = isset($certType) ? $certType : '';
        $ret['realname']['certValue']     = isset($certValue) ? $certValue : '';
        $ret['realname']['isopen']        = (isset($realname) && isset($certValue)) ? 1 : 2;
        $ret['realname']['url'] = $webroot.'/account/secure';
        
        $param = array(
            'phone'           =>$ret['phone']['isopen'],
            'certificateInfo' =>$ret['realname']['isopen'],
            'thirdpay'        =>$ret['huifu']['isopen'],
            'email'           =>$ret['email']['isopen'],
        );
        $ret['securedegree'] = $this->scoreDegree($param);
        $ret['securedegree']['up'] = $webroot.'/account/secure';  

        //是否借款人
        $ret['isborrower'] = $objUser->isborrower;   
        return $ret;
    }
    
    /**
     * 计算该用户的安全总分与安全等级
     * @param array
     * @return array
     */
    public function scoreDegree($paramData){
        $ret = array();
        $sum = 0;
        foreach ($paramData as $k=>$v) {
            if($v == 1) {
                $sum += 25;
            }
        }
        $ret['score'] = $sum;
        if($sum==0 || $sum==25 || $sum==50) {
            $ret['degree'] = 1;
        } elseif ($sum==75) {
            $ret['degree'] = 2;
        } else {
            $ret['degree'] = 3;
        }
        return $ret;
    }
    
    /**
     * 获取用户银卡信息
     * @param string huifuid
     * @return array
     */
    public function getuserCardInfo($huifuid) {
    	$bankCardInfo = Finance_Api::queryCardInfo($huifuid);
    	if($bankCardInfo['status'] === Finance_RetCode::REQUEST_API_ERROR) {
    		Base_Log::error(array(
    			'msg' => $bankCardInfo["statusInfo"],
    		));
    		$ret = array(    				
    		    'bindbank' => 0,
    		    'banknum'  => '',
    		    'bankID'   => ''
    		);
    		return $ret;
    	}
    	if($bankCardInfo['status'] !== '000') {
    		Base_Log::error(array(
    		    'msg' => $bankCardInfo["statusInfo"],
    		));
    		$ret = array(
    			'bindbank' => 0,
    			'banknum'  => '',
    			'bankID'   => ''
    		);
    		return $ret;
    	}
    	if($bankCardInfo['status'] === Finance_RetCode::NOTBINDANYCARD) {
    		Base_Log::error(array(
    		    'msg' => $bankCardInfo["statusInfo"],
    		));
    		$ret = array(
    			'bindbank' => 0,
    			'banknum'  => '',
    			'bankID'   => ''
    		);
    		return $ret;
    	}
    	$ret = array(
			'bindbank' => 1,
			'banknum'  => $bankCardInfo["data"]['UsrCardInfolist'][0]["CardId"],
			'bankID'   => $bankCardInfo["data"]['UsrCardInfolist'][0]["BankId"],
    	);
    	return $ret;
    }
} 