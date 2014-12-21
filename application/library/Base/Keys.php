<?php
class Base_Keys {
	const CSRF_TOKEN_KEY = 'csrftoken';

	public function getCsrfTokenKey(){
		return self::CSRF_TOKEN_KEY;
	}
}