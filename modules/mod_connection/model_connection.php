<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/model_generic.php';

class ModelConnection extends ModelGeneric {

	public function __construct() {

	}

	/**
	 * Get default avatar from json file
	 * @return string
	 */
	public function get_default_avatar() {
		return json_decode(file_get_contents('./resources/json_datas/images.json'), true)[1]['defaultAvatar'];
	}

	/**
	 * Sign up a user
	 * @return int
	 */
	public function signup() {
		// Get avatar
		if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
			// Check avatar dimensions
			if (!ImageLoader::checkAvatarDimensions($_FILES['avatar']['tmp_name']))
				return 1;
			else 
				$avatar = ImageLoader::load_image($_FILES['avatar']['tmp_name']);
		} else
			$avatar = json_decode(file_get_contents('./resources/json_datas/images.json'), true)[1]['defaultAvatar'];

		// Get others entered user informations
		$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
		$username = isset($_POST['username']) ? strtolower(trim($_POST['username'])) : '';
		$password = isset($_POST['password']) ? hash('md5', trim($_POST['password'])) : '';
		$firstName = isset($_POST['firstname']) ? strtolower(trim($_POST['firstname'])) : '';
		$lastName = isset($_POST['lastname']) ? strtolower(trim($_POST['lastname'])) : '';

		// Check users entered informations are valid
		if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))
			return 2;	
		else {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_name 
				FROM user 
				WHERE user_name = :username OR user_email = :email;');
			
			if (!$queryPrepare->execute(array(
				':username' => $username, 
				':email' => $email)))
				return -1;
			else {
				$queryRecover = $queryPrepare->fetchAll();

				// Check users informations repetitions in the db 
				if (!empty($queryRecover))
					return 3;
				else {
					// Insert the user
					$insertPrepare = parent::$db->prepare(
						'INSERT INTO user 
						(user_firstname, user_lastname, user_email, user_name, user_password, user_avatar, role_id)
						VALUES (:firstname, :lastname, :email, :username, :password, :avatar, :role);');
					
					if (!$insertPrepare->execute(array(
						':firstname' => $firstName,
						':lastname' => $lastName,
						':email' => $email,
						':username' => $username,
						':password' => $password, 
						':avatar' => $avatar,
						':role' => 2)))
						return -1;
					else
						return 0;
				}
			}
		}
	}

	/**
	 * Sign in a user
	 * @return int
	 */
	public function signin() {
		// Get user informations
		$login = isset($_POST['login']) ? strtolower(trim($_POST['login'])) : '';
		$password = isset($_POST['password']) ? hash('md5', trim($_POST['password'])) : '';

		// Check users entered informations are valid
		if (empty($login) || empty($password))
			return 1;
		else {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_id as id, role_id as role 
				FROM user 
				WHERE (user_name = :login OR user_email = :login) AND user_password = :password;');
			
			if (!$queryPrepare->execute(array(
				':login' => $login, 
				':password' => $password)))
				return -1;
			else {
				$queryRecover = $queryPrepare->fetchAll();
					
				// Check user presence in the db
				if (empty($queryRecover)) 
					return 2;
				else {
					// Sign in user
					$_SESSION['login'] = $queryRecover[0];

					// Initialise remember cookie
					if (isset($_POST['remember']) && !empty($_POST['remember']))
						setcookie('login', $login , time() + (10 * 365 * 24 * 60 * 60));
					else if (isset($_COOKIE['login']))
						setcookie('login', '');
					
					return 0;
				}
			}
		}
	}

	/**
	 * Sign out a user
	 */
	public function signout() {	
		unset($_SESSION['login']);
	}

	/**
	 * Delete existing connection token of a user
	 * @param string $email
	 * @return boolean
	 */
	private function delete_existing_token($email) {
		// Delete the existing token
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM passwordresets
			WHERE user_email = :email;');
		
		return $deletePrepare->execute(array(
			':email' => $email));
	}

	/**
	 * Send mail reset password
	 * @return int
	 */
	public function send_mail_reset_password() {
		// Get user email
		$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';

		// Check user email is valid
		if (empty($email) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))
			return 1;
		else {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_email as email, user_password as password 
				FROM user
				WHERE user_email = :email;');
			
			if (!$queryPrepare->execute(array(
				':email' => $email)))
				return -1;
			else {
				$queryRecover = $queryPrepare->fetchAll();

				// Check user email exists in the db
				if (empty($queryRecover)) 
					return 2;
				else {
					$queryPrepare = parent::$db->prepare(
						'SELECT passwordresets_token 
						FROM passwordresets 
						WHERE user_email = :email;');
					
					if (!$queryPrepare->execute(array(
						':email' => $email)))
						return -1;
					else {
						$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
						
						// Check token already exists for the user 
						if (!empty($queryRecover)) {
							// Delete the existing token
							if (!$this->delete_existing_token($email))
								return -1;
						}
					}
					// Generate a token
					$token = bin2hex(random_bytes(50));

					$insertPrepare = parent::$db->prepare(
						'INSERT INTO passwordresets 
						(passwordresets_token, user_email)
						VALUES (:token, :email);');

					if (!$insertPrepare->execute(array(
						':token' => $token,
						':email' => $email)))
							return -1;
					else {
						// Send password reset mail 
						if (!MailSender::send_mail(
							$email, 
							"Reset your password on Cooking for Dummies",
							"<p>Hi there, click on this <a href='index.php?module=connection&action=form_reset_password&token=$token'>link</a> to reset your password on our site.</p>"))
							return -1;
						else
							return 0;
					}
				}
			}
		}
	}

	/**
	 * Check if  connection token of a user exists
	 * @return boolean
	 */
	public function token_exists() {
		if (isset($_GET['token'])) {
			// Get the token
			$token = $_GET['token'];

			$queryPrepare = parent::$db->prepare(
				'SELECT passwordresets_token as token 
				FROM passwordresets 
				WHERE passwordresets_token = :token;');
			
			if ($queryPrepare->execute(array(
				':token' => $token))) {
				$queryRecover = $queryPrepare->fetchAll();
				
				// Check the presence of a token in the db
				if (!empty($queryRecover))
					return true;
			}
		}
		return false;
	}

	/**
	 * Reset user password
	 * @return int
	 */
	public function reset_password() {
		// Get user informations
		$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
		$newPassword1 = isset($_POST['newPassword1']) ? hash('md5', trim($_POST['newPassword1'])) : '';
		$newPassword2 = isset($_POST['newPassword2']) ? hash('md5', trim($_POST['newPassword2'])) : '';

		// Check user informations are valid
		if (empty($newPassword1) || empty($newPassword2) || empty($email) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))
			return 1;
		// Check entered passwords are equal
		else if (!strcmp($newPassword1, $newPassword2) == 0)
			return 2;
		else {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_email 
				FROM passwordresets 
				WHERE user_email = :email AND passwordresets_token = :token;');
			
			if (!$queryPrepare->execute(array(
				':email' => $email,
				':token' => $_GET['token'])))
				return -1;
			else {
				$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

				// Check entered email exists in the db
				if (empty($queryRecover))
					return 3; 
				else {
					$queryPrepare = parent::$db->prepare(
						'SELECT user_password as password 
						FROM user 
						WHERE user_email = :email;');
					
					if (!$queryPrepare->execute(array(
						':email' => $email)))
						return -1;
					else {
						$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
						
						// Check entered email exists in the db 
						if (empty($queryRecover))
							return 3;
						// Check password entered exists in the db
						else if ($newPassword1 == $queryRecover[0])
							return 4;
						else {
							// Change the password
							$updatePrepare = parent::$db->prepare(
								'UPDATE user 
								SET user_password = :password
								WHERE user_email = :email;');
							
							if (!$updatePrepare->execute(array(
								':password' => $newPassword1, 
								':email' => $email)))
									return -1;
							else {
								// Delete the existing token
								if (!$this->delete_existing_token($email))
									return -1;
								else {
									// Send mail in order to warn against password reset
									MailSender::send_mail(
										$email, 
										'Password reset on Cooking for Dummies', 
										"<p>Hi there, your password has recently been reset. If you are not at the origin of this action, report it to us by clicking on the following <a href='index.php?module=contact&action=contact_page'>link</a>.</p>");
									
									return 0;
								}
							}
						}
					}
				}
			}
		}
	}
}
?>