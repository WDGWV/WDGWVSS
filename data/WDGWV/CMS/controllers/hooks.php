<?php
namespace WDGWV\CMS\controllers;

class hooks extends \WDGWV\CMS\controllers\baseProtected {
	private $hookDatabase = array();

	/**
	 * Call the database
	 * @since Version 1.0
	 */
	public static function sharedInstance() {
		static $inst = null;
		if ($inst === null) {
			$inst = new \WDGWV\CMS\controllers\hooks();
		}
		return $inst;
	}

	protected function __construct() {

	}

	public function getUBBHooks() {
		return;
	}

	public function loopHooks($which) {
		if (is_array($which)) {
			for ($i = 0; $i < sizeof($which); $i++) {
				$this->loopHook($which[$i]);
			}
		}
	}

	public function loopHook($at) {
		switch ($at) {
		case 'url':
			if (isset($this->hookDatabase['url'])) {
				for ($i = 0; $i < sizeof($this->hookDatabase['url']); $i++) {
					$safeMatch = $this->hookDatabase['url'][$i]['name'];
					$safeMatch = preg_replace("/\//", "\\\\/", $safeMatch);
					if (preg_match("/" . $safeMatch . "/", $_SERVER['REQUEST_URI'])) {
						if (function_exists($this->hookDatabase['url'][$i]['action'])) {
							call_user_func($this->hookDatabase['url'][$i]['action']);
						} else {
							echo sprintf('"%s" is not a function!', $this->hookDatabase['url'][$i]['action']);
						}

					}
				}
			}
			break;
		case '_GET':
			if (isset($this->hookDatabase['_GET'])) {
				for ($i = 0; $i < sizeof($this->hookDatabase['_GET']); $i++) {
					if (isset($_GET[$this->hookDatabase['_GET'][$i]['name']])) {
						if (function_exists($this->hookDatabase['_GET'][$i]['action'])) {
							call_user_func($this->hookDatabase['_GET'][$i]['action']);
						} else {
							echo sprintf('"%s" is not a function!', $this->hookDatabase['_GET'][$i]['action']);
						}
					}
				}
			}
			break;

		default:
			# code...
			break;
		}
	}
	public function createHook($at, $name, $action) {
		$this->hookDatabase[$at][] = array('name' => $name, 'action' => $action);
	}
}
?>