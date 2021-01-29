<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_connection.php';
include_once 'model_connection.php';

class ContConnection extends ContGeneric {

	public function __construct() {
		$this->model = new ModelConnection();
		$this->view = new ViewConnection();
	}

	/**
	 * Display sign up form
	 * @param string $errorMsg
	 */
	public function form_signup($errorMsg = '') {
		$this->view->form_signup($errorMsg, $this->model->get_default_avatar());
	}

	/**
	 * Sign up a user
	 */
	public function signup() {
		$registered = $this->model->signup();
		
		if ($registered == 0)
			header('Location: index.php?module=home');
		else {
			switch ($registered) {
				case -1:
					$errorMsg = "An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.";
					break;
				case 1:
					$errorMsg = "<p class='text-center text-danger'>Avatar dimensions not respected ! (between 300x300px and 450x450px)</p>";
					break;
				case 2:
					$errorMsg = 'Invalid login details entered !';
					break;
				case 3:
					$errorMsg = 'Login details entered already used !';
			}
			$this->form_signup($errorMsg);
		}
	}

	/**
	 * Display sign in form
	 * @param string $errorMsg
	 */
	public function form_signin($errorMsg = '') {
		$this->view->form_signin($errorMsg);
	}

	/**
	 * Sign in a user
	 */
	public function signin() {
		$connected = $this->model->signin();

		if ($connected == 0)
			header('Location: index.php?module=home');
		else {
			switch ($connected) {
				case -1:
					$errorMsg = "An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.";
					break;
				case 1:
					$errorMsg = 'Invalid login details entered !';
					break;
				case 2:
					$errorMsg = 'Wrong login details entered !';
			}
			$this->form_signin($errorMsg);
		}
	}	

	/**
	 * Sign out a user
	 */
	public function signout() {
		if (isset($_SESSION['login']) && !empty($_SESSION['login']))
			$this->model->signout();
	
		header('Location: index.php?module=home');
	}

	/**
	 * Display mail reset password form
	 * @param string $msg
	 */
	public function form_mail_reset_password($msg = '') {
		$this->view->form_mail_reset_password($msg);
	}

	/**
	 * Send mail reset password to a user email
	 */
	public function send_mail_reset_password() {
		$mailSent = $this->model->send_mail_reset_password();
		
		switch ($mailSent) {
			case -1:
				$msg = "<p class='text-center text-danger'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
				break;
			case 0:
				$msg = "<p class='text-center text-success'>Mail send successfully ! If you don't receive it, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
				break;
			case 1:
				$msg = "<p class='text-center text-danger'>Invalid email entered !</p>";
				break;
			case 2:
				$msg = "<p class='text-center text-danger'>Wrong email entered !</p>";
		}
		
		$this->view->form_mail_reset_password($msg);
	}

	/**
	 * Display reset password form
	 * @param string $errorMsg
	 */
	public function form_reset_password($errorMsg = '') {
		if (!$this->model->token_exists())
			header('Location: index.php?module=home');
		else 
			$this->view->form_reset_password($errorMsg);
	}

	/**
	 * Reset user password
	 */
	public function reset_password() {
		if (!$this->model->token_exists())
			header('Location: index.php?module=home');
		else {
			$passwordReset = $this->model->reset_password();
			
			if ($passwordReset == 0)
				header('Location: index.php?module=home');
			else {
				switch ($passwordReset) {
					case -1:
						$errorMsg = "An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.";
						break;
					case 1:
						$errorMsg = 'Invalid email or password entered !';
						break;
					case 2:
						$errorMsg = 'Differente new password entered !';
						break;
					case 3;
						$errorMsg = 'Wrong email entered !';
						break;		
					case 4:
						$errorMsg = 'Enter a different password than your current password !';
				}
				$this->form_reset_password($errorMsg);
			}
		}
	}
}

?>