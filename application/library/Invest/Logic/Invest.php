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
     * @var InvestModel
     */
    private $objModel = null;
    
    public function __construct() {
        $this->objModel = new InvestModel();
    }
    
    /**
     * 准备进行投标
     * @param integer $userid
     * @param integer $loan_id
     * @param number $amount
     * @param number $interest
     * @return boolean|string
     */
    public function invest($userid, $loan_id, $amount, $interest = 0) {
        if ($amount < self::MIN_INVEST) {
            return false;
        }
        $max = $this->getUserCanInvest($userid, $loan_id, $amount);
        if ($max < self::MIN_INVEST) {
            Base_Log::notice('max smaller then min invest :' . $max);
            return false;
        }
        
        $loan = Loan_Api::getLoanInfo($loan_id);
        if ($loan['status'] != 2) {
            Base_Log::notice('loan status is finished');
            return false; //投标已结束
        }
        
        //调用财务接口进行投标扣款 扣款成功后通过回调进行投标
        $retUrl = Base_Config::getConfig('web')->root . '/invest/confirm';
        $max = $this->formatNumber($max);
        //detail支持投资给多个借款人，BorrowerAmt总和要等于总投资额度
        $detail = array(
            array(
                "BorrowerUserId" => $loan['user_id'],
                "BorrowerAmt"    => $max,
            ),
        );
        Finance_Api::initiativeTender($loan_id, $max, $userid, $detail, $retUrl);
    }
    
    /**
     * 格式化数字为金额
     * @param number $num
     * @return string
     */
    private function formatNumber($num) {
        return sprintf('%.2f', $num);
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
     * 是否允许投标 如果命中某个限制策略 则不允许投标
     * @param integer $uid
     * @param integer $loan_id
     * @return boolean
     */
    public function allowInvest($uid, $loan_id) {
        $loan = Loan_Api::getLoanInfo($loan_id);
        if ($loan['fresh'] == 0) {
            return true;
        }
        
        $fresh = new Invest_Object_Fresh();
        $fresh->set('user_id', $uid);
        $fresh->fetch();
        
        if (empty($fresh->id)) {
            return true;
        }
        return false;
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
        return Finance_Api::getUserBalance($uid);
    }
    
    /**
     * 获取用户的总投资金额
     * @param integer $uid
     * @return number
     */
    public function getUserInvestAmount($uid) {
        $list = new Invest_List_Invest();
        $filters = array(
            'uid' => $uid,
            'status' => array(
                'status != ' . Invest_Type_InvestStatus::CANCEL,
                'status != ' . Invest_Type_InvestStatus::FAILED,
            ),
        );
        $list->setFilter($filters);
        //累计投资
        $all = $list->sumField('amount');
        return $all;
    }
    
    /**
     * 获取用户的正在回收的资产总额
     * @param integer $uid
     * @return number
     */
    public function getUserRefundsAmount($uid) {
        $refunds = new Invest_List_Refund();
        $filters = array(
            'uid' => $uid,
            'status' => Invest_Type_InvestStatus::REFUNDING,
        );
        $refunds->setFilter($filters);
        $fields = array(
            'capital',
            'interest',
        );
        $waiting = $refunds->sumField($fields);
        return array_sum($waiting);
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
            Base_Log::notice('amount smaller then min invest');
            return 0;
        }
        
        $can = min($rest, $amount);
        
        // $rest = 200
        // $can = $user_amount = 150
        // $amount = 120 则只允许 $can = 100;
        //最后需要预留100元
        $loanRest = $rest - $can;
        if (($loanRest > 0) && ($loanRest < self::MIN_INVEST)) {
            $can -= self::MIN_INVEST;
        }
        
        //如果本次投标小于100元，则不允许投资
        if ($can < self::MIN_INVEST) {
            Base_Log::notice('can smaller then min invest');
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
    
    /**
     * 获取我的投资列表 借款维度
     * @param integer $uid
     * @param integer $loan_id
     * @return array
     */
    public function getUserInvests($uid, $status, $page = 1, $pagesize = 10) {
        $data = $this->objModel->getUserInvests($uid, $status, $page, $pagesize);
        
        return $data;
    }
    
    /**
     * 获取单笔投资信息
     * @param integer $invest_id
     * @return array
     */
    public function getInvest($invest_id) {
        $invest = new Invest_Object_Invest($invest_id);
        return $invest->toArray();
    }
    
    /**
     * 获取用户累计投资收益情况
     * @param integer $uid
     * @return array <pre>(
            'all_invest' => $all,
            'all_income' => $incomes,
            'wait_capital' => $capital,
            'wait_interest' => $interest,
        );
     */
    public function getUserEarnings($uid) {
        $list = new Invest_List_Invest();
        $filters = array(
            'user_id' => $uid,
            'status' => array(
                'status != ' . Invest_Type_InvestStatus::CANCEL,
                'status != ' . Invest_Type_InvestStatus::FAILED,
            ),
        );
        $list->setFilter($filters);
        //累计投资
        $all = $list->sumField('amount');
        
        $refunds = new Invest_List_Refund();
        $filters = array(
            'user_id' => $uid,
            'status' => Invest_Type_InvestStatus::FINISHED,
        );
        $refunds->setFilter($filters);
        $fields = array(
            'interest',
            'late_charge',
        );
        $income = $refunds->sumField($fields);
        //累计收益
        $incomes = $income['interest'] + $income['late_charge'];
        
        $filters = array(
            'user_id' => $uid,
            'status' => Invest_Type_InvestStatus::REFUNDING,
        );
        $refunds->setFilter($filters);
        $fields = array(
            'capital',
            'interest',
        );
        $waiting = $refunds->sumField($fields);
        //待收本金
        $capital = $waiting['capital'];
        //待收收益
        $interest = $waiting['interest'];
        
        $data = array(
            'all_invest' => $all,
            'all_income' => $incomes,
            'wait_capital' => $capital,
            'wait_interest' => $interest,
        );
        return $data;
    }
    
    /**
     * 获取用户一定时期的投资收益情况 按月区分
     * @param number $uid
     * @param number $start
     * @param number $end
     * @return array <pre>(
            '2014-09' => 100,
            '2014-10' => 200,
        );
    */
    public function getEarningsMonthly($uid, $start = 0, $end = 0) {
        $date = date("Y-m", $start) . '-01 00:00:00';
        $stime = strtotime($date);
        $month = -1;
        
        $earns = array();
        while (true) {
            $month ++;
            $from = $this->nextMonth($stime, $month);
            $to = $this->nextMonth($stime, $month + 1);
            $date = date("Y-m", $from);
            $earns[$date] = $this->getMoneyEarn($uid, $from, $to);
            if ($to >= $end) {
                break;
            }
        }
        return $earns;
    }
    
    /**
     * 获取下几个月的时间戳
     * @param number $stime
     * @param number $month
     * @return number
     */
    private function nextMonth($stime, $month) {
        if ($month < 1) {
            return $stime;
        }
        $time = strtotime("+{$month} month", $stime);
        return $time;
    }
    
    /**
     * 指定时间段的总收益
     * @param number $uid
     * @param number $start
     * @param number $end
     * @return number
     */
    private function getMoneyEarn($uid, $start, $end) {
        $refunds = new Invest_List_Refund();
        $filters = array(
            'user_id' => $uid,
            'status' => Invest_Type_InvestStatus::FINISHED,
            'time' => array(
                'refund_time >= ' . $start,
                'refund_time < ' . $end,
            ),
        );
        $refunds->setFilter($filters);
        $fields = array(
            'interest',
            'late_charge',
        );
        $income = $refunds->sumField($fields);
        //该时间段的总收益
        $incomes = $income['interest'] + $income['late_charge'];
        return $incomes;
    }
    
    /**
     * 获取投资列表，通过filter过滤列表数据
     * @param integer $page
     * @param integer $pagesize
     * @param array $filter 过滤参数 目前支持type cat period
     * array('type' => 1, 'cat' => 1, period => 1);
     * @return array
     * array(
     *      'page' => 1,
     *      'pagesize' => 10,
     *      'pageall' => 100,
     *      'list' => array(),
     * )
     */    
    public function getInvestList($page, $pagesize, $filter) {
        $filter = $this->getInvestFilters($filter);
        $list = Loan_Api::getLoans($page, $pagesize, $filter);
        return $list;
    }
	
	/**
	 * 获取投资列表的过滤器
	 * @param array $data
	 * @return array
	 */
	private function getInvestFilters($data) {
	    $keys = array('type' => 'type_id', 'cat' => 'cat_id');
	    $filters = array();
	    foreach ($keys as $key => $val) {
	        if (!empty($data[$key])) {
	            $filters[$val] = $data[$key];
	        }
	    }
	    $period = $this->getInvestPeriod($data);
	    if (!empty($period)) {
	        $filters['period'][] = $period;
	    }
	    return $filters;
	}
	
	/**
	 * 根据时间参数获取时间周期的过滤器
	 * @param array $data
	 * @return boolean|string|NULL
	 */
	private function getInvestPeriod($data) {
	    $from = $to = 0;
	    if (empty($data['period'])) {
	        return false;
	    }
	    switch ($data['period']) {
	        case 1:
	            $from = 1;
	            $to = 90;
	            break;
	        case 2:
	            $from = 120;
	            $to = 180;
	            break;
	        case 3:
	            $from = 210;
	            $to = 360;
	            break;
	        case 4:
	            $from = 361;
	            $to = 720;
	            break;
	        case 5:
	            $from = 721;
	            break;
	    }
	    $ary = array();
	    if ($from > 0) {
	        $ary[] = "duration >= $from";
	    }
	    if ($to > $from) {
	        $ary[] = "duration <= $to";
	    }
	    if (!empty($ary)) {
	       $filter = implode(' and ', $ary);
	       return $filter;
	    }
	    return null;
	}
}