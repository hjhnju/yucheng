<?php 
/**
 * 安全工具类,封装与安全加密机通信的细节
 * 版本：2.0.0
 * 日期：2012-07-19
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究汇付天下p2p接口使用，只是提供一个参考。
 *
 */
class Finance_Chinapnr_SecureTool{
	private $privateKey=array();
	private $publicKey=array();
	
	const DES_KEY= "SCUBEPGW";
	const HASH_PAD= "0001ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff003021300906052b0e03021a05000414";

	public function Finance_Chinapnr_SecureTool($priKey,$pubKey){
		$this->privateKey= $this->buildPrivateKeyString($priKey);
		$this->publicKey= $this->buildPublicKeyString($pubKey);	
	}	
	

	
	/**
	 * @desc 对指定的数据进行签名请求
	 * @param string $data
	 * @return string 
	 */
	public function sign($msg){
		$hb = $this->secure_tool_sha1_128 ( $msg );
		return $this->secure_tool_rsa_encrypt ( $hb, $this->privateKey );
	}
	
	/**
	 * @desc 对返回数据进行验签检查，返回是否通过。
	 * @param string $verifyData
	 * @return boolean
	 */
	public function verify($plain, $sign){
		$hb = $this->secure_tool_sha1_128 ( $plain );
		$hbhex = strtoupper ( bin2hex ( $hb ) );
		$rbhex = $this->secure_tool_rsa_decrypt ( $sign, $this->publicKey );
		return $hbhex == $rbhex ? true : false;		
	}
	
	private function buildPublicKeyString($keyString){
		return $this->buildKey(substr ( $keyString, 48 ));
	}
	
	private function buildPrivateKeyString($keyString){
		return $this->buildKey(substr ( $keyString, 80 ));
	}
	
	private function buildKey($key){
		$bin = $this->secure_tool_hex2bin ( $key );
		$private_key ["modulus"] = substr ( $bin, 0, 128 );
		$cipher = MCRYPT_DES;
		$iv = str_repeat ( "\x00", 8 );
		$prime1 = substr ( $bin, 384, 64 );
		$enc = $this->like_mcrypt_cbc ( $cipher, $this::DES_KEY, $prime1, MCRYPT_DECRYPT, $iv );
		$private_key ["prime1"] = $enc;
		$prime2 = substr ( $bin, 448, 64 );
		$enc = $this->like_mcrypt_cbc ( $cipher, $this::DES_KEY, $prime2, MCRYPT_DECRYPT, $iv );
		$private_key ["prime2"] = $enc;
		$prime_exponent1 = substr ( $bin, 512, 64 );
		$enc = $this->like_mcrypt_cbc ( $cipher, $this::DES_KEY, $prime_exponent1, MCRYPT_DECRYPT, $iv );
		$private_key ["prime_exponent1"] = $enc;
		$prime_exponent2 = substr ( $bin, 576, 64 );
		$enc = $this->like_mcrypt_cbc ( $cipher, $this::DES_KEY, $prime_exponent2, MCRYPT_DECRYPT, $iv );
		$private_key ["prime_exponent2"] = $enc;
		$coefficient = substr ( $bin, 640, 64 );
		$enc = $this->like_mcrypt_cbc ( $cipher, $this::DES_KEY, $coefficient, MCRYPT_DECRYPT, $iv );
		$private_key ["coefficient"] = $enc;
		return $private_key;
	}
	private function like_mcrypt_cbc($cipher, $key, $data, $md, $iv){
		if(!$data) return ""; //针对substr方法截取公私钥长度不足，返回false造成后续处理报错的情况
	    $td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, '');
	    mcrypt_generic_init($td, $key, $iv);
	    $data = mdecrypt_generic($td, $data);
	    mcrypt_generic_deinit($td);
	    mcrypt_module_close($td);
	    return $data;
	}
	private function secure_tool_hex2bin($hexdata) {
		$bindata = '';
		if (strlen ( $hexdata ) % 2 == 1) {
			$hexdata = '0' . $hexdata;
		}
		for($i = 0; $i < strlen ( $hexdata ); $i += 2) {
			$bindata .= chr ( hexdec ( substr ( $hexdata, $i, 2 ) ) );
		}
		return $bindata;
	}
	private function secure_tool_padstr($src, $len = 256, $chr = '0', $d = 'L') {
		$ret = trim ( $src );
		$padlen = $len - strlen ( $ret );
		if ($padlen > 0) {
			$pad = str_repeat ( $chr, $padlen );
			if (strtoupper ( $d ) == 'L') {
				$ret = $pad . $ret;
			} else {
				$ret = $ret . $pad;
			}
		}
		return $ret;
	}
	private function secure_tool_bin2int($bindata) {
		$hexdata = bin2hex ( $bindata );
		return $this->secure_tool_bchexdec ( $hexdata );
	}
	private function secure_tool_bchexdec($hexdata) {
		$ret = '0';
		$len = strlen ( $hexdata );
		for($i = 0; $i < $len; $i ++) {
			$hex = substr ( $hexdata, $i, 1 );
			$dec = hexdec ( $hex );
			$exp = $len - $i - 1;
			$pow = bcpow ( '16', $exp );
			$tmp = bcmul ( $dec, $pow );
			$ret = bcadd ( $ret, $tmp );
		}
		return $ret;
	}
	private function secure_tool_bcdechex($decdata) {
		$s = $decdata;
		$ret = '';
		while ( $s != '0' ) {
			$m = bcmod ( $s, '16' );
			$s = bcdiv ( $s, '16' );
			$hex = dechex ( $m );
			$ret = $hex . $ret;
		}
		return $ret;
	}
	private function secure_tool_sha1_128($string) {
		$hash = sha1 ( $string );
		$sha_bin = $this->secure_tool_hex2bin ( $hash );
		$sha_pad = $this->secure_tool_hex2bin ( $this::HASH_PAD );
		return $sha_pad . $sha_bin;
	}
	private function secure_tool_rsa_encrypt($input, $private_key) {
		$p = $this->secure_tool_bin2int ( $private_key ["prime1"] );
		$q = $this->secure_tool_bin2int ( $private_key ["prime2"] );
		$u = $this->secure_tool_bin2int ( $private_key ["coefficient"] );
		$dP = $this->secure_tool_bin2int ( $private_key ["prime_exponent1"] );
		$dQ = $this->secure_tool_bin2int ( $private_key ["prime_exponent2"] );
		$c = $this->secure_tool_bin2int ( $input );
		$cp = bcmod ( $c, $p );
		$cq = bcmod ( $c, $q );
		$a = bcpowmod ( $cp, $dP, $p );
		$b = bcpowmod ( $cq, $dQ, $q );
		if (bccomp ( $a, $b ) >= 0) {
			$result = bcsub ( $a, $b );
		} else {
			$result = bcsub ( $b, $a );
			$result = bcsub ( $p, $result );
		}
		$result = bcmod ( $result, $p );
		$result = bcmul ( $result, $u );
		$result = bcmod ( $result, $p );
		$result = bcmul ( $result, $q );
		$result = bcadd ( $result, $b );
		$ret = $this->secure_tool_bcdechex ( $result );
		return strtoupper ( $this->secure_tool_padstr ( $ret ) );
	}

	private function secure_tool_rsa_decrypt($input, $private_key) {
		$check = $this->secure_tool_bchexdec ( $input );
		$modulus = $this->secure_tool_bin2int ( $private_key ["modulus"] );
		$exponent = $this->secure_tool_bchexdec ( "010001" );
		$result = bcpowmod ( $check, $exponent, $modulus );
		$rb = $this->secure_tool_bcdechex ( $result );
		return strtoupper ( $this->secure_tool_padstr ( $rb ) );
	}
}
?>