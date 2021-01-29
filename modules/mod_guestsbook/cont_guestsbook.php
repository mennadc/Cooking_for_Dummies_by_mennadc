<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_guestsbook.php';
include_once 'model_guestsbook.php';

class ContGuestsBook extends ContGeneric {

	public function __construct() {
		$this->model = new ModelGuestsBook();
		$this->view = new ViewGuestsBook();
	}

	/**
	 * Display guests book page
	 * @param string $msg
	 */
	public function guestsbook_page($msg = '') {
		$sentMessages = $this->model->get_guestsbook_msg();
		$fullName = '';

		if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
			$userInfos = $this->model->get_user_informations();
			if ($userInfos !== -1)
				$fullName =  ucfirst($userInfos['firstname']) . ' ' . ucfirst($userInfos['lastname']);
		}
		$this->view->guestsbook_page($msg, $fullName, $sentMessages);
	}

	/**
	 * Send user guests book message
	 */
	public function send_guestsbook_msg() {
		$msgSent = $this->model->send_guestsbook_msg();

		switch ($msgSent) {
			case -1:
				$msg = "<p class='text-center text-danger'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
				break;
			case 0:
				$msg = "<p class='text-center text-success'>Your message has been sent successfully !</p>";
				break;
			case 1:
				$msg = "<p class='text-center text-danger'>Invalid informations entered !";
		}
		$this->guestsbook_page($msg);
	}
}
?>
