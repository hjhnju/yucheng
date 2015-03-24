<?php
/**
 * 首页
 */
class IndexController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    public function indexAction() {

        //投资列表
        $list = Invest_Api::getInvestList(1, 5);
        $list = $list['list'];
        //最新公告
        $newpost = Infos_Api::getNewPost('post');
        //获取新闻
        $media   = Infos_Api::getNewMedia();
        //assign
        $arrData            = array();
        $arrData['list']    = $list;
        $arrData['newpost'] = $newpost;
        $arrData['media'] = $media;
        $this->getView()->assign('data', $arrData);
    }
}
