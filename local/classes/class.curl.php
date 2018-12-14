<?php
class Curl {
	var $referer = 'http://yandex.ru/';
	var $ua = 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9a3pre) Gecko/20070330';
	var $params = array();
	
	public function __construct($uafile='') {
		if (is_file($uafile)) {
			$ua = file($uafile);
			$this->ua  = trim($ua[rand(0, count($ua)-1)]);
		}
	}

	public function get($url, $cookie = false) {

		if ($url == '')
			return false;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_REFERER, $this->referer);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->ua);

		if ($cookie) {
			curl_setopt($ch, CURLOPT_COOKIEJAR, '/home/users/a/aleksey-klimov/domains/tech.uadev.ru/cookie.txt'); // сохранять куки в файл
			curl_setopt($ch, CURLOPT_COOKIEFILE, '/home/users/a/aleksey-klimov/domains/tech.uadev.ru/cookie.txt');
		}

		if (is_array($this->params) && count($this->params) > 0) {
			foreach ($this->params as $param => $value) {
				if ($param != '' && $value != '') {
					curl_setopt($ch, $param, $value);					
				}
			}
		}

		$return=curl_exec($ch);
		
		if ($return === false) {
			echo '<pre style="text-align:left;color:black;background-color:white">';
			print_r(curl_error($ch));
			echo '</pre>';
		}

		curl_close($ch);
		return $return;
	}


	public function post($url, $data, $cookie = false) {

		if ($url == '')
			return false;
		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//		curl_setopt($ch, CURLOPT_REFERER, $this->referer);
//		curl_setopt($ch, CURLOPT_USERAGENT, $this->ua);
		curl_setopt($ch, CURLOPT_URL, $url); // отправляем на
//		curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
//		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // возвратить то что вернул сервер
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // следовать за редиректами
//		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);// таймаут4
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
		curl_setopt($ch, CURLOPT_POST, true); // использовать данные в post
//		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//		curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );

		if ($cookie) {
			curl_setopt($ch, CURLOPT_COOKIEJAR, '/home/users/a/aleksey-klimov/domains/tech.uadev.ru/cookie.txt'); // сохранять куки в файл
			curl_setopt($ch, CURLOPT_COOKIEFILE, '/home/users/a/aleksey-klimov/domains/tech.uadev.ru/cookie.txt');
		}

		if (is_array($this->params) && count($this->params) > 0) {
			foreach ($this->params as $param => $value) {
				if ($param != '' && $value != '') {
					curl_setopt($ch, $param, $value);
				}
			}
		}

		$return = curl_exec($ch);

		if ($return === false) {
			echo '<pre style="text-align:left;color:black;background-color:white">';
			print_r(curl_error($ch));
			echo '</pre>';
		}

		curl_close($ch);
		return $return;
	}



	public function auth($url, $data) {
		
		$ch = curl_init($this->referer);

		if(strtolower((substr($url,0,5))=='https')) { // если соединяемся с https
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}
		curl_setopt($ch, CURLOPT_REFERER, $this->referer);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->ua);
		curl_setopt($ch, CURLOPT_URL, $url); // отправляем на
		curl_setopt($ch, CURLOPT_HEADER, 1); // пустые заголовки
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);// таймаут4
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
		curl_setopt($ch, CURLOPT_COOKIEJAR, '/home/users/a/aleksey-klimov/domains/tech.uadev.ru/cookie.txt'); // сохранять куки в файл
		curl_setopt($ch, CURLOPT_COOKIEFILE, '/home/users/a/aleksey-klimov/domains/tech.uadev.ru/cookie.txt');
		curl_setopt($ch, CURLOPT_POST, 1); // использовать данные в post
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$page = curl_exec($ch);

		if ($page === false) {
			echo '<pre style="text-align:left;color:black;background-color:white">';
			print_r(curl_error($ch));
			echo '</pre>';
		}

		curl_close($ch);

		return $page;
	}


}