<?php
/**
 * 官方公告
 */
class PostController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 公告列表页
     *
     * /infos/post
     * @param   $page, MUST, [1,-), index of page
     * @assign  data=>array('page', 'pagesize', 'pageall', 'list', 'total')
     */
    public function indexAction() {
        $page     = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
        $pagesize = 10;
        $logic    = new Infos_Logic_Post();
        $ret      = $logic->getList($page, $pagesize,'platPost');
        Base_Log::notice($ret); 
        $this->getView()->assign('data', $ret);
    }
    

    /**
     * 公告详情页
     *
     * /infos/post/detail?id=
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
