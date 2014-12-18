<?php
/**
 * 
 */
class IndexController extends Base_Controller_Page {
	
	public function indexAction() {

        echo "in Index controller";
        $this->getView()->assign('street', '21');

        /*
        //$smsRet = Base_Sms::getInstance()->sendRaw('18611015043', '【兴教贷】测试短信fromxjd');
        //var_dump($smsRet);
        $arrArgs = array('476181', '5');
        $tplid   = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $smsRet = Base_Sms::getInstance()->send('18611015043', $tplid, $arrArgs);
        var_dump($smsRet);
        die;
        $redis = Base_Redis::getInstance();
        $ret   = $redis->set('xjd_version', '1.0');
        print_r($ret);
        $ret   = $redis->del('xjd_version');
        print_r($ret);

        $model = new TestModel();
        $res = $model->query();
        print_r($res);
		exit();
        */
	}
}
