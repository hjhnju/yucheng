<?php
class EditController extends Base_Controller_Response {

    public function init(){
        parent::init();
        $this->ajax = true;
    }

    /** 
     * 接口1: /account/edit/chpwd
     * 用户密码修改接口
     * @param $oldpwd
     * @param $newpwd
     * @param $newpwd2
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1101: 修改密码失败
     */
    public function chpwdAction(){
    }

     /** 
      * 接口2: /account/edit/checkphone
      * 用户验证原手机号
      * @param $phone
      * @param $vericode
      * @param $token, csrf token
      * @return 标准json格式
      * status 0: 成功
      * status 1102: 验证码错误
      */
     public function checkPhoneAction(){
     }

    /** 
     * 接口3: /account/edit/chphone
     * 用户修改手机号
     * @param $phone
     * @param $vericode
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1102: 验证码错误
     * status 1103: 修改手机号失败
     */
    public function chphoneAction(){
    }

    /** 
     * 接口4: /account/edit/getsmscode
     * 获取修改手机的短信验证码
     * @param $phone
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1105: 获取验证码失败
     */
    public function getSmsCodeAction(){
    }

}
