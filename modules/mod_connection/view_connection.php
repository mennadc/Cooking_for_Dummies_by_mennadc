<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewConnection extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display sign up form
	 * @param string $errorMsg
	 * @param string $defaultAvatar
	 */
	public function form_signup($errorMsg, $defaultAvatar) {
		if (!empty($errorMsg))
			$errorMsg = "<p class='text-center text-danger'>$errorMsg</p>";

		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>Create an account</h2>
					<p>Get started with your free account !</p>
				</div>
				$errorMsg
				<form class='container form-signin mb-md-3' action='index.php?module=connection&action=signup' method='post' enctype='multipart/form-data'>
					<div class='d-flex justify-content-center mb-md-3'>
						<div class='flex-grow-1 mr-md-5'>
							<div class='form-label-group mb-md-3'>
								<label for='firstname'>First name <span class='text-danger'>*</span></label> 
								<input class='form-control' type='text' name='firstname' placeholder='Enter your first name' required/>
							</div>	
							<div class='form-label-group'>
								<label for='lastname'>Last name <span class='text-danger'>*</span></label> 
								<input class='form-control' type='text' name='lastname' placeholder='Enter your last name' required/>
							</div>
						</div>
						<div class='align-self-center flex-grow-2'>
							<img class='rounded mx-auto d-block col-md-4 mb-md-4' alt='default avatar' src='" . $defaultAvatar . "'>
							<input class='form-control-file' type='file' name='avatar' accept='.png, .jpg, .jpeg'/>
						</div>
					</div>
					<div class='form-label-group mb-md-3'>
						<label for='email'>Email <span class='text-danger'>*</span></label> 
						<div class='input-group'>
							<input class='form-control' type='text' name='email' placeholder='Enter your email' required/> 
							<span class='input-group-text'>@example.com</span> 
						</div>	
					</div>	
					<div class='form-label-group mb-md-3'>
						<label for='username'>Username <span class='text-danger'>*</span></label> 
						<input class='form-control' type='text' name='username' placeholder='Enter your username' required/>
					</div>
					<div class='form-label-group mb-md-5'>
						<label for='password'>Password <span class='text-danger'>*</span></label> 
						<input class='form-control' type='password' name='password' placeholder='Enter your password' required/>
					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Sign up'/>
				</form>
				<p class='text-center'>Have an account ? <a href='index.php?module=connection&action=form_signin'>Sign in</a></p>
			</div>";
	}

	/**
	 * Display sign in form
	 * @param string $errorMsg
	 */
	public function form_signin($errorMsg) {
		if (!empty($errorMsg))
			$errorMsg = "<p class='text-center text-danger'>$errorMsg</p>";

		$strLogin = '';
		if (isset($_COOKIE['login']))
			$strLogin = $_COOKIE['login'];

		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>Log in to your account</h2>
					<p>Join us to have access to more functionalities !</p>
				</div>
				$errorMsg
				<form class='container form-signin mb-md-3' action='index.php?module=connection&action=signin' method='post'>
					<div class='form-label-group mb-md-3'>
						<label for='login'>Username or email <span class='text-danger'>*</span></label> 
						<input class='form-control' type='text' name='login' placeholder='Enter your username or email' value='$strLogin'required/>
					</div>	
					<div class='form-label-group mb-md-3'>
						<label for='password'>Password <span class='text-danger'>*</span></label> 
						<input class='form-control' type='password' name='password' placeholder='Enter your password' required/>
					</div>
					<div class='form-group form-check mb-md-5'>
						<input class='form-check-input' type='checkbox' name='remember' value='remember' checked/> 
						<label class='form-check-label ml-md-2 mt-md-1' for='remember'>Remember me</label>
					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Sign in'/>
				</form>
				<div class='text-center'>
					<p class='text-center'>Don't have an account ? <a href='index.php?module=connection&action=form_signup'>Sign up</a> </p>
					<a href='index.php?module=connection&action=form_mail_reset_password'>Forgot password ?</a>
				</div>
			</div>";
	}

	/**
	 * Display mail reset password form
	 * @param string $msg
	 */
	public function form_mail_reset_password($msg) {
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>	
					<h2 class='h2 mb-md-3'>Password forgotten</h2>
					<p>A mail will be sent to the entered email address, so that you can reset your password !</p>
				</div>
				$msg
				<form class='container form-signin mb-md-3' action='index.php?module=connection&action=send_mail_reset_password' method='post'>
					<div class='form-label-group mb-md-5'>
						<label for='email'>Email <span class='text-danger'>*</span></label> 
						<div class='input-group'>
							<input class='form-control' type='text' name='email' placeholder='Enter your email' required/> 
							<span class='input-group-text'>@example.com</span> 
						</div>	
					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Send mail'/>
				</form>
			</div>";
	}

	/**
	 * Display reset password form
	 * @param string $errorMsg
	 */
	public function form_reset_password($errorMsg) {
		if (!empty($errorMsg))
			$errorMsg = "<p class='text-center text-danger'>$errorMsg</p>";

		$token = '';
		if (isset($_GET['token']))
			$token = $_GET['token'];

		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>	
					<h2 class='h2 mb-md-3'>Reset password</h2>
				</div>
				$errorMsg
				<form class='container form-signin mb-md-3' action='index.php?module=connection&action=reset_password&token=" . $token . "' method='post'>
					<div class='form-label-group mb-md-3'>
						<label for='email'>Email <span class='text-danger'>*</span></label> 
						<div class='input-group'>
							<input class='form-control' type='text' name='email' placeholder='Enter your email' required/> 
							<span class='input-group-text'>@example.com</span> 
						</div>	
					</div>
					<div class='form-label-group mb-md-3'>
						<label for='newPassword1'>New password <span class='text-danger'>*</span></label> 
						<input class='form-control' type='password' name='newPassword1' placeholder='Enter a new password' required/>
					</div>	
					<input class='form-control mb-md-5' type='password' name='newPassword2' placeholder='Enter the new password again' required/>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Reset Password'/>
				</form>
			</div>";
	}
}

?>