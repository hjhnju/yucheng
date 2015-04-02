<?php
/**
 * 公告列表
 * @author huwei
 *
 */
class PushAction extends Yaf_Action_Abstract {
    public function execute() {
        $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;
        $list     = Infos_Api::getAllPost($page, $pagesize);
        $pageAll  = $list['pageall'];
        $arrInfos = $list['list'];
        foreach ($arrInfos as $key => $val){
			switch($val['type']){
				case 1:
					$arrInfos[$key]['type'] = '平台公告';
					break;
				case 2:
					$arrInfos[$key]['type'] = '媒体报道';
					break;
				case 3:
					$arrInfos[$key]['type'] = '还款公告'; 
					break;	
			}
			$arrInfos[$key]['abstract'] = $this->cutstr($val['abstract'],80);
			$arrInfos[$key]['title'] = $this->cutstr($val['title'],40);
        }
        $this->getView()->assign('arrInfo', $arrInfos);
        $this->getView()->assign('pageall', $pageAll);
        $this->getView()->assign('page', $page);
    }
    
    /*
     * TODO：文字截取并加点
    * param str $string 要截取的字符串 int $length 截取的长度
    */
    function cutstr($string, $length) {
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