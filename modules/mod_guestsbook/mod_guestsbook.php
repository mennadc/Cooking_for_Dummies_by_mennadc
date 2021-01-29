<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_guestsbook.php';

class ModGuestsBook extends ModGeneric {

	public function __construct() {
		$this->controller = new ContGuestsBook();

		if (isset($_GET['action']))
			$action = $_GET['action'];
		else
			$action = 'default';

		switch ($action) {
			case 'guestsbook_page':
			case 'send_guestsbook_msg':
				$this->controller->$action();
				break;
			default:
				$this->controller->notFound_page();
		}
	}
}
?>
