<?php
/*
 * TODO:实现省市联动选择框效果
 * Author:yibing
 */
class GetcityAction extends Yaf_Action_Abstract {	
	public function execute(){
		
		
		$list = new Area_List_Area();
		$prov = $_REQUEST['prov'];
		//设置查询条件（where）省的id
		$list->setFields(array("name","id"));
		$list->setFilterString("pid = $prov");
		$list->setOrder('pinyin asc');
		$list->setPagesize(PHP_INT_MAX);
		$list = $list->toArray();
		$list = $list['list'];
		foreach ($list as $key => $value){
			$id = $value['id'];
			$name = $value['name'];
			echo "<option value = '$id'>$name</option>";
		}
		//关闭模板自动渲染
		Yaf_Dispatcher::getInstance()->autoRender(FALSE);
	}
}