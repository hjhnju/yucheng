<?php
/**
 * 上传功能
 */
class UploadController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 上传图片
     */
    public function picAction() {
        if (empty($_FILES['file'])) {
            return $this->ajaxError(Base_RetCode::PARAM_ERROR);
        }
        
        $ext = substr($_FILES['file']['name'], -3);
        if (!in_array($ext, array('jpg', 'gif', 'png'))) {
            return $this->ajaxError(Base_RetCode::PARAM_ERROR);
        }
        
        $hash = md5(microtime(true));
        $hash = substr($hash, 8, 16);
        $filename = $hash . '.jpg';
        
        $oss = Oss_Adapter::getInstance();
        $res = $oss->writeFile($filename, $_FILES['file']['tmp_name']);
        if ($res) {
            @unlink($_FILES['file']['tmp_name']);
            $data = array(
                'hash' => $hash,
                'url'  => Base_Util_Image::getUrl($hash),
            );
            return $this->ajax($data);
        }

        $msg = array(
            'hash' => $hash,
            'file' => $_FILES['file']['name'],
        );
        Base_Log::warn($msg);
        $this->ajaxError();
    }
}
