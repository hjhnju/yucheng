<?php
/**
 * 微站 开通第三方
 */
class OpenController extends Base_Controller_Page {

    protected $loginUrl = '/m/login';

    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }

    /**
     * 开通第三方
     *
     * /m/open
     * @param
     * @return
     */
    public function indexAction() {
       $this->getView()->assign('title', "开通汇付天下");
        $sucHit = (int)Base_Redis::getInstance()->hGet('reg_success_hset', $this->userid);
        if($sucHit === 1){
            $this->getView()->assign('hint', 1);
        }else{
            Base_Redis::getInstance()->hSet('reg_success_hset', $this->userid, 1);
        }
    }

    /**
     * 注册成功
     *
     * /m/open
     * @param
     * @return
     */
    public function successAction() {
        $this->getView()->assign('title', "注册成功");
        $sucHit = (int)Base_Redis::getInstance()->hGet('open_success_hset', $this->userid);
        if($sucHit === 1){
            $this->getView()->assign('hint', 1);
        }else{
            Base_Redis::getInstance()->hSet('open_success_hset', $this->userid, 1);
        }
    }

    public function activityAction(){
        return $this->redirect('http://mp.weixin.qq.com/s?__biz=MzAxODE3MDk0OA==&mid=205028488&idx=1&sn=724a23c2f985950021340f19b16be11f&scene=18&key=2e5b2e802b7041cfbbc500639e60e0af49de4313cc0515a3f4370b62ee4247876e28261e32b1337c802d8764e0fb12e4');
    }

}
