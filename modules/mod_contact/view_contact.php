<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewContact extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display contact page
	 * @param string $msg
	 * @param string $fullName
	 * @param string $email
	 */
	public function contact_page($msg, $fullName, $email) {
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>Contact us</h2>
					<p>Do you have any questions ? Please do not hesitate to contact us directly. Our team will come back to you within
						a matter of hours to help you</p>
				</div>
				$msg
				<form class='container form-signin' action='index.php?module=contact&action=send_contact_msg' method='post'>
					<div class='form-label-group mb-md-3'>
						<label for='name'>Your name <span class='text-danger'>*</span></label>
						<input type='text' name='name' class='form-control' placeholder='Enter your name' value='$fullName' required>	
					</div>
					<div class='form-label-group mb-md-3'>
						<label for='email'>Your email <span class='text-danger'>*</span></label>
						<input type='text' name='email' class='form-control' placeholder='Enter your email' value='$email' required>
					</div>
					<div class='form-label-group mb-md-3'>
						<label for='subject'>Subject <span class='text-danger'>*</span></label>
						<input type='text' name='subject' class='form-control' placeholder='Enter the subject' required>
					</div>
					<div class='form-label-group mb-md-5'>
						<label for='message'>Your message <span class='text-danger'>*</span></label>
						<textarea type='text' name='message' rows='4' class='form-control md-textarea' placeholder='Enter your message' maxlength='1000' required></textarea>
					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Send message'/>
				</form>
			</div>";
	}
}
?>