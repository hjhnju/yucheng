<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_Apply extends Apply_Logic_Base {
	/**
	 * 可以为空的字段
	 */
	protected $_except = array('id', 'create_time', 'update_time', 'start_time', 'end_time', 'rate');
	/**
	 * @param  null
	 * @return 
	 */
	public function saveApply() {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('apply');
		$objUser = User_Api::checkLogin();
		if($objUser) {
			$cookies['userid'] = $objUser->userid;
		}
		$cookies['status'] = Apply_Type_Status::AUDIT;
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		//开始存储信息，此处要过滤cookie中的值是否是合法的
		$apply = Apply_Object_Apply::init($cookies);
		if ($apply->save()) {
			$objRet = new Base_Result(Apply_RetCode::SUCCESS, array('id'=>$apply->id), Apply_RetCode::getMsg(Apply_RetCode::SUCCESS));
            return $objRet->format();
        }else {
            return $this->errorFormat();
	    }
	}

	/**
	 * @param 需要保存到cookie中的数据
	 * @return null
	 */
	public function saveCookie($param) {
		//如果没有通过验证
		if(!$this->checkParams($param)) {
			return false;
		}
		$fields = $this->getProperties();
		Apply_Cookie::save($param, $fields);

		return true;	
	}

	/**
	 * @param  null
	 * @return 返回apply表中的字段
	 */
	public function getProperties() {
		$object = new Apply_Object_Apply();
		$fields = $object->properties;

		return $fields;
	}

    /**
     * 获得申请列表
     * @param $page 当前页数
     * @param $pagesize 每页多少条
     * @return 获得申请列表
     */
    public function getApplyList($page=1, $pagesize=10, $filter=array()){
    	$objApply = new Apply_List_Apply();
        if(!empty($filter)) {
            $objApply->setFilter($filter);
        }
    	$objApply->setPage($page);
    	$objApply->setPagesize($pagesize);
    	$objApply->setOrder('create_time desc');
    	$data = $objApply->toArray();
    	foreach($data['list'] as $key=>$item) {
    		$tmpData = array();
    		$tmpData['apply'] = $this->getDataItem($item);
    		//得到学校信息
    		$objSchool = new Apply_List_School();
    		$objSchool->setFilter(array('apply_id' => $item['id']));
            $logicSchool = new Apply_Logic_School();
    		$tmpData['school'] = $logicSchool->getDataItem($objSchool->getData());
    		
    		//得到个人信息
    		$objPersonal = new Apply_List_Personal();
    		$objPersonal->setFilter(array('apply_id' => $item['id']));
            $logicPersonal = new Apply_Logic_Personal();
    		$tmpData['personal'] = $logicPersonal->getDataItem($objPersonal->getData());

            //得到股权信息
            $objStock = new Apply_List_Stock();
            $objStock->setFilter(array('apply_id' => $item['id']));
            $logicStock = new Apply_Logic_Stock();
            $tmpData['stock'] = $logicStock->getDataItem($objStock->getData());

    		$data['list'][$key] = $tmpData;
    	}
    	return $data;
    }

    /**
     * 只会返回第一个数组元素，目的是省的到处写 $data[0]
     * @param  [type] $data [需要解析的数组]
     * @return [type]       [解析数组后的数组元素]
     */
    public function getDataItem($data) {
        if(!empty($data[0])) {
            $item = reset($data);
        }else {
            $item = $data;
        }
        $user                = User_Api::getUserObject($item['userid']);
        $item['amount']      = number_format($item['amount'], 2);
        $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
        $type                = $item['duration_type'] == 2 ? '个月' : '天';
        $item['duration']    = $item['duration'].' '.$type;
        $status              = Apply_Type_Status::$names;
        $item['status']      = $status[$item['status']];
        $item['service_charge'] = floatval($item['service_charge']);
        $item['rate']           = floatval($item['rate']);

        $item['username']           = $user->email;

        return $item;
    }
}