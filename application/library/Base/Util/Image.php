<?php
class Base_Util_Image {
    /**
     * 获取图片的URL地址
     * @param string $hash
     * @param number $width
     * @param number $height
     * @return string
     */
    public static function getUrl($hash, $width = 0, $height = 0) {
        $url = "/pic/$hash";
        if ($width > 0) {
            $url .= "_{$width}";
            if ($height > 0) {
                $url .= "_{$height}";
            }
        }
        $url .= ".jpg";
        return $url;
    }
}