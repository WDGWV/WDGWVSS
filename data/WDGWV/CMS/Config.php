<?php
namespace WDGWV\CMS;

class Config extends \WDGWV\General\WDGWVFramework {
	public function adminURL() {
		return 'administration';
	}

	public function theme() {
		// TO RUN NORMAL MODE
		return \WDGWV\CMS\controllers\databases\plainText::sharedInstance()->getTheme();
		return 'portal';
		// TO TEST ADMIN:
		// return 'admin';
	}

	/**
	 * DO NOT CHANGE BELOW
	 */
	public function getVersion() {
		return (new \WDGWV\General\WDGWV())->version;
	}
}

?>