<?php
/**
 * @file 保存上传文件
 */
class Apply_Logic_Attach extends Apply_Logic_Base {
	/**
	 * 可以为空的字段
	 */
	protected $_except = array('id', 'create_time', 'update_time', 'title', 'status');
	/**
	 * 保存files
	 * @param  array  $param [需要保存的数据信息]
	 * $param = array(
	 * 	'apply_id' => 1,
	 * 	'userid' => 23,
	 * 	'type'	=> '1',
	 * 	'title' => '',
	 * 	'url'   => '0d0565946dafee05', 文件的hash
	 * );
	 * @return [type]        [true|false(错误的标准格式)]
	 */
	public function saveFile($param = array()){
		$objRet = new Base_Result();
		//用户不合法
		if(!$this->checkApplyId($param['apply_id'])) {
			$objRet->status = Apply_RetCode::ILLEGAL_USER;
			$objRet->statusInfo = Apply_RetCode::getMsg($objRet->status);
			return $objRet->format();
		}
		$attach = Apply_Object_Attach::init($param);
		//保存失败
		if(!$attach->save() || !$this->checkParams($param)){
			$objRet->status = Apply_RetCode::PARAM_ERROR;
			$objRet->statusInfo = Apply_RetCode::getMsg($objRet->status);
			return $objRet->format();
		}
		
		$objRet->status = Apply_RetCode::SUCCESS;
		return $objRet->format();
	}

	/**
	 * 检验当前的apply id是否是合法的
	 * @param  string $apply_id [apply id]
	 * @return [type]           [true|false]
	 */
	public function checkApplyId($apply_id = ''){
		$ret = false;
		//检查用户是否是一个登录用户，并且该登录用户类型必须是一个融资用户
		$objUser = User_Api::checkLogin();
		//解密apply id
		$apply_id = $apply_id;
		//检验该apply的作者是否是当前用户
		$list = new Apply_List_Apply();
		$list->setFilter(array('id'=>$apply_id, 'userid'=>$objUser->userid));
		if($list->getTotal() && $objUser->usertype == User_Type_Roles::TYPE_FINA) {
			$ret = true;
		}

		return $ret;
	}
}