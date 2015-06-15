<?php
class Base_Keys {
	//手机号，短信数量限制
	const PHONE_LIMIT = 'phone_limit_%s';

    const CSRF_TOKEN_KEY = 'csrftoken';

    public function getCsrfTokenKey(){
        return self::CSRF_TOKEN_KEY;
    }
    
    /**
     * 获取手机号，短信数量限制
     * @return string
     */
    public function getPhoneLimitKey($phone){
        return sprintf(self::PHONE_LIMIT, $phone);
    }
}