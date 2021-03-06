<?php
class Loan_Logic_Loan {
    
    protected $objModel;
    
    public function __construct() {
        $this->objModel = new LoanModel();
    }

    /**
     * 发布借款, 默认发布后状态为立即开启，
     * @param integer $loanId 借款ID
     * @param integer $isOpen 默认发布后状态为“立即开启”，＝1时开启投标
     * @return Base_Result
     */
    public function publish($loanId, $isOpen = 0, $days = 7) {

        $objRst = new Base_Result();

        $db = Base_Db::getInstance('xjd');
        $db->beginTransaction();

        $loan = new Loan_Object_Loan($loanId);
        //时间戳投标开始时间(选用创建时设定的起至时间)
        $loan->startTime = time();
        //时间戳投标截止时间
        $loan->deadline  = time() + $days * 24 * 3600;
        
        //项目所在地
        $area    = new Area_Object_Area($loan->area);
        $proArea = $area->huifuCityid;
        //应还款日期
        $duration = new Loan_Type_Duration();
        $retDate  = $duration->getTimestamp($loan->duration, $loan->deadline);
        //总还款金额
        $retAmt  = Loan_Api::getLoanRefundAmount($loanId);

        if($isOpen >= 1){
            $loan->status = Loan_Type_LoanStatus::LENDING;
            //调用财务API进行借款录入
            $arrRst  = Finance_Api::addBidInfo($loanId, $loan->userId, $loan->amount, 
            $loan->interest/100, $loan->refundType, $loan->startTime, 
            $loan->deadline, $retAmt, $retDate, $proArea);
            if ($arrRst['status'] !== Base_RetCode::SUCCESS) {
                $objRst->status     = $arrRst['status'];
                $objRst->statusInfo = $arrRst['statusInfo'];
                Base_Log::warn($arrRst);
                return $objRst;
            }
            //订单号
            $loan->orderId = intval($arrRst['data']['orderId']);
        }else if($isOpen <= -1) {
            $loan->status = Loan_Type_LoanStatus::AUDIT;
        }else if($isOpen === 0) {
            $loan->status = Loan_Type_LoanStatus::WAITING;
        }

        if (!$loan->save()) {
            $objRst->status     = Loan_RetCode::LOAN_SAVE_FAIL;
            $objRst->statusInfo = Loan_RetCode::getMsg(Loan_RetCode::LOAN_SAVE_FAIL);
            return $objRst;
        }

        $db->commit();

        $objRst->status = Base_RetCode::SUCCESS;
        return $objRst;
    }

    /**
     * 满标打款
     * @param $loanId 
     */
    public function makeLoans($loanId){
        $objRst = new Base_Result();
        if (empty($loanId)) {
            $objRst->status     = Base_RetCode::getMsg($objRst->status);
            return $objRst->format();
        }

        $logic       = new Loan_Logic_Loan();
        $arrLoanInfo = $logic->getLoanInfo($loanId);
        $bolRet      = true;
        if ($arrLoanInfo['status'] !== Loan_Type_LoanStatus::FULL_PAYING) {
            $objRst->status     = Loan_RetCode::UNABLE_MAKE_LOAN;
            $objRst->statusInfo = Loan_RetCode::getMsg($objRst->status);
            Base_Log::notice(array(
                'msg'    => '该笔投资无需再打款',
                'info'   => $arrLoanInfo,
                'bolRet' => $bolRet,
            ));
            return $objRst;
        }

        //借款人（入款用户）
        $inUserId = intval($arrLoanInfo['user_id']);
        //获取该项目所有投资
        $arrRet   = Invest_Api::getLoanInvests($loanId);
        foreach($arrRet['list'] as $arrInfo){
            $bolRet1 = $this->singleMakeLoans($loanId, $inUserId, $arrInfo);
            $bolRet  = $bolRet && $bolRet1;
        }
        
        if (!$bolRet) {
            $objRst->status     = Loan_RetCode::MAKE_LOAN_FAIL;
            $objRst->statusInfo = Loan_RetCode::getMsg(Loan_RetCode::MAKE_LOAN_FAIL);
            $content            = "给客户打款失败";
            Base_Log::error(array(
                'msg'    => $content,
                'loanId' => $loanId,
            ));
            return $objRst;
        }

        Loan_Api::updateLoanStatus($loanId, Loan_Type_LoanStatus::REFUNDING);

        $objRst->status = Base_RetCode::SUCCESS;
        $content = "给客户打款成功";
        Base_Log::notice(array(
            'msg'    => $content,
            'loanId' => $loanId,
        ));
        $this->addLog($loanId, $content);
        
        return $objRst;
    }

    /**
     * 单笔打款失败
     */
    private function singleMakeLoans($loanId, $inUserId, $arrInvestInfo){
        $bolRet = false;

        $investId      = $arrInvestInfo['id'];
        $subOrdId      = $arrInvestInfo['order_id'];
        $outUserId     = $arrInvestInfo['user_id'];
        $transAmt      = $arrInvestInfo['amount'];
        $singlePayStat = $arrInvestInfo['status'];
        //是否需要打款
        if($singlePayStat >= Invest_Type_InvestStatus::REFUNDING){
            $bolRet = true;
            Base_Log::debug(array(
                'msg'    => '该笔投资已成功或结束，不能再打款',
                'info'   => $arrInvestInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        //通知财务打款
        $arrRet = Finance_Api::loans($loanId, $subOrdId, $inUserId, $outUserId, $transAmt);
        if(Base_RetCode::SUCCESS !== $arrRet['status']){
            $bolRet = false;
            Base_Log::error(array(
                'msg'    => '财务满标打款单笔失败',
                'info'   => $arrInvestInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        //单笔打款成功，更新投资人已打款字段
        $bolRet = Invest_Api::updateInvestStatus($investId, Invest_Type_InvestStatus::REFUNDING);
        if(!$bolRet){
            Base_Log::error(array(
                'msg'    => '更新打款状态失败',
                'info'   => $arrInvestInfo,
                'bolRet' => $bolRet,
            ));
            return $bolRet;
        }

        Base_Log::notice(array(
            'msg'    => '满标打款单笔成功',
            'info'   => $arrInvestInfo,
            'bolRet' => $bolRet,
        ));
        //短信通知,都统一放到回调函数中发送
        //$arrArgs    = array('JK_'.$loanId, $transAmt);
        //$tplid      = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        //$objOutUser = User_Api::getUserObject($outUserId);
        //$bResult    = Base_Sms::getInstance()->send($objOutUser->phone, $tplid[4], $arrArgs);

        return $bolRet;
    }

    /**
     * 添加借款日志记录
     * @param integer $loanId
     * @param string $content
     * @return boolean
     */
    public function addLog($loanId, $content) {
        $log          = new Loan_Object_Log();
        $log->loanId  = $loanId;
        $log->ip      = Base_Util_Ip::getClientIp();
        $log->content = $content;
        //userId是谁
        $log->userId  = 0;
        return $log->save();
    }
    
    /**
     * 借款成功 创建还款与收款计划
     * @param integer $loanId
     * @return boolean
     */
    public function lendSuccess($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        if (empty($loan->id)) {
            Base_Log::error(array(
               'loanid' => $loanId,
               'loanid2' => $loan->id,
	    ));
            return false;
        }
        
        $res = $this->objModel->beginTransaction();
        if (!$res) {
            return false;
        }
        $logic = new Loan_Logic_Refund();
        $res   = $logic->buildRefunds($loanId);
        if (empty($res)) {
            Base_Log::error(array(
                'res' => $res,
                'loanid' => $loanId,
            ));
            $this->objModel->rollback();
            return false;
        }
        
        //循环创建收款计划
        $invests = Invest_Api::getLoanInvests($loanId);
        $invests = $invests['list'];
        foreach ($invests as $invest) {
            $res = Invest_Api::buildRefunds($invest['id']);
            
            if (empty($res)) {
                Base_Log::error(array(
		    'res' => $res,
		    'loanid' => $loanId,
		    'investid' => $invest['id'],
                ));
                $this->objModel->rollback();
                return false;
            }
        }
        
        //更新状态
        $loan->status = Loan_Type_LoanStatus::REFUNDING;
        
        return $this->objModel->commit();
    }
    
    /**
     * 获取借款基本信息
     * @param integer $loanId
     * @return array
     */
    public function getLoanInfo($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        $data = $loan->toArray();
        
        return $data;
    }
    
    /**
     * 获取借款的还款总额
     * @param number $loanId
     * @return number
     */
    public function getLoanRefundAmount($loanId) {
        $loan = new Loan_Object_Loan($loanId);
        if ($loan->payTime > 0) {
            $start = $loan->payTime;
        } else {
            $start = time();
        }
        
        $duration = new Loan_Type_Duration();
        
        if ($loan->refundType === Loan_Type_RefundType::AVERAGE) {
            $months = $duration->getMonths($loan->duration);
            $interest = Loan_Type_RefundType::getInterest($loan->refundType, $loan->amount, $loan->interest, $months);
        } elseif ($loan->refundType === Loan_Type_RefundType::MONTH_INTEREST) {
            $days = $duration->getDays($loan->duration, $start);
            $interest = Loan_Type_RefundType::getInterest($loan->refundType, $loan->amount, $loan->interest, $days);
        }
        return $loan->amount + $interest;
    }
    
    /**
     * 格式化借款数据
     * @param array $data
     * @return array
     */
    private function formatLoan($data) {
        $data['amount'] = number_format($data['amount'], 2);
        $data['invest_amount'] = number_format($data['invest_amount'], 2);
        
        $duration = new Loan_Type_Duration();
        $data['duration_name'] = $duration->getTypeName($data['duration']);
        return $data;
    }

}
