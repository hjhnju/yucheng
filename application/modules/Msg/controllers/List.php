<?php
/**
 * 消息列表
 */
class ListController extends Base_Controller_Response {
    const PAGE_SIZE = 8;
    
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
	
    /**
     * 获取消息列表
     * @param int status  0:所有，1已读，2未读
     * @param int page  页码，从1开始
     * @return array(array('type' =>,'status'=>xxx,'content'=>xxx,'link'=>'xxx','time'=>xxx));
     * 
     */
	public function indexAction() {
	    $intType = trim($_REQUEST['status']);
	    $intPage = trim($_REQUEST['page']) - 1;
        $this->msgLogic = new Msg_Logic_Msg();
        $uid = $this->getUserId();
        $arrReturn = array();
        $arrData = $this->msgLogic->getList($uid, $intType,$intPage,self::PAGE_SIZE);
        if(empty($arrData)){
            $this->ajax(array(
                'page'=>0,
                'pageall'=>0,
                'list'=>array()
            ));
        }
        foreach($arrData as $index => $val){
            $arrLink = Msg_Api::getLink($val['type']);
            $arrReturn[$index]['type'] = $val['type'];
            $arrReturn[$index]['status'] = $val['status'];
            $arrReturn[$index]['content'] = $val['content'];
            $arrReturn[$index]['link'] = $arrLink['link'];
            $arrReturn[$index]['linkname'] = $arrLink['linkname'];
            $arrReturn[$index]['mid'] = $val['mid'];
            $arrReturn[$index]['time'] = $val['create_time'];
        }
        $data['page']    = $intPage + 1;
        $data['pageall'] = intval((count($arrData)-1)/self::PAGE_SIZE)+1;
        $data['list'] = $arrReturn;
        $this->ajax($data);
	}
}
