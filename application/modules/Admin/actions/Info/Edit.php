<?php
/**
 * 公告列表
 * @author huwei
 *
 */
class EditAction extends Yaf_Action_Abstract {
    public function execute() {
        $postid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if(empty($postid)){
            $this->getView()->assign('post', '');
        }
        $postInfo  = Infos_Api::getPost($postid);  
        $this->getView()->assign('post', $postInfo);
    }
}