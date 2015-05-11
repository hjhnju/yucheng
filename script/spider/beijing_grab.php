<?php
set_time_limit(0);
@ini_set('memory_limit', '2048M');
require(dirname(dirname(__FILE__)) . '/env.inc.php');
/**
 * 抓取网页的所有动作，比如北京的抓取，广西的抓取行为
 */
class BeijingGrab  {
    public function execute() {
        $this->executeEducation();
        $this->executeSouXue();
        $this->executeDaquan();
    }

    /**
     * 什么的都没做
     */
    public function executeEducation() {
        $url = 'http://www.bjedu.gov.cn/publish/portal27/tab1805/info35658.htm';
        $spider = new Spider_Grab_Education($url, 'kindergarten', 'beijing');
        $spider->downloadFiles();

        $url = 'http://www.bjedu.gov.cn/publish/portal27/tab1805/info35674.htm';
        $spider = new Spider_Grab_Education($url, 'middle', 'beijing');
        $spider->downloadFiles();

        $url = 'http://www.bjedu.gov.cn/publish/portal27/tab1805/info35690.htm';
        $spider = new Spider_Grab_Education($url, 'middle', 'beijing');
        $spider->downloadFiles();

    }
    public function executeSouXue() {
        $url = 'http://xuexiao.51sxue.com/slist/?t=1&areaCodeS=11';
        $spider = new Spider_Grab_SouXue($url, 'kindergarten', 'beijing');
        $spider->downloadFiles();

        $url = 'http://xuexiao.51sxue.com/slist/?t=2&areaCodeS=11';
        $spider = new Spider_Grab_SouXue($url, 'middle', 'beijing');
        $spider->downloadFiles();

        $url = 'http://xuexiao.51sxue.com/slist/?t=3&areaCodeS=11';
        $spider = new Spider_Grab_SouXue($url, 'middle', 'beijing');
        $spider->downloadFiles();
    }

    public function executeDaquan() {
        $url = 'http://xuexiao.chazidian.com/beijing_youeryuan/';
        $spider = new Spider_Grab_Daquan($url, 'kindergarten', 'beijing');
        $spider->run();

        $url = 'http://xuexiao.chazidian.com/beijing_xiaoxue/';
        $spider = new Spider_Grab_Daquan($url, 'middle', 'beijing');
        $spider->run();

        $url = 'http://xuexiao.chazidian.com/beijing_chuzhong/';
        $spider = new Spider_Grab_Daquan($url, 'middle', 'beijing');
        $spider->run();
    }
}

$instance = new BeijingGrab();
$instance->execute();


	