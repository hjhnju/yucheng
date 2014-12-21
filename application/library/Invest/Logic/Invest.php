<?php
/**
 * Invest业务逻辑层
 * @author jiangsongfang
 *
 */
class Invest_Logic_Invest {
    /**
     * 最低投标金额
     * @var integer
     */
    const MIN_INVEST = 100;
    
    /**
     * 准备进行投标
     * @param integer $uid
     * @param integer $loan_id
     * @param number $amount
     * @param number $interest
     * @return boolean|string
     */
    public function invest($uid, $loan_id, $amount, $interest = 0) {
        if ($amount < self::MIN_INVEST) {
            return false;
        }
        $max = $this->getUserCanInvest($uid, $loan_id, $amount);
        if ($max < self::MIN_INVEST) {
            return false;
        }
        
        //调用财务接口进行投标扣款 扣款成功后通过回调进行投标
        //$url = Finance_Api::initiativeTender($transAmt, $usrCustId, $maxTenderRate);
        $url = '/invest/confirm';
        return $url;
    }
    
    /**
     * 准备进行投标
     * @param integer $uid
     * @param integer $loan_id
     * @param number $amount
     * @return boolean|string
     */
    public function doInvest($uid, $loan_id, $amount) {
        if ($amount < self::MIN_INVEST) {
            return false;
        }
        $max = $this->getUserCanInvest($uid, $loan_id, $amount);
        if ($max < $amount) {
            $this->cancelInvest($uid, $amount);
            return false;
        }
        //防并发进行投资
        $res = Loan_Api::updateLoanInvestAmount($loan_id, $amount);
        if ($res === true) {
            $invest = new Invest_Object_Invest();
            $invest->amount = $amount;
            $invest->loanId = $loan_id;
            //@todo 获取用户名
            $invest->name = '';
            $invest->userId = $this->getUserId();
            if (!$invest->save()) {
                Base_Log::error(json_encode($invest), '写入投标信息失败');
            }
        } else {
            $this->cancelInvest($uid, $amount);
            return false;
        }
        return true;
    }
    
    /**
     * 获取登录用户ID
     * @return number
     */
    private function getUserId() {
        //@todo 获取用户ID
        return 1;
    }
    
    /**
     * 撤销投标
     * @param integer $uid
     * @param number $amount
     * @return boolean
     */
    public function cancelInvest($uid, $amount) {
        return true;
    }
    
    /**
     * 获取用户的可用余额
     * @param integer $uid
     * @return number
     */
    public function getUserAmount($uid) {
        return 1000;
    }
    
    /**
     * 获取用户的总投资金额
     * @param integer $uid
     * @return number
     */
    public function getUserInvestAmount($uid) {
        return 1000;
    }
    
    /**
     * 获取用户在当前借款可以投资的最大金额
     * @param integer $uid
     * @param integer $loan_id
     * @param integer $amount
     * @return number
     */
    public function getUserCanInvest($uid, $loan_id, $amount) {
        //如果本次投标小于100元，则不允许投资
        if ($amount < self::MIN_INVEST) {
            return 0;
        }
        
        $loan = Loan_Api::getLoanInfo($loan_id);
        $rest = $loan['amount'] - $loan['invest_amount'];
        $user_amount = $this->getUserAmount($uid);
        $amount = min($amount, $user_amount);
        if ($amount < self::MIN_INVEST) {
            return 0;
        }
        
        $can = min($rest, $amount);
        
        // $rest = 200
        // $can = $user_amount = 150
        // $amount = 120 则只允许 $can = 100;
        //最后需要预留100元
        if (($rest - $can) < self::MIN_INVEST) {
            $can -= self::MIN_INVEST;
        }
        
        //如果本次投标小于100元，则不允许投资
        if ($can < self::MIN_INVEST) {
            $can = 0;
        }
        
        return $can;
    }
    
    /**
     * 获取借款的投资列表
     * @param integer $loan_id
     * @param integer $page
     * @param integer $pagesize
     * @return array
     */
    public function getLoanInvests($loan_id, $page = 1, $pagesize = PHP_INT_MAX) {
        $invest = new Invest_List_Invest();
        $invest->setFilter(array('loan_id' => $loan_id));
        $invest->setPage($page);
        $invest->setPagesize($pagesize);
        
        return $invest->toArray();
    }
    
    /**
     * 获取我的投资列表
     * @param integer $uid
     * @param integer $loan_id
     * @return array
     */
    public function getUserLoanInvest($uid, $loan_id) {
        $invest = new Invest_List_Invest();
        $filters = array(
            'loan_id' => $loan_id,
            'user_id' => $uid,
        );
        $invest->setFilter($filters);
        $invest->setPagesize(PHP_INT_MAX);
        
        return $invest->toArray();
    }
}