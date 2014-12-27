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
        $list->joinType($type, 'type_id');
        $list->joinType($cat, 'cat_id');
        $list->joinType($safe, 'safe_id');
        $list->joinType($refund, 'refund_type');
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
        $data = self::formatLoan($data);
        
        return $data;
    }
    
    /**
     * 格式化借款数据
     * @param array $data
     * @return array
     */
    private static function formatLoan($data) {
        $data['amount'] = number_format($data['amount'], 2);
        $data['invest_amount'] = number_format($data['invest_amount'], 2);
        
        $duration = new Loan_Type_Duration();
        $data['duration_name'] = $duration->getTypeName($data['duration']);
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
        $data['percent'] = number_format($data['invest_amount'] / $data['amount'], 2);
        
        $type = new Loan_Type_LoanType();
        $cat = new Loan_Type_LoanCat();
        $safe = new Loan_Type_SafeMode();
        $refund = new Loan_Type_RefundType();
        $data['loan_type'] = $type->getTypeName($data['type_id']);
        $data['loan_cat'] = $cat->getTypeName($data['cat_id']);
        $data['safemode'] = $safe->getTypeName($loan->safeId);
        $data['refund_typename'] = $refund->getTypeName($data['refund_type']);

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
        
        //$invests = Invest_Api::getLoanInvests($loan_id);
        //$data['invest'] = $invests['list'];
        
        return $data;
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