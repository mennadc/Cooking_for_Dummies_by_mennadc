<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_connection.php';

class ModConnection extends ModGeneric {

	public function __construct() {
		$this->controller = new ContConnection();

		if (isset($_GET['action']))
			$action = $_GET['action'];
		else
			$action = 'default';

		switch ($action) {
			case 'form_signup':
			case 'signup':
			case 'form_signin':
			case 'signin':
			case 'form_mail_reset_password':
			case 'send_mail_reset_password':
			case 'form_reset_password':
			case 'reset_password':
				if (isset($_SESSION['login']) || !empty($_SESSION['login'])) {
					header('Location: index.php?module=home');
					break;
				}
			case 'signout':
				$this->controller->$action();
				break;
			default:
				$this->controller->notFound_page();
		}
	}
}
?>
