<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_contact.php';
include_once 'model_contact.php';

class ContContact extends ContGeneric {

	public function __construct() {
		$this->model = new ModelContact();
		$this->view = new ViewContact();
	}

	/**
	 * Display contact page
	 * @param string $msg
	 */
	public function contact_page($msg = '') {
		$fullName = '';
		$email = '';

		if (isset($_SESSION['login'])) {
			$userInfos = $this->model->get_user_informations();
			if ($userInfos != -1) {
				$fullName =  ucfirst($userInfos['firstname']) . ' ' . ucfirst($userInfos['lastname']);
				$email = $userInfos['email']; 
			}
		}
		$this->view->contact_page($msg, $fullName, $email);
	}

	/**
	 * Send contact user message
	 */
	public function send_contact_msg() {
		$msgSent = $this->model->send_contact_msg();

		switch ($msgSent) {
			case -1:
				$msg = "<p class='text-center text-danger'>An error has occurred !</p>";
				break;
			case 0:
				$msg = "<p class='text-center text-success'>Your message has been sent to our team successfully ! We will contact you soon.</p>";
				break;
			case 1:
				$msg = "<p class='text-center text-danger'>Invalid informations entered !</p>";
				
		}
		$this->contact_page($msg);
	}
}
?>
