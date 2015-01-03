<?php
/**
 * 官方公告
 */
class PostController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
        $this->ajax = false;
    }
    
    /**
     * 公告列表页
     *
     * /infos/post/index
     * @param   $page, MUST, [1,-), index of page
     * @assign  data=>array('page', 'pagesize', 'pageall', 'list', 'total')
     */
    public function indexAction() {
        $page     = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
        $pagesize = 10;
        $logic    = new Infos_Logic_Post();
        $ret      = $logic->getList($page, $pagesize);
        Base_Log::notice($ret);
        $this->getView()->assign('data', $ret);
    }
    
    /**
     * 公告列表每页数据
     *
     * /infos/post/list
     * @param   $page, MUST, [1,-), index of page
     * @return  json data=>array('page', 'pagesize', 'pageall', 'list', 'total')
     */
    public function listAction() {
        $page     = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
        $pagesize = 10;
        $logic    = new Infos_Logic_Post();
        $ret      = $logic->getList($page, $pagesize);
        Base_Log::notice($ret);
        $this->ajax($ret);
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

    /**
     * 测试使用
     * TODO：Admin后台使用
     * /infos/post/save
     * 保存公告
     * @param   $title
     * @param   $ctx html内容
     * @param   $publishtime 发布时间
     */
    public function saveAction() {
        $this->ajax              = true;
        $logic                   = new Infos_Logic_Post();
        $arrPost                 = array();
        $arrPost['title']        = '兴教贷好友邀请计划上线公告';
        $arrPost['author']       = '兴教贷团队';
        $arrPost['publishtime']  = strtotime('2015-01-10 00:00:00');
        $arrPost['ctx']          = '<div><p>奖励条件：</p><div></div></div>';
        $postid = $logic->save($arrPost);
        Base_Log::notice(array('postid' => $postid));
        $this->ajax($postid);
    } 

    /**
     * 测试使用
     * TODO:Admin后台使用
     * /infos/post/publish?id=
     * 发布公告
     * @param   $id postid
     */
    public function publishAction() {
        $postid = isset($_POST['id'])? intval($_POST['id']) : 0; 
        if($postid <= 0){
            $this->ajaxError();
        }

        $logic  = new Infos_Logic_Post();
        $ret    = $logic->publish($postid);

        Base_Log::notice(array('postid'=>$postid, 'ret'=>$ret));
        if($ret){
            $this->ajax();
        }else{
            $this->ajaxError();
        }
    }

}
