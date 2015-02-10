<?php
/**
 * OSS图片浏览
 */
class PicController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 图片浏览
     */
    public function indexAction() {
        $hash = $this->_request->get('hash');
        if (empty($hash)) {
            header("HTTP/1.1 404 Not Found");
            exit;
        }
        $hash = substr($hash, 0, -4);
        $ary = explode('_', $hash);
        $hash = $ary[0];
        $cnt = count($ary);
        if ($cnt > 1) {
            $width = intval($ary[1]);
        }
        if ($cnt > 2) {
            $height = intval($ary[2]);
        }
        
        if (!empty($width) && empty($height)) {
            $height = $width;
        }
        
        $filename = $hash . '.jpg';
        $oss = Oss_Adapter::getInstance();
        $image = $oss->getContent($filename);
        if (empty($image)) {
            header("HTTP/1.1 404 Not Found");
            exit;
        }

        $imagick = new Imagick();
        $imagick->readimageblob($image);
        
        // @TODO 需要对图片跟缩略图做本地cache
        if ($width > 0 && $height > 0) {
            $imagick->cropthumbnailimage($width, $height);
        }
        $imagick->setimagecompressionquality(75);
        $mime = $imagick->getimagemimetype();
        header("content-type: " . $mime);
        ob_clean();
        $this->setBrowserCache(3600 * 24);
        echo $imagick->getimageblob();
        exit;
    }
}
