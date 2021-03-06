<?php
/**
 * 官方公告
 */
class InfoApiController extends Base_Controller_Api {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    
    /**
     * 公告列表每页数据
     *
     * /infos/post/list
     * @param   $page, MUST, [1,-), index of page
     * @return  json data=>array('page', 'pagesize', 'pageall', 'list', 'total')
     */
    public function indexAction() {
        $page     = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
        $strType     = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        $pagesize = 10;
        $logic    = new Infos_Logic_Post();
        $ret      = $logic->getList($page, $pagesize,$strType);
        Base_Log::notice($ret);
        $this->ajax($ret);
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
        $strTitle  = $_REQUEST['title'];
        $strAuthor = $_REQUEST['author'];
        $strAbstract = $_REQUEST['abstract'];
        $strCtx    = $_REQUEST['ctx'];
        $strTime   = isset($_REQUEST['time'])?$_REQUEST['time']:date();
        $intType   = $_REQUEST['type'];
        $id   = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $this->ajax              = true;
        $logic                   = new Infos_Logic_Post();
        $arrPost                 = array();
        $arrPost['title']        = $strTitle;
        $arrPost['author']       = $strAuthor;
        $arrPost['abstract']       = $strAbstract;
        $arrPost['publishtime']  = strtotime($strTime);
        $arrPost['ctx']          = $strCtx;
        $arrPost['type']         = $intType;
        $postid = $logic->save($arrPost,$id);
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
        $postid = isset($_REQUEST['id'])? intval($_REQUEST['id']) : 0; 
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
    
    /**
     * 测试使用
     * TODO:Admin后台使用
     * /infos/post/del?id=
     * 删除公告
     * @param   $id postid
     */
    public function delAction() {
        $postid = isset($_REQUEST['id'])? intval($_REQUEST['id']) : 0;
        if($postid <= 0){
            $this->ajaxError();
        }
    
        $logic  = new Infos_Logic_Post();
        $ret    = $logic->del($postid);
    
        Base_Log::notice(array('postid'=>$postid, 'ret'=>$ret));
        if($ret){
            $this->ajax();
        }else{
            $this->ajaxError();
        }
    }

}
