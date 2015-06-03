<?php
/**
 * @file 主要计算贷款信息
 */
class Apply_Logic_Calculate {
	//风险等级
	private $_level;

	//贷款总额
	private $_amount;

	//融资服务费比例
	private $_service = 0.005;

	//贷款期限
	private $_duration;

	//贷款期限类型，天或者月
	private $_duration_type;

	//利率
	private $_rate;

	/**
	 * 构造函数，用于初始化数据
	 * @param [type] $amount        [总额]
	 * @param [type] $duration      [贷款期限]
	 * @param [type] $duration_type [贷款期限类型]
	 * @param string $rate          [贷款的利率]
	 * @param string $level         [贷款的风险等级]
	 */
	public function __construct($amount, $duration, $duration_type, $rate='0.13', $level='AA') {
		$this->setAmount($amount);
		$this->setDuration($duration);
		$this->setDurationType($duration_type);
		$this->setLevel($level);
		$this->setRate($rate);
	}

	/**
	 * 根据不同的风险级别，设置不同的百分比
	 * @return [type] [返回所有百分比设置]
	 */
	public function serviceChargeSettings(){
		$settings = array(
			'C'	=> array(
				'venture' => '2.50',
				'account' => '0.45',
			),
			'B'	=> array(
				'venture' => '2.00',
				'account' => '0.40',
			),
			'A'	=> array(
				'venture' => '1.50',
				'account' => '0.35',
			),
			'AA'	=> array(
				'venture' => '1.00',
				'account' => '0.30',
			),
			'AAA'	=> array(
				'venture' => '0.50',
				'account' => '0.25',
			)
		);

		return $settings;
	}

	/**
	 * 根据不同的风险级别得到相应的服务费比例
	 */
	public function getSettings(){
		$settings = $this->serviceChargeSettings();
		$setting  = $settings[$this->_level];
		foreach($setting as $key=>$value){
			$setting[$key] = $value/100;
		}

		return $setting;
	}

	/**
	 * 设置贷款的风险级别
	 */
	public function setLevel($value){
		$this->_level = $value;
	}

	/**
	 * 设置贷款的期限
	 */
	public function setDuration($value){
		$this->_duration = $value;
	}

	/**
	 * 设置贷款的期限类型
	 */
	public function setDurationType($value){
		$this->_duration_type = $value;
	}

	/**
	 * 设置贷款的期限类型
	 */
	public function setAmount($value){
		$this->_amount = floatval($value);
	}

	/**
	 * 设置贷款利率
	 */
	public function setRate($value){
		$this->_rate = $value;
	}

	/**
	 * 获取服务费
	 * @return [type] [总的服务费用]
	 */
	public function getService(){
		//根据风险级别得出相应的费用比例
		$setting = $this->getSettings();
		//服务费计算公式 总额 * 融资服务费 + 总额 * 账户管理费 + 总额 * 风险准备金
		//如果借款类型是按天，则：
		if($this->_duration_type == Apply_Type_Duration::MONTH){
			$venture = $this->_amount * ($setting['venture']/12);
			$account = $this->_amount * ($setting['account']);
		}else {
			$venture = $this->_amount * ($setting['venture']/365);
			$account = $this->_amount * ($setting['account']/30);
		}
		//融资服务费 
		$service = $this->_amount * $this->_service;
		// $total   = $service + ($account * $this->_duration) + ($venture * $this->_duration);
		$total   = $service + ($account * $this->_duration);

		return number_format($total, 2);
	}

	/**
	 * 实际到账的金额
	 */
	public function getActualAmount() {
		$data = $this->_amount - $this->getService();
		return number_format($data, 2);
	}

	/**
	 * 月平均利息
	 * @return [type] [description]
	 */
	public function getInterestMonth() {
		if($this->_duration_type == Apply_Type_Duration::MONTH){
			//利率一般都是年利率，所以要除以12
			$data = $this->_amount * ($this->_rate/12);
		}else {
			//利率一般都是年利率，所以要除以365,然后乘以一个月的天数，现在默认一个月的天数是30天，一年是365天
			$data = $this->_amount * ($this->_rate/365) * 30;
		}

		return number_format($data, 2);
	}

	/**
	 * 月平均还款，因为是等额本息的方式所以计算方法如下
	 */
	public function getRefundMonth() {
		if($this->_duration_type == Apply_Type_Duration::MONTH){
			//月平均还款 = 总额/期限 + 每个月的利息
			$data = $this->_amount/$this->_duration + $this->getInterestMonth();
		}else {
			//月平均还款 = 总额/期限*30 + 每个月的利息
			$data = $this->_amount/$this->_duration * 30 + $this->getInterestMonth();
		}

		return number_format($data, 2);
	}
}