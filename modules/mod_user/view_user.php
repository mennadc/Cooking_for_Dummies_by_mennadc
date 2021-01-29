<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewUser extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Return active class or not by action  
	 * @param string $action
	 */
	private function activeAction($action) {
		if (isset($_GET['action'])) {
			return strpos($_GET['action'], $action) !== false ? 'active font-weight-bold' : '';
		} else 
			return '';
	}

	/**
	 * Display user account page
	 * @param string $avatar
	 * @param string $actionView
	 */
	public function account_page($avatar, $actionView) {
		echo "
			<script>removeActive();</script>
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='container d-flex'>
					<div class='col-md-4 '>
						<div class='card'>
							<a class='m-3' href='index.php?module=user&action=profile_page&id=" . $_SESSION['login']['id'] . "'>
								<img src='$avatar' alt='avatar' class='rounded-circle mx-auto d-block w-50'>
							</a>
							<div class='list-group'>
								<a class='list-group-item list-group-item-action " . $this->activeAction('overview') . "' href='index.php?module=user&action=overview'>Overview</a>
								<div class='dropdown list-group-item list-group-item-action " . $this->activeAction('settings') . "'>
									<a class='nav-link dropdown-toggle' data-bs-toggle='dropdown' href='#' role='button' aria-expanded='false'>Settings</a>
									<ul class='dropdown-menu'>
										<li><a class='dropdown-item " . $this->activeAction('password_settings') . "' href='index.php?module=user&action=password_settings'>Password settings</a></li>
										<li><a class='dropdown-item " . $this->activeAction('email_settings') . "' href='index.php?module=user&action=email_settings'>Email settings</a></li>
										<li><a class='dropdown-item " . $this->activeAction('avatar_settings') . "' href='index.php?module=user&action=avatar_settings'>Avatar settings</a></li>
									</ul>
								</div>
								<a class='list-group-item list-group-item-action' href='index.php?module=recipe&action=favorite_recipe&id=" . $_SESSION['login']['id'] . "'>Favorite recipes</a>
								<a class='list-group-item list-group-item-action' href='index.php?module=recipe&action=posted_recipes&id=" . $_SESSION['login']['id'] . "'>Posted recipes</a>
								<a class='list-group-item list-group-item-action " . $this->activeAction('subscriptions') . "' href='index.php?module=user&action=subscriptions&id=" . $_SESSION['login']['id'] . "'>Subscriptions</a>
								<a class='list-group-item list-group-item-action " . $this->activeAction('followers') . "' href='index.php?module=user&action=followers&id=" . $_SESSION['login']['id'] . "'>Followers</a>
								<a class='list-group-item list-group-item-action' href='index.php?module=contact&action=contact_page'>Contact</a>
								<a class='list-group-item list-group-item-action' href='index.php?module=faq'>FAQ</a>
								<a class='list-group-item list-group-item-action' href='index.php?module=guestsbook&action=guestsbook_page'>Guests book</a>
								<a class='list-group-item list-group-item-action " . $this->activeAction('newsletter') . "' href='index.php?module=user&action=newsletter_page'>Newsletter subscription</a>
								<a class='list-group-item list-group-item-action bg-danger text-white font-weight-bold' href='index.php?module=user&action=deletion_page'>Delete account</a>
							</div>
						</div>
					</div>
					$actionView
				</div>
			</div>";
	}

	/**
	 * Return user overview page
	 * @param string $user_account_informations
	 * @return string
	 */
	public function overview($user_account_informations) {
		$date = new DateTime($user_account_informations['date']);

		return "
			<div class='col-md-8'>
				<div class='text-center my-5'>
					<h2 class='h2 mb-md-3'>Overview</h2>
				</div>
				<ul class='container list-group list-group-flush'>
					<li class='list-group-item row'>
						<p class='col-sm-3 mb-0'>First name :</p>
						<p class='col-sm-9 text-secondary'>" . $user_account_informations['firstname']  . "</p>
					</li>
					<li class='list-group-item row'>
						<p class='col-sm-3 mb-0'>Last name :</p>
						<p class='col-sm-9 text-secondary'>" . $user_account_informations['lastname']  . "</p>
					</li>
					<li class='list-group-item row'>
						<p class='col-sm-3 mb-0'>Email :</p>
						<p class='col-sm-9 text-secondary'>" . $user_account_informations['email'] . "</p>
					</li>
					<li class='list-group-item row'>
						<p class='col-sm-3 mb-0'>Username :</p>
						<p class='col-sm-9 text-secondary'>" . $user_account_informations['username']  . "</p>
					</li>
					<li class='list-group-item row'>
						<p class='col-sm-3 mb-0'>Creation date :</p>
						<p class='col-sm-9 text-secondary'>" . $date->format('Y/m/d h:m:s') . "</p>
					</li>
				</ul>
			</div>";
	}

	/**
	 * Return user avatar settings page
	 * @param string $avatar
	 * @param string $msg
	 * @return string
	 */
	public function avatar_settings($avatar, $msg) {
		return "
			<div class='col-md-8'>
				<div class='text-center my-5'>
					<h2 class='h2'>Avatar settings</h2>
				</div>
				$msg
				<form class='container d-flex justify-content-center form-signin pt-md-5' action='index.php?module=user&action=change_avatar' method='post' enctype='multipart/form-data'>
					<div>
						<img class='rounded-circle mx-auto d-block col-md-8 mb-md-5 w-75' alt='default avatar' src='$avatar'>
						<input class='form-control-file mb-md-5' type='file' name='avatar' accept='.png, .jpg, .jpeg' required/>
						<input class='btn btn-lg btn-primary btn-block' type='submit' value='Change avatar'/>	
					</div>
				</form>
			</div>";
	}

	/**
	 * Return user password settings page
	 * @param string $msg
	 * @return string
	 */
	public function password_settings($msg) {
		return "
			<div class='col-md-8'> 
				<div class='text-center my-5'>	
					<h2 class='h2'>Password settings</h2>
				</div>
				$msg
				<form class='container form-signin pt-md-5' action='index.php?module=user&action=reset_password' method='post'>
					<div class='form-label-group mb-md-3'>
						<label for='currentpassword'>Current password <span class='text-danger'>*</span></label> 
						<input class='form-control' type='password' name='currentpassword' placeholder='Enter your current password' required/>
					</div>	
					<div class='form-label-group mb-md-5'>
						<label for='newpassword'>New password <span class='text-danger'>*</span></label> 
						<input class='form-control' type='password' name='newpassword' placeholder='Enter a new password' required/>
					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Reset Password'/>
				</form>
			</div>";
	}

	/**
	 * Return user email settings page
	 * @param string $msg
	 * @return string
	 */
	public function email_settings($msg) {
		return "
			<div class='col-md-8'>
				<div class='text-center my-5'>	
					<h2 class='h2'>Email settings</h2>
				</div>
				$msg
				<form class='container form-signin pt-md-5' action='index.php?module=user&action=reset_email' method='post'>
					<div class='form-label-group mb-md-3'>
						<label for='email'>Email <span class='text-danger'>*</span></label> 
						<div class='input-group'>
							<input class='form-control' type='text' name='email' placeholder='Enter a new email' required/> 
							<span class='input-group-text'>@example.com</span> 
						</div>	
					</div>
					<div class='form-label-group mb-md-5'>
						<label for='password'>Password <span class='text-danger'>*</span></label> 
						<input class='form-control' type='password' name='password' placeholder='Enter your password' required/>					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Reset email'/>
				</form>
			</div>";
	}

	/**
	 * Return user deletion page
	 * @param string $errorMsg
	 * @return string
	 */
	public function deletion_page($errorMsg) {
		if (!empty($errorMsg))
			$errorMsg = "<p class='text-center text-danger'>$errorMsg</p>";

		return "
			<div class='col-md-8'>
				<div class='text-center my-5'>	
					<h2 class='h2 mb-md-5'>Delete account</h2>
					<p>If you delete your account, you will not be able to get it back</p>
				</div>
				$errorMsg
				<div class='container d-flex justify-content-around pt-md-5'>
					<a class='btn btn-success font-weight-bold' href='index.php?module=user&action=overview'>No, go back to my profile</a>
					<a class='btn btn-danger font-weight-bold' href='index.php?module=user&action=delete_account'>Yes, delete my account</a>
				</div>
			</div>";
	}

	/**
	 * Return user newsletter page
	 * @param string $description
	 * @param string $newsletterBtn
	 * @param string $errorMsg
	 * @return string
	 */
	public function newsletter_page($description, $newsletterBtn, $errorMsg) {
		if (!empty($errorMsg))
			$errorMsg = "<p class='text-center text-danger'>$errorMsg</p>";

		return "
			<div class='col-md-8'>
				<div class='text-center my-5'>	
					<h2 class='h2 mb-md-5'>Newsletter subscription</h2>
					<p>$description</p>
				</div>
				$errorMsg
				<div class='container d-flex justify-content-center pt-md-5'>
					$newsletterBtn	
				</div>
			</div>";
	}

	/**
	 * Display user profile page
	 * @param string $avatar
	 * @param array $userInfo
	 * @param int $recipesCount
	 * @param int $subscriptionsCount
	 * @param int $followersCount
	 * @param string $deleteAccountButton
	 * @param string $subBtn
	 */
	public function profile_page($avatar, $userInfo, $recipesCount, $subscriptionsCount, $followersCount, $deleteAccountButton, $subBtn) {
		$date = new DateTime($userInfo['date']);
	
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>User profile</h2>
				</div>
				<div id='user-profile_cards' class='mt-5 py-4 px-4 container w-100'>
					<div class='d-flex justify-content-center mb-md-4'>
						<div class='mr-md-2'>
							<a href='index.php?module=user&action=profile_page&id=" . $_GET['id'] . "'>
								<img class='rounded-circle mx-auto d-block col-md-11' src='" . $avatar . "'>
							</a>
						</div>
						<div class='ml-3 w-100'>
							<div class='d-flex justify-content-between'>
								<div class='mb-md-3'>
									<h3 class='h3 mb-md-1'><a class='user-name_link'  href='index.php?module=user&action=profile_page&id=" . $_GET['id'] . "'>" . ucfirst($userInfo['firstname']) . " " . ucfirst($userInfo['lastname']) . "</a></h3>
									<p class='text-secondary mb-md-2'><span>@</span>" . $userInfo['username'] . "</p>
									<p class='text-secondary'>Joined Cooking for Dummies on " . $date->format('Y/m/d') . "</p>
								</div>
								<div class='button'>
									$deleteAccountButton
									$subBtn
								</div>
							</div>
							<div class='py-3 mt-2 d-flex justify-content-around rounded user_stats'>
								<div class='d-flex flex-column'>
									<h5><a href='index.php?module=recipe&action=posted_recipes&id=" . $_GET['id'] . "'>Posted recipes</a></h5>
									<p>" . $recipesCount . "</p>
								</div>
								<div class='d-flex flex-column'>
									<h5><a href='index.php?module=user&action=followers&id=" . $_GET['id'] . "'>Followers</a></h5>
									<p>" . $followersCount . "</p>
								</div>
								<div class='d-flex flex-column'>
									<h5><a href='index.php?module=user&action=subscriptions&id=" . $_GET['id'] . "'>Subscriptions</a></h5>
									<p>" . $subscriptionsCount . "</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>";
	}

	/**
	 * Display user subscriptions or followers page
	 * @param string $title
	 * @param array|int $users
	 */
	public function subscription_page($title, $users) {
		$result = "
			<div class='container mt-md-5 mb-md-5 p-4 pb-md-4 bg-white'>
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>" . $title . "</h2>
					<hr>
				</div>";
		
		if (empty($users) || !is_array($users)) 
			$result .= "
				<p class='text-center'>No users found.</p>";
		else {
			foreach ($users as $user) {
				$date = new DateTime($user['date']);
				
				$result .= "
				<div class='recipe-user_cards container mt-md-5 py-4 px-0'>
					<div class='d-flex justify-content-center'>
						<div class='mr-md-2'>
							<a href='index.php?module=user&action=profile_page&id=" . $user['id'] . "'>
								<img class='rounded-circle mx-auto d-block col-md-8' src='" . $user['avatar'] . "'>
							</a>
						</div>
						<div class='align-center mt-md-3 ml-md-3 w-100'>
							<h3 class ='h3 mb-md-1 mt-0'><a class='user-name_link' href='index.php?module=user&action=profile_page&id=" . $user['id'] . "'>" . ucfirst($user['firstname']) . " " . ucfirst($user['lastname']) . "</a></h3>
							<p class='text-secondary mb-md-2'><span>@</span>" . $user['username'] . "</p>
							<p class='text-secondary'>Joined on " . $date->format('Y/m/d') . "</p>
						</div>
					</div>
				</div>";
			}
		}

		$result .= "
			</div>";

		echo $result;
	}
}
?>