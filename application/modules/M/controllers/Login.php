<?php
/**
 * 官方公告
 */
class LoginController extends Base_Controller_Page {

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
    	$this->getView()->assign('title', "登陆兴教贷");
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
    
    /*
     * TODO：文字截取并加点
     * param str $string 要截取的字符串 int $length 截取的长度
     */
    function cutstr($string, $length) {
        $j = 0;
        $wordscut = '';
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
        for($i=0; $i<count($info[0]); $i++) {
            $wordscut .= $info[0][$i];
            $j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
            if ($j > $length - 3) {
                return $wordscut." ...";
            }
        }
        return join('', $info[0]);
    }

}
