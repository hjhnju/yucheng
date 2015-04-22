<?php
/**
 * 财务模块封装汇付逻辑实现类
 * @author lilu
 */
class Finance_Api {

    /**
     * 验证签名
     */
    public static function verifySign($arrFields, $arrParams, $sign){
        $chinapnr  = Finance_Chinapnr_Client::getInstance();
        $originStr = $chinapnr->getSignContent($arrParams, $arrFields);
        $bolRet    = $chinapnr->verify($originStr, $sign);
        Base_Log::notice(array(
            'msg'    => '验证签名',
            'bolRet' => $bolRet,
        ));
        return $bolRet;
    }

    /**
     * 获取平台账户各种余额
     * @return $array array("AvlBal":"10.00" , "AcctBal":"10.00" , "FrzBal":"10.00")
     */
    public static function getPlatformBalance(){
        $logic   = new Finance_Logic_Query();
        $arrAcct = $logic->queryAccts();
        Base_Log::notice(array(
            'msg' => '获取平台账户各种余额',
            'mdt' => $arrAcct,
        ));
        $acct = Base_Config::getConfig('huifu.acct.MDT1', CONF_PATH.'/huifu.ini');
        return $arrAcct[$acct]['AvlBal'];
    }


    /**
     * 获取用户可用余额Finance_Api::getUserAvlBalance
     * @param int userid
     * @return array || false
     */
    public static function getUserAvlBalance($userid) {
        $userid = intval($userid);
        if($userid <= 0) {
            Base_Log::error(array(
                'msg'    => '请求参数错误',
                'userid' => $userid,
            ));
            return false;
        }
        $logic  = new Finance_Logic_Query();
        $avlBal = $logic->getUserAvlBalance($userid);
        Base_Log::notice(array(
            'avlBal' => $avlBal,
            'userid' => $userid,
         ));
        return $avlBal;
    }

    /**
     * 获取用户各种余额Finance_Api::getUserBalance
     * @param int userid
     * @return array 出错时，各值返回0.00
     * array('AvlBal'=>可用余额,
     *      'AcctBal' => 账户余额,
     *      'FrzBal'  => 冻结金额,)
     */
    public static function getUserBalance($userid) {
        $logic  = new Finance_Logic_Query();
        $arrBal = $logic->getUserBalance($userid);
        Base_Log::notice(array_merge(array('userid'=>$userid), $arrBal));
        return $arrBal;
    }

    /**
     * 系统转账给用户
     * @param $inUserId 入账用户
     * @param $transAmt 金额
     */
    public static function transfer($inUserId, $transAmt){
        $inUserId = intval($inUserId);
        $transAmt = floatval($transAmt);
        if($inUserId<=0 || $transAmt<=0.00) {
            Base_Log::error(array(
                'msg'      => '请求参数错误',
                'userid'   => $inUserId,
                'transAmt' => $transAmt,
            ));
            return false;
        }

        $outUserId   = Base_Config::getConfig('huifu.merCustId', CONF_PATH . '/huifu.ini');
        $outAcctId   = Base_Config::getConfig('huifu.acct.MDT1', CONF_PATH . '/huifu.ini');
        $transLogic = new Finance_Logic_Transaction();
        $ret = $transLogic->transfer($outUserId, $outAcctId, $transAmt,
            $inUserId, Finance_Order_Type::RECE_AWD);
        if(!$ret || $ret['RespCode'] !== '000') {
            Base_Log::error(array(
                'msg'   => '转账失败',
                'param' => $ret,
            ));
            return false;
        }
        return true;
    }

    /**
     * 添加标的信息接口 Finance_Api::addBidInfo
     * @param int proId 标的唯一标示
     * @param int borrUserId 借款人uid
     * @param float borrTotAmt 借款总金额
     * @param float yearRate 年利率
     * @param int retType 还款方式   1等额本息  2等额本金  3按期付息，到期还本   4一次性还款   99其他
     * @param int bidStartDate 时间戳投标开始时间
     * @param int bidEndDate 时间戳投标截止时间
     * @param float retAmt 总还款金额
     * @param int retDate 应还款日期
     * @param int proArea 项目所在地
     * @return 标准数组格式Base_Result::format
     * data => orderId 借款订单号
     *
     */
    public static function addBidInfo($loanId, $borrUserId,$borrTotAmt,$yearRate,$retType,$bidStartDate,
        $bidEndDate,$retAmt,$retDate,$proArea) {

        $transLogic = new Finance_Logic_Transaction();
        $objRst     = $transLogic->addBidInfo($loanId, $borrUserId,$borrTotAmt,$yearRate,$retType,
            $bidStartDate,$bidEndDate,$retAmt,$retDate,$proArea);
        if(Base_RetCode::SUCCESS !== $objRst->status) {
            Base_Log::error(array(
                'msg'          => $objRst->statusInfo,
                'loanId'       => $loanId,
                'borrUserId'   => $borrUserId,
                'borrTotAmt'   => $borrTotAmt,
                'yearRate'     => $yearRate,
                'retType'      => $retType,
                'bidStartDate' => $bidStartDate,
                'bidEndDate'   => $bidEndDate,
                'retAmt'       => $retAmt,
                'retDate'      => $retDate,
                'proArea'      => $proArea,
            ));
            return $objRst->format();
        }
        Base_Log::notice(array(
            'msg'          => '添加标的信息成功',
            'loanId'       => $loanId,
            'borrUserId'   => $borrUserId,
            'borrTotAmt'   => $borrTotAmt,
            'yearRate'     => $yearRate,
            'retType'      => $retType,
            'bidStartDate' => $bidStartDate,
            'bidEndDate'   => $bidEndDate,
            'retAmt'       => $retAmt,
            'retDate'      => $retDate,
            'proArea'      => $proArea,
        ));
        return $objRst->format();
    }

    /**
     * 银行卡查询接口 Finance_Api::queryCardInfo
     * @param String $userCustId 用户客户号(required)
     * @param String $carId 开户银行账号(optional)
     *
     * @return API 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
     * status=0 请求成功 返回正常信息
     * status=Base_RetCode::PARAM_ERROR  参数错误(1.userCustId查无此人 2.$userCustId或$carId格式出错 )
     * status=  该用户无此卡(有其他的卡)
     * status=  该用户没有绑定任何银行卡
     * status=  请求API出错
     * data=array(
     *     0=>array(
     *         'MerCustId'商户客户号
     *         'UsrCustId'用户客户号
     *         'UsrName'真实名称
     *         'CertId'证件号码
     *         'BankId'银行代号
     *         'CardId'开户银行账号
     *         'RealFlag'银行卡是否实名
     *         'UpdDateTime'时间
     *         'ProvId'银行省份
     *         'AreaId'银行地区
     *         'IsDefault'是否默认
     *     )
     *     1=>(...)
     *     2=>(...)
     *     ...
     * )
     *
     */
    public static function queryCardInfo($userCustId,$cardId='') {
        $ret        = new Base_Result();
        $queryLogic = new Finance_Logic_Query();
        $return     = $queryLogic->queryBankCard($userCustId,$cardId);
        if($return == false) {
            $ret->status            = Finance_RetCode::REQUEST_API_ERROR;
            $ret->data              = array();
            $ret->statusInfo        = Finance_RetCode::getMsg($ret->status);

            $logParam               = array();
            $logParam['msg']        = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
            $logParam['userCustId'] = $userCustId;
            $logParam['cardId']     = $cardId;

            Base_Log::error($logParam);

            return $ret->format();
        }
        if($return['RespCode'] != '000') {
            $ret->status     = $return['RespCode'];
            $ret->data       = $return;
            $ret->statusInfo = $return['RespDesc'];

            $logParam        = array();
            $logParam['msg'] = $return['RespDesc'];
            $logParam        = array_merge($logParam,$return);
            Base_Log::error($logParam);

            return $ret->format();
        }
        if (empty($return['UsrCardInfolist'])) {
            $ret->status     = Finance_RetCode::NOTBINDANYCARD;
            $ret->data       = array();
            $ret->statusInfo = Finance_RetCode::getMsg($ret->status);

            $logParam        = array();
            $logParam['msg'] = Finance_RetCode::getMsg(Finance_RetCode::NOTBINDANYCARD);
            $logParam        = array_merge($logParam,$return);
            Base_Log::notice($logParam);

            return $ret->format();
        }
        $ret->status = $return['RespCode'];
        $ret->data = $return;

        Base_Log::notice($return);

        return $ret->format();
    }

    /**
     * 删除银行卡接口
     * @param string huifuid
     * @param string cardId
     * @return array
     * status:0   删除成功
     * status:Finance_RetCode::REQUEST_API_ERROR 请求汇付API接口失败
     * status:汇付返回值的RespCode
     * data=array(
     *     'huifuid'
     *     'cardId'
     * )
     */
    public static function delCard($huifuid,$card) {
        if(!isset($huifuid) || empty($huifuid) || !isset($card) || empty($card)) {
            Base_Log::error(array(
                'msg' => '请求参数错误',
                'huifuid' => $huifuid,
                'card'    => $card,
            ));
        }
        $ret             = new Base_Result();
        $userManageLogic = new Finance_Logic_UserManage();
        $return          = $userManageLogic->delCard($huifuid,$card);
        if($return == false) {
            $ret->status     = Finance_RetCode::REQUEST_API_ERROR;
            $ret->data       = array();
            $ret->statusInfo = Finance_RetCode::getMsg($ret->status);

            $logParam        = array();
            $logParam['msg'] = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
            Base_Log::error($logParam);

            return $ret->format();
        }
        if ($return['RespCode'] != "000") { //汇付返回值为非正常处理结构
            $ret->status     = $return['RespCode'];
            $ret->data       = $return;
            $ret->statusInfo = $return['RespDesc'];

            $logParam        = array();
            $logParam['msg'] = $return['RespDesc'];
            $logParam        = array_merge($logParam,$return);
            Base_Log::error($logParam);

            return $ret->format();
        }
        $ret = array(
            'status' => $return['RespCode'],
            'data'   => $return,
        );

        Base_Log::notice($return);

        return $ret->format();
    }

    /**
     * 主动投标接口 Finance_Api::initiativeTender
     * @param int proId 借款ID
     * @param float transAmt 交易金额(required)
     * @param int usrid 用户ID(required)
     * @param float maxTenderRate 最大投资手续费率(required)
     * @param array BorrowerDetails 借款人信息(required)
     *        array(
     *            0 => array(
     *                "BorrowerUserId":借款人userid
     *                "BorrowerAmt": "20.01"， 借款金额
     *                "BorrowerRate":"0.18" 借款手续费率(必须)
     *            )
     *            1 =>array(
     *                ...
     *                ...
     *                ...
     *            )
     *            ...
     *        )
     * @param boolean $IsFreeze 是否冻结(required) true--冻结false--不冻结
     * @param string $FreezeOrdId 冻结订单号(optional)
     * @return false || redirect
     * )
     *
     */
    public static function initiativeTender($loanId, $transAmt, $userid ,$borrowerDetails,
        $retUrl='', $vocherAmt=0.00) {
        if(!isset($loanId) || empty($loanId) || !isset($transAmt) || empty($transAmt) ||
           !isset($userid) || empty($userid) || !isset($borrowerDetails) || empty($borrowerDetails)) {
            Base_Log::error(array(
                'msg'             => '请求参数错误',
                'loanId'          => $loanId,
                'transAmt'        => $transAmt,
                'userid'          => $userid,
                'borrowerDetails' => $borrowerDetails,
            ));
            return false;
        }
        $transLogic = new Finance_Logic_Transaction();
        Base_Log::notice(array(
            'loanId'          => $loanId,
            'transAmt'        => $transAmt,
            'userid'          => $userid,
            'borrowerDetails' => $borrowerDetails,
            'retUrl'          => $retUrl,
        ));

        $transLogic->initiativeTender($loanId, $transAmt, $userid, $borrowerDetails, $retUrl, $vocherAmt);
    }

    /**
     * 投标撤销接口Finance_Api::tenderCancel
     * @param String transAmt 交易金额    (required)
     * @param String usrid 用户id   (required)
     * @param boolean isUnFreeze 是否解冻(require)  true--解冻  false--不解冻
     * @param String UnFreezeOrdId 解冻订单号(optional) 解冻订单号
     * @return bool true--撤销成功  false--撤销失败
     */
    public static function tenderCancel($transAmt,$userid,$orderId,$retUrl='') {
        if(!isset($transAmt) || !isset($userid) || !isset($orderId)) {
            Base_Log::error(array(
                'msg'       => '请求参数错误',
                'transAmt'  => $transAmt,
                'userid'    => $userid,
                'orderId'   => $orderId,
                'retUrl'    => $retUrl,
            ));
        }
        $transLogic = new Finance_Logic_Transaction();
        Base_Log::notice(array(
            'transAmt'  => $transAmt,
            'userid'    => $userid,
            'orderId'   => $orderId,
            'retUrl'    => $retUrl,
        ));
        $transLogic->tenderCancel($transAmt,$userid,$orderId,$retUrl);
    }

    /**
     * 满标打款接口 Finance_Api::loans
     * @param $loanId 借款项目ID
     * @param $subOrdId 对应投标的orderId
     * @param $inUserId 入账的userid
     * @param $outUserId 出账的userid
     * @param $transAmt 该笔打款金额
     * @return bool true--打款成功  false--打款失败
     *
     */
    public static function loans($loanId,$subOrdId,$inUserId,$outUserId,$transAmt) {
        $transLogic = new Finance_Logic_Transaction();
        $objRst     = $transLogic->loans($loanId, $subOrdId, $inUserId, $outUserId, $transAmt);
        Base_Log::notice(array(
            'msg'  => '财务满标打款接口',
            'args' => func_get_args(),
            'ret'  => $objRst->format(),
        ));
        return $objRst->format();
    }

    /**
     * 还款接口Finance_Api::Repayment
     * @param string refundId 单笔投资回款计划id
     * @param string outUserId 出账账户号：还款人的uid
     * @param string inUserId 入账账户号：投资人的uid
     * @param string subOrdId 关联的投标订单号
     * @param float transAmt 交易金额(包含逾期给投资人的罚息)
     * @param int loanId
     * @param float mangFee 逾期需要缴纳的管理费用
     * @return 接口统一Json格式
     *
     */
     public static function repayment($refundId, $outUserId,$inUserId,$subOrdId,$transAmt,$loanId,$mangFee = 0.00) {
        if(!isset($outUserId) || !isset($inUserId) ||!isset($subOrdId) || !isset($transAmt) ||
           !isset($loanId) ) {
            Base_Log::error(array(
                'msg'  => '请求参数错误',
                'args' => func_get_args(),
            ));
            $objRst = new Base_Result();
            $objRst->status = Base_RetCode::PARAM_ERROR;
            return $objRst->format();
        }
        $transLogic = new Finance_Logic_Transaction();
        $objRst     = $transLogic->repayment($refundId, $outUserId, $inUserId,
            $subOrdId, $transAmt, $loanId, $mangFee);
        if(Base_RetCode::SUCCESS !== $objRst->status) {
            Base_Log::error(array(
                'msg'       => $objRst->statusInfo,
                'refundId'  => $refundId,
                'outUserId' => $outUserId,
                'inUserId'  => $inUserId,
                'subOrdId'  => $subOrdId,
                'transAmt'  => $transAmt,
                'loanId'    => $loanId,
                'mangFee'   => $mangFee,
            ));
            return $objRst->format();
        }
        Base_Log::notice(array(
            'msg'       => '还款接口成功',
            'refundId'  => $refundId,
            'outUserId' => $outUserId,
            'inUserId'  => $inUserId,
            'subOrdId'  => $subOrdId,
            'transAmt'  => $transAmt,
            'loanId'    => $loanId,
            'mangFee'   => $mangFee,
        ));
        return $objRst->format();
     }

     /**
      * 封装汇付天下API实现用户绑卡功能(由Fiance模块controller层转入调用)Finance_Api::userBindCard
      * @param String $UsrCustId 用户客户号(必须)
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR 参数错误
      * status=API调用失败
      * data=array(
      *         'CmdId' 消息类型
      *         'RespCode' 应答返回码
      *         'RespDesc' 应答描述
      *         'MerCustId' 商户客户号
      *         'UsrCustId' 用户客户号
      *         'BgRetUrl' 商户后台应答地址
      *         'ChkValue' 签名
      *         'OpenAcctId' 开户银行账号
      *         'OpenBankId' 开户银行代号
      *         'TrxId' 本平台交易唯一标识
      *         'MerPriv' 商户私有域
      *      )
      *
      */
     public static function userBindCard($userCustId){
         if(!isset($userCustId) || empty($usrCustId)) {
            Base_Log::error(array(
                'msg'        => '请求参数错误',
                'userCustId' => $userCustId,
            ));
         }
         $userManageLogic = new Finance_Logic_UserManage();
         Base_Log::notice(array(
            'userCustId' => $userCustId,
         ));
         $userManageLogic->userBindCard($userCustId,$userid);
     }

     /**
      * 封装汇付天下API实现企业开户功能(由Fiance模块controller层转入调用)
      * @param String $BusiCode 营业执照编号(必须)
      * @param String $userId 用户号
      * @param String $UsrName 真实名称
      * @param String $InstuCode 组织机构代码
      * @param String $TaxCode 税务登记号
      * @param String $GuarType 担保类型
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR 参数错误
      * status=API调用失败
      * data=array(
      *         'CmdId' 消息类型(必须)
      *         'RespCode' 应答返回码 (必须)
      *         'RespDesc' 应答描述 (必须)
      *         'MerCustId' 商户客户号 (必须)
      *         'UsrId' 用户号(必须)
      *         'AuditStat' 审核状态 (必须)
      *         'TrxId' 本平台交易唯一标识 (必须)
      *         'BgRetUrl' 商户后台应答地址 (必须)
      *         'ChkValue' 签名 (必须)
      *         'UsrName' 真实名称
      *         'UsrCustId' 用户客户号
      *         'AuditDesc' 审核状态描述
      *         'MerPriv' 商户私有域
      *         'OpenBankId' 开户银行代号
      *         'CardId' 开户银行账号
      *         'RespExt' 返参扩展域
      *     )
      *
      */
     public static function corpRegist($userid,$userName,$busiCode,$instuCode='',$taxCode=''){
         $userManageLogic = new Finance_Logic_UserManage();
         $userManageLogic->corpRegist($userid,$userName,$busiCode,$instuCode='',$taxCode='');
     }


     /**
      * 商户代取现接口
      * @param int userid
      * @param float transAmount
      * @return bool
      *
      */
     public static function merCash($userid,$transAmt) {
         $transLogic = new Finance_Logic_Transaction();
         $ret = $transLogic->merCash($userid,$transAmt);
         if($ret === false) {
             Base_Log::error(array(
                 'msg'      => Base_RetCode::getMsg(Base_RetCode::PARAM_ERROR),
                 'userid'   => $userid,
                 'transAmt' => $transAmt,
             ));
             return false;
         }
         if(is_null($ret)) {
             Base_Log::error(array(
                 'msg'      => '请求汇付API错误',
                 'userid'   => $userid,
                 'transAmt' => $transAmt,
             ));
             return false;
         }
         $respCode = $ret['RespCode'];
         $respDesc = $ret['RespDesc'];
         if($respCode !== '000') {
             $logParam = $ret;
             $logParam['msg'] = $respDesc;
             Base_Log::error($logParam);
             return false;
         }
         Base_Log::notice($ret);
         return true;
     }
     /**
      * 用户登录汇付login接口
      * redirect
      */
     public static function userLogin($userCustId) {
         if(!isset($userCustId) || empty($userCustId)) {
             Base_Log::error(array(
                 'msg'       => '请求参数错误',
                 'usrCustId' => $userCustId,
             ));
         }
         $userManageLogic = new Finance_Logic_UserManage();
         Base_Log::notice(array(
             'usrCustId' => $userCustId,
         ));
         $userManageLogic->userLogin($userCustId);
     }

     /**
      * 用户信息修改接口
      * redirect
      */
     public static function acctModify($userCustId) {
         if(!isset($userCustId) || empty($userCustId)) {
             Base_Log::error(array(
                 'msg' => '请求参数错误',
                 'usrCustId' => $userCustId,
             ));
         }
         $userManageLogic = new Finance_Logic_UserManage();
         Base_Log::notice(array(
             'userCustId' => $userCustId,
         ));
         $userManageLogic->userLogin($userCustId);
     }

}
