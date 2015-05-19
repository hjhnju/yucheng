<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_Personal {
	/**
	 * @param  
	 * @return 
	 */
	
	public function savePerson($param) {

	}

	/**
	 * @param 需要保存到cookie中的数据
	 * @return null
	 */
	public function saveCookie($param) {
		$fields = $this->getProperties();
		Apply_Cookie::save($param, $fields);	
	}

	/**
	 * @param  null
	 * @return 返回apply表中的字段
	 */
	public function getProperties() {
		$object = new Apply_Object_Person();
		$fields = array_flip($object->properties);
		
		return $fields;
	}
}