<?php
/**
 * 媒体报道
 */
class MediaController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 媒体报道列表页
     *
     * /infos/media
     * @param   $page, MUST, [1,-), index of page
     * @assign  data=>array('page', 'pagesize', 'pageall', 'list', 'total')
     */
    public function indexAction() {
        $page     = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
        $pagesize = 10;
        $logic    = new Infos_Logic_Post();
        $ret      = $logic->getList($page, $pagesize,'media');
        Base_Log::notice($ret);
        $this->getView()->assign('data', $ret);
    }
    
 

    /**
     * 媒体报道详情页
     *
     * /infos/media/detail?id=
     * @param   $id 公告id
     * @assign  data=>array('title','ctx','author','publish_time')
     */
    public function detailAction() {
        $postid = isset($_GET['id'])? intval($_GET['id']) : 0; 
        $logic  = new Infos_Logic_Post();
        $ret    = $logic->getPost($postid);
        Base_Log::notice(array(
            'title' => isset($ret['title'])? $ret['title'] : ''));
        $this->getView()->assign('data', $ret);
    }
}
