<?php
class ImagecodeController extends Base_Controller_Api {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }

    /**
     * /user/imagecode/getimage?type=
     * 根据类型获取图片验证码
     * @param  string $type 自定义类型
     */
    public function getImageAction(){
        $strType = trim($_REQUEST['type']);
        //设置文件头;
        header("Content-type: image/png");

        $distortionIm = User_Logic_ImageCode::genImage($strType);
        //以PNG格式将图像输出到浏览器或文件;
        imagepng($distortionIm);
        //销毁一图像,释放与image关联的内存;
        imageDestroy($distortionIm);
    }
    
    /** 
     * 接口11: /user/imagecode/checkcode
     * 获取图片验证码
     * @param string $token
     * @param string $imagecode
     * @return 标准Json格式
     * status 0:成功
     * status 1040:图形验证码错误
     */
    public function checkCodeAction(){
        $strType         = trim($_REQUEST['type']);
        $strImageCode    = trim($_REQUEST['imagecode']);
        
        $bolRet = User_Logic_ImageCode::checkCode($strType, $strImageCode);
        if($bolRet){
            return $this->ajax();
        }else{
            return $this->ajaxError(User_RetCode::IMAGE_CODE_WRONG,
                User_RetCode::getMsg(User_RetCode::IMAGE_CODE_WRONG));
        }
    }
}
