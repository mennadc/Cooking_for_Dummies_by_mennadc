<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_user.php';
include_once 'model_user.php';

class ContUser extends ContGeneric {

	public function __construct() {
		$this->model = new ModelUser();
		$this->view = new ViewUser();
	}
		
	/**
	 * Get user avatar
	 * @return string
	 */
	private function get_avatar($id) {
		return $this->model->get_avatar($id);
	}

	/**
	 * Display user account page
	 * @param string $action
	 */
	public function account_page($action) {
		$actionView = $this->$action();
		
		if($actionView == -1)
			$this->notFound_page();
		else
			$this->view->account_page($this->get_avatar($_SESSION['login']['id']), $actionView);
	}

	/**
	 * Return overview user account page
	 * @return string|int
	 */
	private function overview() {
		$userAccountInfo = $this->model->get_user_account_informations();
		
		if ($userAccountInfo == -1)
			return -1;
		else
			return $this->view->overview($userAccountInfo);
	}

	/**
	 * Return password settings page
	 * @param string $msg
	 * @return string
	 */
	private function password_settings($msg = '') {
		return $this->view->password_settings($msg);
	}

	/**
	 * Reset user password
	 * @return string
	 */
	private function reset_password() {
		$passwordReset = $this->model->reset_password();
		
		switch ($passwordReset) {
			case -1:
				$msg = "<p class='text-center text-danger'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
				break;
			case 0:
				$msg = "<p class='text-center text-success'>Password reset successfully !</p>";
				break;
			case 1:
				$msg = "<p class='text-center text-danger'>Invalid login details entered !</p>";
				break;
			case 2:
				$msg = "<p class='text-center text-danger'>Wrong current password entered !</p>";
				break;
			case 3:
				$msg = "<p class='text-center text-danger'>Enter a different password than your current password !</p>";
		}
		return $this->password_settings($msg);
	}

	/**
	 * Return email settings page
	 * @param string $msg
	 * @return string
	 */
	private function email_settings($msg = '') {
		return $this->view->email_settings($msg);
	}

	/**
	 * Reset user email
	 * @return string
	 */
	private function reset_email() {
		$emailReset = $this->model->reset_email();

		switch ($emailReset) {
			case -1:
				$msg = "<p class='text-center text-danger'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
				break;
			case 0:
				$msg = "<p class='text-center text-success'>Email reset successfully !</p>";
				break;
			case 1:
				$msg = "<p class='text-center text-danger'>Invalid login details entered !</p>";
				break;
			case 2:
				$msg = "<p class='text-center text-danger'>Wrong password entered !</p>";
				break;
			case 3:
				$msg = "<p class='text-center text-danger'>Enter a different email than your current email !</p>";
				break;
			case 4:
				$msg = "<p class='text-center text-danger'>Email already used by an other user !</p>";
		}
		return $this->email_settings($msg);
	}

	/**
	 * Return avatar settings page
	 * @param string $msg
	 * @return string
	 */
	private function avatar_settings($msg = '') {
		return $this->view->avatar_settings($this->get_avatar($_SESSION['login']['id']), $msg);
	}

	/**
	 * Change user avatar
	 * @return string
	 */
	private function change_avatar() {
		$avatarChange = $this->model->change_avatar();

		switch ($avatarChange) {
			case -1:
				$msg = "<p class='text-center text-danger'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
				break;
			case 0:
				$msg = "<p class='text-center text-success'>Avatar changed successfully !</p>";
				break;
			case 1:
				$msg = "<p class='text-center text-danger'>Invalid sent avatar !</p>";
				break;
			case 2:
				$msg = "<p class='text-center text-danger'>Avatar dimensions not respected ! (between 300x300px and 450x450px)</p>";
				break;
			case 3:
				$msg = "<p class='text-center text-danger'>Send a different avatar than your current avatar !</p>";
		}

		return $this->avatar_settings($msg);
	}

	/**
	 * Return deletion user account page
	 * @param string $errorMsg
	 * @return string
	 */
	private function deletion_page($errorMsg = '') {
		return $this->view->deletion_page($errorMsg);
	}

	/**
	 * Delete user account
	 * @return string
	 */
	private function delete_account() {
		if ($this->model->delete_account()) {
			unset($_SESSION['login']);
			header('Location: ?module=home');
		} else
			return $this->deletion_page("An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.");
	}

	/**
	 * Return newsletter page
	 * @param string $errorMsg
	 * @return string
	 */
	private function newsletter_page($errorMsg = '') {
		$subscribed = $this->model->is_subscribed_newsletter();
		
		switch ($subscribed) {
			case -1:
				$description = "<p class='text-danger'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
				$newsletterBtn = "<a class='btn btn-primary font-weight-bold' href='index.php?module=user&action=overview'>Go back to the overview page</a>";
				break;
			case 0:
				$description = "<p>You are not subscribed to our newsletter ! Subscribe to receive informations on recipes by email !</p>";
				$newsletterBtn = "<a class='btn btn-success font-weight-bold' href='index.php?module=user&action=subscribe_newsletter'>Subscribe to our newsletter</a>";
				break;
			case 1:
				$description = "<p>You are subscribed to our newsletter !</p>";
				$newsletterBtn = "<a class='btn btn-danger font-weight-bold' href='index.php?module=user&action=unsubscribe_newsletter'>Unsubscribe from our newsletter</a>";
		}

		return $this->view->newsletter_page($description, $newsletterBtn, $errorMsg);
	}

	/**
	 * Subscribe a user to the newsletter
	 * @return string
	 */
	private function subscribe_newsletter() {
		$subscribed = $this->model->subscribe_newsletter();
		
		if (!$subscribed)
			$errorMsg = "<p class='text-danger text-center'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
		else
			$errorMsg = '';

		return $this->newsletter_page($errorMsg);
	}

	/**
	 * Unsubscribe a user to the newsletter
	 * @return string
	 */
	private function unsubscribe_newsletter() {
		$unsubscribed = $this->model->unsubscribe_newsletter();
		
		if (!$unsubscribed)
			$errorMsg = "<p class='text-danger text-center'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
		else 
			$errorMsg = '';

		return $this->newsletter_page($errorMsg);
	}

	/**
	 * Check if user is followed
	 * @return boolean
	 */
	private function is_following() {
		return $this->model->is_following();
	}

	/**
	 * Check if the user is on personal page by user id
	 * @return boolean
	 */
	private function isOnPersonalPage() {
		return $_SESSION['login']['id'] == $_GET['id'];
	}

	/**
	 * Check if array is a not empty array 
	 * @param array|int $array
	 * @return boolean
	 */
	private function check_count_array($array) {
		return (is_array($array) && !empty($array)) ? $array[0] : '0';
	}

	/**
	 * Display page profile page
	 */
	public function profile_page() {
		$following = '';
		
		// Initialize delete account (admin) subscription buttons
		if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
			$deleteAccountButton = '';
			$subBtn = '';
		} else {
			if ($_SESSION['login']['role'] != 1 || $this->isOnPersonalPage())
				$deleteAccountButton = '';	
			else			
				$deleteAccountButton = "<a class='btn btn-danger mr-md-3 font-weight-bold' href='index.php?module=admin&action=delete_account&id=" . $_GET['id'] ."'>Delete account</a>";	

			if ($this->isOnPersonalPage())
				$subBtn = '';
			else {
				$following = $this->is_following();

				switch ($following) {
					case 0:
						$subBtn = "<a class='btn buttons__validation font-weight-bold' href='index.php?module=user&action=follow&id=" . $_GET['id'] . "'><i class='fa fa-check text-white'></i> Follow</a>";
						break;
					case 1:
						$subBtn = "<a class='btn buttons__undo font-weight-bold' href='index.php?module=user&action=unfollow&id=" . $_GET['id'] . "'><i class='fa fa-minus text-white'></i> Unfollow</a>";
						
				}
			} 
		} 

		$userInfo = $this->model->get_user_profile_informations();
		$recipesCount = $this->model->get_recipes_count();
		$subscriptionsCount = $this->model->get_subscriptions_count();
		$followersCount = $this->model->get_followers_count();

		// Check count user profile informations are valid
		if ($following == -1 || $userInfo  == -1 || $recipesCount == -1 || $subscriptionsCount == -1 || $followersCount == -1)
			$this->notFound_page();
		else  {
			// Initialize count user profile informations
			$recipesCount = $this->check_count_array($recipesCount);
			$subscriptionsCount = $this->check_count_array($subscriptionsCount);
			$followersCount = $this->check_count_array($followersCount);

			$this->view->profile_page($this->get_avatar($_GET['id']), $userInfo, $recipesCount, $subscriptionsCount, $followersCount, $deleteAccountButton, $subBtn);
		}
	}

	/**
	 * Display user subscriptions page
	 * @param string $title
	 * @param array|int $users
	 */
	private function subscription_page($title, $users) {
		$this->view->subscription_page($title, $users);
	}

	/**
	 * Get user subscriptions
	 */
	public function subscriptions() {
		$subscriptions = $this->model->get_subscriptions();

		if ($subscriptions == -1)
			$this->notFound_page();
		else 
			$this->subscription_page('Subscriptions', $subscriptions);
	}

	/**
	 * Get user followers
	 */
	public function followers() {
		$followers = $this->model->get_followers();

		if ($followers == -1)
			$this->notFound_page();
		else 
			$this->subscription_page('Followers', $followers);
	}

	/**
	 * Unfollow a user
	 */
	public function unfollow() {
		if ($this->isOnPersonalPage() || !$this->model->unfollow())
			$this->notFound_page();
		else 	
			header('Location: index.php?module=user&action=profile_page&id=' . $_GET['id']);
	}

	/**
	 * Unfollow a user
	 */
	public function follow() {
		if ($this->isOnPersonalPage() || !$this->model->follow())
			$this->notFound_page();
		else
			header('Location: index.php?module=user&action=profile_page&id=' . $_GET['id']);
	}
}
?>
