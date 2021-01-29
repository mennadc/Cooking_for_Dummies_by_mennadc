<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_contact.php';

class ModContact extends ModGeneric {

	public function __construct() {
		$this->controller = new ContContact();

		if (isset($_GET['action']))
			$action = $_GET['action'];
		else
			$action = 'default';

		switch ($action) {
			case 'contact_page':
			case 'send_contact_msg':
				$this->controller->$action();
				break;
			default:
				$this->controller->notFound_page();
		}
	}
}
?>
