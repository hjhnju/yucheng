<?php
/**
 * 数字签名生成与检查类
 */
class Base_Util_Sign {

    /**
     * 对传输的参数进行签名
     * 签名规则:按照参数的键正序排列,键值连接成'key1=v1&key2=v2'字串
     * 返回strtoupper(md5(appkey=xxx&&userid=222&secretKey))
     * @param array $params 参与签名的参数数组键值对
     * @param string $secretKey 签名密钥
     * @param int $signLen 密钥长度 0 为完整长度
     * @return mix $sign 签名指纹 或者返回错误
     */
    public function sign($params, $secretKey, $signLen = 0) {

        if (!is_array($params) || empty($secretKey)) {
            return false;
        }
        //键值默认排序
        ksort($params);
        $paramArray = array();
        foreach($params as $key => $value) {
            $paramArray[] = $key. '='. $value;
        }
        $paramStr  = implode( '&', $paramArray);
        $sign = md5($paramStr . $secretKey);
        $sign = $sign ? substr($sign, 0, $signLen) : $sign;
        return $sign;
    }

    /**
     * 校验签名
     * @param array $params 参与签名的参数数组键值对
     * @param string $secretKey 签名密钥
     * @param string $sign 签名指纹
     * @param int $signLen 密钥长度 0 为完整长度
     * @return bool 签名正确与否
     */
    public function check($params, $secretKey, $sign, $signLen = 0) {

        if (!is_array($params) || !$secretKey || !$sign) {
            return false;
        }
        $signB = self::sign($params, $secretKey, $signLen);
        return ($sign === $signB);
    }
}
