<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

	public function __construct() {
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}

	public function runFirewall($config) {
	    $config_mijoshop = $config->get('config_mijoshop');

		$this->checkSecurity($config_mijoshop, $this->get, 'get');
		$this->checkSecurity($config_mijoshop, $this->post, 'post');
	}

	public function checkSecurity($config, $data, $request) {
		$uri = urldecode(http_build_query($data));

		if (($config['firewall_lfi'] == $request) or ($config['firewall_lfi'] == 'both')) {
			$this->_checkLFI($uri);
		}

		if (($config['firewall_rfi'] == $request) or ($config['firewall_rfi'] == 'both')) {
			$this->_checkRFI($uri);
		}

		if (($config['firewall_sql'] == $request) or ($config['firewall_sql'] == 'both')) {
			$this->_checkSQL($uri);
		}

		if (($config['firewall_xss'] == $request) or ($config['firewall_xss'] == 'both')) {
			$this->_checkXSS($uri);
		}
	}

	public function _checkLFI($uri) {
		if (preg_match('#\.\/#is', $uri, $match)) {
			die('LFI attack attempt.');
		}
	}

	public function _checkRFI($uri) {
		static $exceptions;

		if (!is_array($exceptions)) {
			$exceptions = array();

			// attempt to remove instances of our website from the URL...
			$domain = JUri::getInstance()->getHost();
			$exceptions[] = 'http://'.$domain;
			$exceptions[] = 'https://'.$domain;

			// also remove blank entries that do not pose a threat
			$exceptions[] = 'http://&';
			$exceptions[] = 'https://&';
		}

		$uri = str_replace($exceptions, '', $uri);

		if (preg_match('#=https?:\/\/.*#is', $uri, $match)) {
			die('RFI attack attempt.');
		}
	}

	public function _checkSQL($uri) {
		if (preg_match('#[\d\W](union select|union join|union distinct)[\d\W]#is', $uri, $match)) {
			die('SQL Injection attack attempt.');
		}

		// check for SQL operations with DB_PREFIX in the URI
		if (preg_match('#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is', $uri, $match) && preg_match('/'.preg_quote(DB_PREFIX).'/', $uri, $match)) {
			die('SQL Injection attack attempt.');
		}
	}

	public function _checkXSS($uri) {
		if (preg_match('#<[^>]*\w*\"?[^>]*>#is', $uri, $match)) {
			die('XSS attack attempt.');
		}
	}
	
	public function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}
}