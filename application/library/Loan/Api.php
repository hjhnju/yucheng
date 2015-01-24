<?php
/**
 * 借款模块API接口
 * @author jiangsongfang
 *
 */
class Loan_Api {

    /**
     * 生成借款标的还款计划
     * @param integer $lid
     */
    public static function buildRefunds($loan_id) {
    }
    
    public static function buildInvestRefunds($invest_id) {
        
    }
    
    private static function sendMoney($from, $to) {
        
    }
    
    /**
     * 对成功的借款进行打款
     * @param integer $loan_id
     * @return boolean
     */
    public static function sendLendMoney($loan_id) {
        if (empty($loan_id)) {
            return false;
        }

        $logic = new Loan_Logic_Loan();
        $loan = $logic->getLoanInfo($loan_id);
        $res = false;
        if ($loan['status'] == Loan_Type_LoanStatus::PAYING) {
            $res = $logic->sendMoney($loan_id);
        }
        
        if ($res) {
            $content = "给客户打款成功";
            self::AddLog($loan_id, $content);
        } else {
            $content = "给客户打款失败";
            self::AddLog($loan_id, $content);
        }
        return $res;
    }
    
    /**
     * 借款成功
     * @param integer $loan_id
     * @return boolean
     */
    public static function lendSuccess($loan_id) {
        if (empty($loan_id)) {
            return false;
        }
        
        $logic = new Loan_Logic_Loan();
        $loan = $logic->getLoanInfo($loan_id);
        $res = false;
        if ($loan['status'] == Loan_Type_LoanStatus::FULL_CHECK) {
            $res = $logic->lendSuccess($loan_id);
        }
        
        if ($res) {
            $content = "生成还款计划成功";
            self::AddLog($loan_id, $content);
        } else {
            $content = "生成还款计划失败";
            self::AddLog($loan_id, $content);
        }
        
        return $res;
    }
    
    /**
     * 获取某借款的还款计划列表
     * @param integer $loan_id
     * @return array
     */
    public static function getLoanRefunds($loan_id) {
        $refunds = new Loan_List_Refund();
        $filters = array('loan_id' => $loan_id);
        $refunds->setFilter($filters);
        $refunds->setPagesize(PHP_INT_MAX);
        return $refunds->toArray();
    }
    
    /**
     * 添加借款日志记录
     * @param integer $loan_id
     * @param string $content
     * @return boolean
     */
    public static function AddLog($loan_id, $content) {
        $log = new Loan_Object_Log();
        $log->loanId = $loan_id;
        $log->ip = Base_Util_Ip::getClientIp();
        $log->content = $content;
        $log->userId = 0;
        return $log->save();
    }
    
    /**
     * 获取用户的借款列表
     * @param number $uid
     * @param number $page
     * @param number $pagesize
     * @return Array
     */
    public static function getUserLoans($uid, $page = 1, $pagesize = 10, $filters = array()) {
        $list = new Loan_List_Loan();
        $list->setPage($page);
        $list->setPagesize($pagesize);
        
        $filters['user_id'] = $uid;
        $list->setFilter($filters);
        return $list->toArray();
    }
    
    /**
     * 获取借款列表
     * @param number $page
     * @param number $pagesize
     * @param array $filters
     * @return array
     */
    public static function getLoans($page = 1, $pagesize = 10, $filters = array()) {
        $list = new Loan_List_Loan();
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list->setFilter($filters);
        
        $type = new Loan_Type_LoanType();
        $cat = new Loan_Type_LoanCat();
        $safe = new Loan_Type_SafeMode();
        $refund = new Loan_Type_Refund();
        $duration = new Loan_Type_Duration();
        
        $list->joinType($type, 'type_id');
        $list->joinType($cat, 'cat_id');
        $list->joinType($refund, 'refund_type');
        $list->joinType($duration, 'duration');
        
        $data = $list->toArray();
        foreach ($data['list'] as $key => $row) {
            $data['list'][$key] = self::formatLoan($row);
        }
        return $data;
    }
    
    /**
     * 获取借款本身的信息 不包含附加表信息
     * @param integer $loan_id
     * @return array
     */
    public static function getLoanInfo($loan_id) {
        $loan = new Loan_Object_Loan($loan_id);
        $data = $loan->toArray();
        
        return $data;
    }
    
    /**
     * 获取借款的应还款总额
     * @param number $loanId
     * @return number
     */
    public static function getLoanRefundAmount($loanId) {
        $logic = new Loan_Logic_Loan();
        return $logic->getLoanRefundAmount($loanId);
    }
    
    /**
     * 格式化借款数据
     * @param array $data
     * @return array
     */
    private static function formatLoan($data) {
        $data['amount_rest'] = $data['amount'] - $data['invest_amount'];
        $data['amount'] = number_format($data['amount'], 2);
        $data['invest_amount'] = number_format($data['invest_amount'], 2);
        $data['percent'] = number_format($data['invest_amount'] / $data['amount'], 2);
        $data['days'] = self::getDays($data['duration']);

        $safe = new Loan_Type_SafeMode();
        $refund = new Loan_Type_RefundType();
        $safe_ids = explode(',', $data['safe_id']);
        foreach ($safe_ids as $safeid) {
            $data['safemode'][$safeid] = $safe->getTypeName($safeid);
        }
        $data['refund_typename'] = $refund->getTypeName($data['refund_type']);
        
        $duration = new Loan_Type_Duration();
        $data['duration_name'] = $duration->getTypeName($data['duration']);
        if ($data['duration'] < 15) {
            $data['duration_day'] = $data['duration'];
            $data['duration_type'] = '天';
        } elseif ($data['duration'] == 15) {
            $data['duration_day'] = '半';
            $data['duration_type'] = '个月';
        } else {
            $data['duration_day'] = $data['duration'] / 30;
            $data['duration_type'] = '个月';
        }
        return $data;
    }
    
    /**
     * 更新借款当前的状态
     * @param integer $loan_id
     * @param integer $status
     * @return boolean
     */
    public static function updateLoanStatus($loan_id, $status) {
        $loan = new Loan_Object_Loan($loan_id);
        $loan->status = $status;
        $res = $loan->save();
        if ($res) {
            $type = new Loan_Type_LoanStatus();
            $content = "更新借款状态为" . $type->getTypeName($status);
            self::AddLog($loan_id, $content);
        }
        return $res;
    }
    
    /**
     * 更新借款的投标金额，如果投标总金额小于借款金额则成功，否则失败
     * @param integer $loan_id
     * @param number $amount
     * @return boolean
     */
    public static function updateLoanInvestAmount($loan_id, $amount) {
        $model = new LoanModel();
        return $model->updateInvestAmount($loan_id, $amount);
    }
    
    /**
     * 获取借款详情 按前端需要进行分组
     * @param integer $loan_id
     * @return array
     */
    public static function getLoanDetail($loan_id) {
        $data = self::getLoanInfo($loan_id);
        $data = self::formatLoan($data);
        
        $type = new Loan_Type_LoanType();
        $cat = new Loan_Type_LoanCat();
        $data['loan_type'] = $type->getTypeName($data['type_id']);
        $data['loan_cat'] = $cat->getTypeName($data['cat_id']);

        $cond = array('loan_id' => $loan_id);
        $company = new Loan_Object_Company($cond);
        $data['company'] = $company->toArray();
        
        $counter = new Loan_Object_Counter($data['user_id']);
        $data['counter'] = $counter->toArray();
        
        $guarantee = new Loan_Object_Guarantee($cond);
        $data['guarantee'] = $guarantee->toArray();
        
        $audits = new Loan_List_Audit();
        $audits->setFilter($cond);
        $audits_data = $audits->toArray();
        $data['audit'] = self::stepArray($audits_data['list'], 'type');

        $attachs = new Loan_List_Attach();
        $attachs->setFilter($cond);
        $attachs_data = $attachs->toArray();
        $data['attach'] = self::stepArray($attachs_data['list'], 'type');
        
        $data['refunds'] = self::getRefunds($loan_id);
        //$invests = Invest_Api::getLoanInvests($loan_id);
        //$data['invest'] = $invests['list'];
        
        return $data;
    }
    
    /**
     * 获取周期对应的总天数
     * @param number $duration
     * @return number
     */
    private static function getDays($duration) {
        if ($duration < 30) {
            return $duration;
        }
        
        $periods = $duration / 30;
        //@todo 对于半个月的处理
        $date = new DateTime('today');
        $start = $date->getTimestamp();
        $date->modify('+' . $periods . 'months');
        
        $time = $date->getTimestamp() - $start;
        $days = ceil($time / 3600 / 24);
        
        return $days;
    }
    
    public static function getRefunds($loan_id) {
        $logic = new Loan_Logic_Loan();
        $refunds = $logic->getRefunds($loan_id);
        return $refunds;
    }
    
    /**
     * 将数组按照某个字段进行分级
     * @param array $data
     * @param string $key
     * @return array
     */
    private static function stepArray($data, $key) {
        $ary = array();
        foreach ($data as $row) {
            $ary[$row[$key]][] = $row;
        }
        return $ary;
    }
}