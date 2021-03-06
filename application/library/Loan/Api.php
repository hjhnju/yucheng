<?php
/**
 * 借款模块API接口
 * @author jiangsongfang
 *
 */
class Loan_Api {

    /**
     * 满标时审核是否可以打款
     */
    /*public function fullPassAudit($loanId){
        $objLoan = new Loan_Object_Loan($loanId);
        if($objLoan->status === Loan_Type_LoanStatus::FULL_CHECK){
            $objLoan->status = Loan_Type_LoanStatus::FULL_PAYING;
            return $objLoan->save();
        }
        return false;
    }*////不再调用

    /**
     * 还款操作
     * @param $refundId 还款计划id
     * @param $loanId 借款id
     */
    public function doRefund($refundId, $loanId){
        $logic = new Loan_Logic_Refund();
        $objRst = $logic->doRefund($refundId, $loanId);
        return $objRst->format();
    }

    /**
     * 发布借款
     * @param integer $loanId 借款ID
     * @return boolean
     */
    public function publish($loanId, $isOpen = 0, $days = 7) {
        $logic  = new Loan_Logic_Loan();
        $objRst = $logic->publish($loanId, $isOpen, $days);
        return $objRst->format();
    }
    
    /**
     * 对成功的借款进行打款
     * @param integer $loan_id
     * @return boolean
     */
    public static function makeLoans($loan_id) {
        $logic  = new Loan_Logic_Loan();
        $objRst = $logic->makeLoans($loan_id);
        return $objRst->format();
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
        //状态已经在打款时变为还款中
        if ($loan['status'] == Loan_Type_LoanStatus::REFUNDING) {
            $res = $logic->lendSuccess($loan_id);
        }
        
        if ($res) {
            $content = "生成还款计划成功";
            self::addLog($loan_id, $content);
        } else {
            $content = "生成还款计划失败";
            self::addLog($loan_id, $content);
        }

        Base_Log::notice(array(
	    'loanid' => $loan_id,
	    'beginstatus' => $loan['status'],
            'res' => $res,
        ));
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
    public static function addLog($loan_id, $content) {
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
    public static function getLoans($page = 1, $pagesize = 10, $filters = array(), $strFilter = '') {
        $list = new Loan_List_Loan();
        $list->setPage($page);
        $list->setPagesize($pagesize);
        if(!empty($filters) && is_array($filters)){
            $list->setFilter($filters);
        }
        if(!empty($strFilter)){
            $list->appendFilterString($strFilter);
        }
        
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
        if (!$loan->isLoaded()) {
            return array();
        }
        
        $data = $loan->toArray();
        $data['amount'] = floatval($data['amount']);
        $data['invest_amount'] = floatval($data['invest_amount']);

        Base_Log::debug(array('loan_id'=> $loan_id, 'data'=>$data));
        
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
        $data['percent']       = floor(100 * $data['invest_amount'] / $data['amount']);
        $data['amount_rest']   = $data['amount'] - $data['invest_amount'];
        $data['amount']        = number_format($data['amount'], 2);
        $data['invest_amount'] = number_format($data['invest_amount'], 2);
        $data['days']          = self::getDays($data['duration']);

        $safe     = new Loan_Type_SafeMode();
        $refund   = new Loan_Type_RefundType();
        $status   = new Loan_Type_LoanStatus();
        $safe_ids = explode(',', $data['safe_id']);
        foreach ($safe_ids as $safeid) {
            $data['safemode'][$safeid] = $safe->getTypeName($safeid);
        }
        $data['refund_typename'] = $refund->getTypeName($data['refund_type']);
        $data['status_name']     = $status->getTypeName($data['status']);
        
        $duration = new Loan_Type_Duration();
        $data['duration_name'] = $duration->getTypeName($data['duration']);
        if ($data['duration'] < 15) {
            $data['duration_day'] = $data['duration'];
            $data['duration_type'] = '天';
        } elseif ($data['duration'] == 15) {
            $data['duration_day'] = '15';
            $data['duration_type'] = '天';
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
            self::addLog($loan_id, $content);
        }
        return $res;
    }

    /**
     * 更新借款回款计划的状态
     * @param integer $loan_id
     * @param integer $status
     * @return boolean
     */
    public static function updateLoanRefundStatus($refundId, $status) {
        $refund = new Loan_Object_Refund($refundId);
        $refund->status = $status;
        if($status === Loan_Type_Refund::REFUNDED){
            $refund->refundTime = time();
        }
        $res = $refund->save();
        return $res;
    }


    /**
     * 更新借款的投标金额，如果投标总金额小于借款金额则成功，否则失败
     * @param integer $loanId
     * @param number $amount
     * @return boolean
     */
    public static function updateLoanInvestAmount($loanId, $amount) {
        $model = new LoanModel();
        return $model->updateInvestAmount($loanId, $amount);
    }
    
    /**
     * 如果有必要的话 更新满标状态
     * @param integer $loanId
     * @return boolean
     */
    public static function updateFullStatus($loanId) {

        $loan = new Loan_Object_Loan($loanId);
        if ($loan->investAmount > $loan->amount) {
            Base_Log::error($loan, '投标金额错误');
        }

        if ($loan->investAmount >= $loan->amount) {
            //Add:不再需要满标审核，只要满标即可放款
            $loan->status   = Loan_Type_LoanStatus::FULL_CHECK;
            $loan->status   = Loan_Type_LoanStatus::FULL_PAYING;
            $loan->fullTime = time();
            if (!$loan->save()) {
                Base_Log::error($loan, '更改满标状态错误');
                return false;
            }
            return true;
        }
        return false;
    }
    
    /**
     * 获取借款详情 按前端需要进行分组
     * @param integer $loan_id
     * @return array
     */
    public static function getLoanDetail($loan_id) {
        $data = self::getLoanInfo($loan_id);
        if (empty($data)) {
            return $data;
        }
        $data = self::formatLoan($data);
        
        $type = new Loan_Type_LoanType();
        $cat = new Loan_Type_LoanCat();
        $level = new Invest_Type_LevelName();
        $data['loan_type'] = $type->getTypeName($data['type_id']);
        $data['loan_cat'] = $cat->getTypeName($data['cat_id']);
        $data['level_name'] = $level->getTypeName($data['level']);

        //判断个人or学校借款
        $catId = intval($data['cat_id']);
        if($catId === Loan_Type_LoanCat::TEACHER){
            $cond = array('loan_id' => $loan_id);
            $private = new Loan_Object_Private($cond);
            $data['private'] = $private->toArray();
        }elseif ($catId === Loan_Type_LoanCat::SCHOOL) {
            $cond = array('loan_id' => $loan_id);
            $company = new Loan_Object_Company($cond);
            $data['company'] = $company->toArray();
            //城市转换为省份信息显示
            $area = new Area_Object_Area($data['company']['area']);
            if ($area->province !== 0) {
                $area = new Area_Object_Area($area->province);
            }
            // 对于学校不对外显示 //去除Base_Util_Secure::hideDetail($data['company']['school']);，采用人工加＊
            $data['company']['school'] = $data['company']['school'];
            $data['company']['area_name'] = $area->name;
        }

        $guarantee = new Loan_Object_Guarantee($cond);
        if($guarantee->isLoaded()){
            $data['guarantee'] = $guarantee->toArray();
            $data['guarantee']['name'] = $data['guarantee']['name'];
            //Base_Util_Secure::hideDetail($data['guarantee']['name']);
        }

        $counter = new Loan_Object_Counter($data['user_id']);
        if($counter->isLoaded()){
            $data['counter'] = $counter->toArray();
        }
        
        $audits = new Loan_List_Audit();
        $audits->setFilter($cond);
        $audits_data = $audits->toArray();
        $data['audit'] = self::stepArray($audits_data['list'], 'type', Loan_Type_Audit::$names);

        $attachs = new Loan_List_Attach();
        $attachs->setFilter($cond);
        $attachs_data = $attachs->toArray();
        foreach ($attachs_data['list'] as $key => $row) {
            $attachs_data['list'][$key]['hash'] = $row['url'];
            //$attachs_data['list'][$key]['url'] = Base_Util_Image::getUrl($row['url']);
            if($row['type'] === Loan_Type_Attach::CONTRACT){
                $attachs_data['list'][$key]['thumb'] = Base_Util_Image::getUrl($row['url'], 130*3, 180*3);
            }else if($row['type'] === Loan_Type_Attach::ENTITY){
                $attachs_data['list'][$key]['thumb'] = Base_Util_Image::getUrl($row['url'], 250, 180);
            }else{
                $attachs_data['list'][$key]['thumb'] = Base_Util_Image::getUrl($row['url'], 192, 144);
            }
        }
        $data['attach'] = self::stepArray($attachs_data['list'], 'type', Loan_Type_Attach::$names);
        
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
        $logic   = new Loan_Logic_Refund();
        $refunds = $logic->getRefunds($loan_id);
        return $refunds;
    }
    
    /**
     * 根据用户id获取最近待回款的回款计划
     * @param $userId
     */
    public static function getActiveRefunds($userId){
        $logic = new Loan_Logic_Refund();
        $refunds = $logic->getActiveRefunds($userId);
        return $refunds;
    }

    /**
     * 将数组按照某个字段进行分级
     * @param array $data
     * @param string $key
     * @return array
     */
    private static function stepArray($data, $key, $map = array()) {
        $ary = array();
        foreach ($data as $row) {
            $newKey = isset($map[$row[$key]]) ? $map[$row[$key]] : $row[$key];
            $ary[$newKey][] = $row;
        }
        return $ary;
    }
}
