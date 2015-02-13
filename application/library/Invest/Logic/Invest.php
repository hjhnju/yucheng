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
     * 准备进行投标（主动投标）
     * @param integer $userid
     * @param integer $loan_id
     * @param number $amount
     * @param number $interest
     * @return boolean|string
     */
    public function invest($userid, $loan_id, $amount, $interest = 0) {
        $loan = Loan_Api::getLoanInfo($loan_id);
        if ($loan['status'] != Invest_Type_InvestStatus::LENDING) {
            Base_Log::notice('loan status is finished');
            return false; //投标已结束
        }
        
        //调用财务接口进行投标扣款 扣款成功后通过回调进行投标
        $amount = $this->formatNumber($amount);
        //detail支持投资给多个借款人，BorrowerAmt总和要等于总投资额度
        $detail = array(
            array(
                "BorrowerUserId" => $loan['user_id'],
                //TODO:是否要采用千分位方式？
                "BorrowerAmt"    => $amount,
            ),
        );
        $returl = Base_Config::getConfig('web')->root . '/invest/confirm';
        return Finance_Api::initiativeTender($loan_id, $amount, $userid, $detail, $returl);
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
     * 确认进行投标
     * @param integer $orderId 订单号
     * @param integer $userid
     * @param integer $loanId
     * @param number $amount
     * @return boolean|string
     *
     * 修改：若重复投标，算投标确认成功，返回true
     * @author hejunhua
     */
    public function doInvest($orderId, $userid, $loanId, $amount) {
        // 防order_id被多次调用 TODO：对redis的稳定性有要求
        $redis    = Base_Redis::getInstance();
        $used_key = 'invest_order_' . $orderId;
        $used     = $redis->setnx($used_key, 1);
        if (empty($used)) {
            $msg = array(
                'msg'    => 'orderid被多次调用',
                'invest' => json_encode(func_get_args()),
            );
            Base_Log::warn($msg);
            return true;
        }
        // 防并发进行投资 先确认我的资金能借出去
        $res = Loan_Api::updateLoanInvestAmount($loanId, $amount);
        if ($res !== true) {
            $this->setInvestSt($orderId, false);
            return false;
        }

        $invest          = new Invest_Object_Invest();
        $invest->userId  = intval($userid);
        $invest->loanId  = intval($loanId);
        $invest->amount  = floatval($amount);
        $objUser         = User_Api::getUserObject($userid);
        $invest->name    = $objUser->name;
        $invest->orderId = intval($orderId);
        //投资的该项目的利率和周期
        $arrData = Loan_Api::getLoanInfo($loanId);
        $invest->interest = isset($arrData['interest']) ? $arrData['interest'] : 0;
        $invest->duration = isset($arrData['duration']) ? $arrData['duration'] : 0;
        //@TODO 这里需要事务保障与前面的更新投标金额强一致性
        if (!$invest->save()) {
            Base_Log::error(array(
                'msg'    => '写入投标信息失败 不处理会导致用户资金与投标丢失',
                'invest' => json_encode($invest),
            ));
            $this->setInvestSt($orderId, true);
            return false;
        }
        // 保存以后检查满标状态
        Loan_Api::updateFullStatus($loanId);
        // 对于新手标 保存新手投标状态
        $loan = Loan_Api::getLoanInfo($loanId);
        if (!empty($loan['fresh'])) {
            $fresh = new Invest_Object_Fresh();
            $fresh->loanId = $loanId;
            $fresh->userId = $userid;
            $fresh->save();
        }

        Invest_Logic_ChkStatus::setInvestStatus($orderId, true);
        return true;
    }
    
    /**
     * 是否允许投标 如果命中某个限制策略 则不允许投标
     * @param integer $uid
     * @param integer $loanId
     * @return integer
     */
    public function allowInvest($uid, $loanId) {
        $loan = Loan_Api::getLoanInfo($loanId);
        // 已满标不允许投标
        if ($loan['status'] != Invest_Type_InvestStatus::LENDING) {
            return Invest_RetCode::NOT_ALLOWED;
        }
        // 已结束的不允许投标
        if ($loan['deadline'] < time()) {
            return Invest_RetCode::NOT_ALLOWED;
        }
        
        // 新手标不允许重复投标
        if (!empty($loan['fresh'])) {
            $fresh = new Invest_Object_Fresh();
            $fresh->userId = $uid;
            $fresh->fetch();
            
            if (!empty($fresh->id)) {
                return Invest_RetCode::FRESH_ONLY;
            }
        }
        
        return Invest_RetCode::SUCCESS;
    }
    
    /**
     * 获取借款的详情信息
     * @param integer $loanId
     * @return array
     */
    public function getLoanDetail($loanId) {
        $loan = Loan_Api::getLoanDetail($loanId);
        if (empty($loan)) {
            return $loan;
        }
        // 对打款状态 标记为满标状态
        if ($loan['status'] == Invest_Type_InvestStatus::PAYING) {
            $loan['status'] = Invest_Type_InvestStatus::FULL_CHECK;
        }
        // 对于投标时间已经结束的 进行修正
        if ($loan['status'] == Invest_Type_InvestStatus::LENDING && $loan['deadline'] < time()) {
            $loan['status'] = Invest_Type_InvestStatus::FAILED;
            $loan['status_name'] = Invest_Type_InvestStatus::getTypeName(Invest_Type_InvestStatus::FAILED);
        }
        //满标耗时
        if($loan['status'] == Invest_Type_InvestStatus::FULL_CHECK){
            $expendTime = intval($loan['full_time']) - intval($loan['start_time']);
            //$expendTime = 48 * 60 * 60 + 560;
            $day = intval($expendTime / (24 * 60 * 60));
            $hours = intval(($expendTime - $day * 24 * 60 * 60) / (60 * 60));
            $mins = intval(($expendTime - $day * 24 * 60 * 60 - $hours * 60 * 60) / 60);
            $extime = "";
            if($day > 0){
                $extime = $day . "天";
            }
            if($hours > 0){
                $extime = $extime . $hours . "小时";
            }
            if($mins > 0){
                $extime = $extime . $mins . "分";
            }
            $loan['expend_time'] = $extime;
        }
        return $loan;
    }
    
    /**
     * 所投标金额是否合法
     * @param integer $loanId
     * @param number $amount
     * @return boolean
     */
    public function isAmountLegal($loanId, $amount) {
        $loan = Loan_Api::getLoanInfo($loanId);
        $rest = $loan['amount'] - $loan['invest_amount'];
        // 投标金额超过总额
        if ($amount > $rest) {
            return false;
        }
        // 最后一标必须全部投完
        if ($amount < 100 && $amount != $rest) {
            return false;
        }
        return true;
    }
    
    /**
     * 撤销投标
     * @param integer $orderId
     * @param integer $userid
     * @param number $amount
     * @return boolean
     */
    public function cancelInvest($orderId, $userid, $amount) {
        return Finance_Api::tenderCancel($amount, $userid, $orderId);
    }
    
    /**
     * 获取用户的总投资金额
     * @param integer $uid
     * @return number
     */
    public function getUserInvestAmount($uid) {
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
     * 获取用户在当前借款可以投资的最大金额 会校验当前用户的余额
     * @param integer $uid
     * @param integer $loan_id
     * @param integer $amount
     * @return number
     */
    public function getUserCanInvest($uid, $loan_id, $amount) {
        $loan = Loan_Api::getLoanInfo($loan_id);
        $rest = $loan['amount'] - $loan['invest_amount'];
        
        // 如果本次投标小于100元，则不允许投资
        // 但是如果一次性投满则允许
        if (($amount < $rest) && ($amount < self::MIN_INVEST)) {
            return 0;
        }
        
        $user_amount = $this->getAccountAvlBal($uid);
        if ($user_amount < $amount) {
            Base_Log::notice('user balance smaller then amount');
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
    public function getUserInvests($uid, $mixStatus, $page = 1, $pagesize = 10) {
        $data = $this->objModel->getUserInvests($uid, $mixStatus, $page, $pagesize);
        
        return $data;
    }

    /**
     * 获取用户一段时间的投资总额
     * @param number $uid
     * @param number $startTime
     * @param number $endTime
     * @return number
     */
    public function getUserInvestTotal($uid, $startTime, $endTime) {
        $startTime = intval($startTime);
        $endTime = intval($endTime);
        $list = new Invest_List_Invest();
        $filters = array(
            'status' => array('(status = 5 or status = 6)'),
            'time' => array(
                "create_time >= $startTime and create_time <= $endTime", 
            ),
        );
        $list->setFilter($filters);
        $total = $list->sumField('amount');
        return $total;
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
     *      'all_invest' => $all,
     *       'all_income' => $incomes,
     *       'wait_capital' => $capital,
     *       'wait_interest' => $interest,
     *   );
     */
    public function getUserEarnings($uid) {
        //1.累计投资
        $list = new Invest_List_Invest();
        $filters = array(
            'user_id' => $uid,
            'status' => array(
                'status != ' . Invest_Type_InvestStatus::CANCEL,
                'status != ' . Invest_Type_InvestStatus::FAILED,
            ),
        );
        $list->setFilter($filters);
        $all = $list->sumField('amount');

        
        //2.累计收益
        $refunds = new Invest_List_Refund();
        $filters = array(
            'user_id' => $uid,
            'status' => Invest_Type_RefundStatus::RETURNED,
        );
        $refunds->setFilter($filters);
        $fields = array(
            'interest',
            'late_charge',
        );
        $income = $refunds->sumField($fields);
        $incomes = $income['interest'] + $income['late_charge'];
        
        //3.待收计算
        $filters = array(
            'user_id' => $uid,
            'status'  => Invest_Type_RefundStatus::NORMAL,
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
        
        //4.返回值
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
	    $keys = array('type_id', 'cat_id');
	    $filters = array();
	    foreach ($keys as $key) {
	        if (!empty($data[$key])) {
	            $filters[$key] = $data[$key];
	        }
	    }
	    $period = $this->getInvestPeriod($data);
	    if (!empty($period)) {
	        $filters['duration'][] = $period;
	    }
	    // 最低显示为已审核的借款
	    $filters['status'][] = 'status > 1';
	    return $filters;
	}
	
	/**
	 * 根据时间参数获取时间周期的过滤器
	 * @param array $data
	 * @return boolean|string|NULL
	 */
	private function getInvestPeriod($data) {
	    $from = $to = 0;
	    if (empty($data['duration'])) {
	        return false;
	    }
	    switch ($data['duration']) {
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
