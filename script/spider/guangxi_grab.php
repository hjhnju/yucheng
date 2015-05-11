<?php
set_time_limit(0);
@ini_set('memory_limit', '2048M');
require(dirname(dirname(__FILE__)) . '/env.inc.php');
/**
 * 抓取网页的所有动作，比如北京的抓取，广西的抓取行为
 */
class BeijingGrab  {
    public function execute() {
        $this->executeXiehui();
        $this->executeSouXue();
        $this->executeDaquan();
    }
    public function executeSouXue() {
        $url = 'http://xuexiao.51sxue.com/slist/?t=1&areaCodeS=45';
        $spider = new Spider_Grab_SouXue($url, 'kindergarten', 'guangxi');
        $spider->downloadFiles();

        $url = 'http://xuexiao.51sxue.com/slist/?t=2&areaCodeS=45';
        $spider = new Spider_Grab_SouXue($url, 'middle', 'guangxi');
        $spider->downloadFiles();

        $url = 'http://xuexiao.51sxue.com/slist/?t=3&areaCodeS=45';
        $spider = new Spider_Grab_SouXue($url, 'middle', 'guangxi');
        $spider->downloadFiles();
    }

    public function executeDaquan() {
        $url = 'http://xuexiao.chazidian.com/guangxi_youeryuan/';
        $spider = new Spider_Grab_Daquan($url, 'kindergarten', 'guangxi');
        $spider->run();

        $url = 'http://xuexiao.chazidian.com/guangxi_xiaoxue/';
        $spider = new Spider_Grab_Daquan($url, 'middle', 'guangxi');
        $spider->run();

        $url = 'http://xuexiao.chazidian.com/guangxi_chuzhong/';
        $spider = new Spider_Grab_Daquan($url, 'middle', 'guangxi');
        $spider->run();
    }

    public function executeXiehui(){
        $spider = new Spider_Grab_Xiehui();
        $spider->run();

    }
}

$instance = new BeijingGrab();
$instance->execute();


