<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/model_generic.php';

class ModelUser extends ModelGeneric {

	public function __construct() {

	}

	/**
	 * Get avatar by user id
	 * @return string
	 */
	public function get_avatar($id) {
		$queryPrepare = parent::$db->prepare(
			'SELECT user_avatar 
			FROM user 
			WHERE user_id = :id;');

		if ($queryPrepare->execute(array(
			':id' => $id))) {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

			if (!empty($queryRecover))
				return $queryRecover[0];
		}

		return json_decode(file_get_contents('./resources/json_datas/images.json'), true)[0]['errorImage'];
	}

	/**
	 * Get user account informations by user id
	 * @return array|int
	 */
	public function get_user_account_informations() {
		$queryPrepare = parent::$db->prepare(
			'SELECT user_firstname as firstname, user_lastname as lastname, user_email as email, user_name as username, user_creationdate as date 
			FROM user
			WHERE user_id = :id;');

		if ($queryPrepare->execute(array(
			':id' => $_SESSION['login']['id']))) {
			$queryRecover = $queryPrepare->fetchAll();
			
			if (!empty($queryRecover))
				return $queryRecover[0];
		}

		return -1;
	}

	/**
	 * Reset user password
	 * @return int
	 */
	public function reset_password() {
		// Get entered datas
		$newPassword = isset($_POST['newpassword']) ? hash('md5', trim($_POST['newpassword'])) : '';
		$currentPassword = isset($_POST['currentpassword']) ? hash('md5', trim($_POST['currentpassword'])) : '';

		// Check entered data are valid
		if (empty($newPassword) || empty($currentPassword)) 
			return 1;
		else {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_email as email, user_password as password 
				FROM user 
				WHERE user_id = :id AND user_password = :password;');
			if (!$queryPrepare->execute(array(
				':id' => $_SESSION['login']['id'],
				':password' => $currentPassword)))
				return -1;
			else {
				$queryRecover = $queryPrepare->fetchAll();

				// Check user exists in the db
				if (empty($queryRecover))
					return 2;				
				// Check user current password is equals to new entered password
				else if ($newPassword == $queryRecover[0]['password'])
					return 3;
				else {
					// Update user password
					$updatePrepare = parent::$db->prepare(
						'UPDATE user 
						SET user_password = :password
						WHERE user_id = :id;');
					if (!$updatePrepare->execute(array(
						':password' => $newPassword, 
						':id' => $_SESSION['login']['id'])))
						return -1;
					else {
						MailSender::send_mail(
							$queryRecover[0]['email'],
							'Password reset on Cooking for Dummies',
							"<p>Hi there, your password has recently been reset. If you are not at the origin of this action, report it to us by clicking on the following <a href='index.php?module=contact&action=contact_page'>link</a>.</p>");
						return 0;
					}
				}
			}
		}
	}

	/**
	 * Reset user email
	 * @return int
	 */
	public function reset_email() {
		// Get entered datas
		$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
		$password = isset($_POST['password']) ? hash('md5', trim($_POST['password'])) : '';

		// Check entered datas are valid
		if (empty($email) || empty($password) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email)) 
			return 1;
		else {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_id as id, user_email as email 
				FROM user 
				WHERE user_id = :id AND user_password = :password;');
			if (!$queryPrepare->execute(array(
				':id' => $_SESSION['login']['id'],
				':password' => $password)))
				return -1;
			else {
				$queryRecover = $queryPrepare->fetchAll();

				// Check password is wrong
				if (empty($queryRecover)) 
					return 2;
				// Check user already use entered email
				else if (strcmp($email, $queryRecover[0]['email']) == 0)
					return 3;
				else {
					$currentEmail = $queryRecover[0]['email'];

					$queryPrepare = parent::$db->prepare(
						'SELECT user_email as email 
						FROM user 
						WHERE user_id != :id AND user_email = :email;');
					if (!$queryPrepare->execute(array(
						':id' => $queryRecover[0]['id'], 
						':email' => $email)))
						return -1;
					else {
						$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
						
						// Check other user has entered email 
						if (!empty($queryRecover))
							return 4;
						else {
							// Update user email
							$updatePrepare = parent::$db->prepare(
								'UPDATE user 
								SET user_email = :email
								WHERE user_id = :id;');
							if (!$updatePrepare->execute(array(
								':email' => $email, 
								':id' => $_SESSION['login']['id'])))
								return -1;
							else  {
								MailSender::send_mail(
									$currentEmail,
									'Email reset on Cooking for Dummies',
									"<p>Hi there, your email has recently been reset. If you are not at the origin of this action, report it to us by clicking on the following <a href='index.php?module=contact&action=contact_page'>link</a>.</p>");
								return 0;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Change user avatar
	 * @param int
	 */
	public function change_avatar() {
		// Check sent avatar is valid
		if (!isset($_FILES['avatar']) || empty($_FILES['avatar']['name'])) 
			return 1;
		else {
			// Check sent avatar dimensions
			if (!ImageLoader::checkAvatarDimensions($_FILES['avatar']['tmp_name']))
				return 2;
			else 
				$avatar = ImageLoader::load_image($_FILES['avatar']['tmp_name']);
		}

		$queryPrepare = parent::$db->prepare(
			'SELECT user_id as id 
			FROM user 
			WHERE user_id = :id AND user_avatar = :avatar;');
		
		if (!$queryPrepare->execute(array(
			':id' => $_SESSION['login']['id'], 
			':avatar' => $avatar)))
				return -1;
		else {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
			
			// Check user already has sent avatar 
			if (!empty($queryRecover))
				return 3;
			else {
				// Update the avatar
				$updatePrepare = parent::$db->prepare(
					'UPDATE user 
					SET user_avatar = :avatar
					WHERE user_id = :id;');
				if (!$updatePrepare->execute(array(
					':avatar' => $avatar, 
					':id' => $_SESSION['login']['id'])))
					return -1;
				else
					return 0;
			}
		}
	}

	/**
	 * Delete user account by id
	 * @return boolean
	 */
	public function delete_account() {
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM user  
			WHERE user_id = :id;');
		return $deletePrepare->execute(array(
			':id' => $_SESSION['login']['id']));
	}

	/**
	 * Check if user is subscribed to the newsletter 
	 * @return int
	 */
	public function is_subscribed_newsletter() {
		$queryPrepare = parent::$db->prepare(
			'SELECT user_id
			FROM newsletter
			WHERE user_id = :id;');

		if (!$queryPrepare->execute(array(
			':id' => $_SESSION['login']['id'])))
			return -1;
		else {
			$queryRecover = $queryPrepare->fetchAll();
			
			// Check user is in newsletter table 
			if (!empty($queryRecover))
				return 1;
			else 
				return 0;
		}
	}

	/**
	 * Subscribe a user to the newsletter
	 * @param boolean
	 */
	public function subscribe_newsletter() {
		$insertPrepare = parent::$db->prepare(
			'INSERT INTO newsletter 
			(user_id)
			VALUES (:id);');

		if ($insertPrepare->execute(array(
			':id' => $_SESSION['login']['id']))) {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_email
				FROM user
				WHERE user_id = :id;');
	
			if ($queryPrepare->execute(array(
				':id' => $_SESSION['login']['id']))) {
				$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
	
				if (!empty($queryRecover)) {
					// Send subscribe newsletter mail 
					MailSender::send_mail(
						$queryRecover[0],
						'Subscribing to Cooking for Dummies newsletter',
						"<p>You have recently subscribed to our newsletter ! You will be regularly informed about news from the Cooking for Dummies website !</p>
						<p>Click on this <a href='index.php?module=home'>link</a> to visit our site.</p>");
		
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Unsubscribe a user to the newsletter
	 * @param boolean
	 */
	public function unsubscribe_newsletter() {
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM newsletter
			WHERE user_id = :id;');
		if ($deletePrepare->execute(array(
			':id' => $_SESSION['login']['id']))) {
			$queryPrepare = parent::$db->prepare(
				'SELECT user_email
				FROM user
				WHERE user_id = :id;');
	
			if ($queryPrepare->execute(array(
				':id' => $_SESSION['login']['id']))) {
				$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
	
				if (!empty($queryRecover)) {
					// Send unsubscribe newsletter mail 
					MailSender::send_mail(
						$queryRecover[0],
						'Unsubscribing to Cooking for Dummies newsletter',
						"<p>You have unsubscribed to our newsletter ! You will no longer be kept informed of the site's news.</p>
						<p>If you want to re-subscribe to our newsletter, click on this <a href='index.php?module=home'>link</a>, log in and re-subscribe to our newsletter.</p>");
		
					return true;
				}
			}	
		}

		return false;
	}

	/**
	 * Check if user is following another user 
	 * @return int
	 */
	public function is_following() {
		$queryPrepare = parent::$db->prepare(
			'SELECT * 
			FROM follow
			WHERE follow_subscription = :subscription AND follow_follower = :follower;');

		if (!$queryPrepare->execute(array(
			':subscription' => $_GET['id'],
			':follower' => $_SESSION['login']['id'])))
			return -1;
		else {
			$queryRecover = $queryPrepare->fetchAll();
			
			// Check 'follower' is following to 'subscription' 
			if (!empty($queryRecover))
				return 1;
			else 
				return 0;
		}
	}

	/**
	 * GEt user profile informations by id
	 * @return array|int
	 */
    public function get_user_profile_informations() {
        $queryPrepare = parent::$db->prepare(
			'SELECT user_firstname as firstname, user_lastname as lastname, user_name as username, user_creationdate as date
			FROM user 
			WHERE user_id = :id;');

		if ($queryPrepare->execute(array(
			':id' => $_GET['id']))) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover[0];
		}
		
		return -1;
    }

	/**
	 * Get number of posting recipes of a user 
	 * @return array|int
	 */
	public function get_recipes_count() {
		$queryPrepare = parent::$db->prepare(
			'SELECT count(recipe_id)
			FROM recipe 
			WHERE user_id = :id
			GROUP BY user_id;');
		
		if (!$queryPrepare->execute(array(
			':id' => $_GET['id'])))
			return -1;
		else
			return $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
	}
	
	/**
	 * Get number of subscriptions of a user 
	 * @return array|int
	 */
	public function get_subscriptions_count() {
		$queryPrepare = parent::$db->prepare(
			'SELECT count(u.user_id)
			FROM follow f INNER JOIN user u ON (f.follow_subscription = u.user_id)
			WHERE f.follow_follower = :id
			GROUP BY f.follow_follower;');
		
		if (!$queryPrepare->execute(array(
			':id' => $_GET['id'])))
			return -1;
		else
			return $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
	}

	/**
	 * Get number of followers of a user 
	 * @return array|int
	 */
	public function get_followers_count() {
		$queryPrepare = parent::$db->prepare(
			'SELECT count(u.user_id)
			FROM follow f INNER JOIN user u ON (f.follow_follower = u.user_id)
			WHERE f.follow_subscription = :id
			GROUP BY f.follow_subscription;');
		
		if (!$queryPrepare->execute(array(
			':id' => $_GET['id'])))
			return -1;
		else
			return $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
	}

	/**
	 * Get subscriptions of a user
	 * @return array|int
	 */
	public function get_subscriptions() {
        $queryPrepare = parent::$db->prepare(
			'SELECT user_id as id, user_firstname as firstname, user_lastname as lastname, user_name as username, user_creationdate as date, user_avatar as avatar 
			FROM follow f INNER JOIN user u ON (f.follow_subscription = u.user_id)
			WHERE f.follow_follower = :id;');
		
		if (!$queryPrepare->execute(array(
			':id' => $_GET['id']))) 
			return -1;
		else
			return $queryPrepare->fetchAll();
	}
	
	/**
	 * Get followers of a user
	 * @return array|int
	 */
	public function get_followers() {
        $queryPrepare = parent::$db->prepare(
			'SELECT user_id as id, user_firstname as firstname, user_lastname as lastname, user_name as username, user_creationdate as date, user_avatar as avatar
			FROM follow f INNER JOIN user u ON (f.follow_follower = u.user_id)
			WHERE f.follow_subscription = :id;');
		
		if (!$queryPrepare->execute(array(
			':id' => $_GET['id'])))
			return -1;
		else
			return $queryPrepare->fetchAll();
	}

	/**
	 * Unfollow a user
	 * @return boolean
	 */
	public function unfollow() {
		// Delete the subscription from the followers subscriptions list
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM follow 
			WHERE follow_subscription = :subscription AND follow_follower = :follower;');
		
		return $deletePrepare->execute(array(
			':follower' => $_SESSION['login']['id'],
			':subscription' => $_GET['id']));
	}

	/**
	 * Follow a user
	 * @return boolean
	 */
	public function follow() {
		// Insert the subscription to the followers subscriptions list
		$insertPrepare = parent::$db->prepare(
			'INSERT INTO follow 
			(follow_follower, follow_subscription) VALUES (:follower, :subscription);');
		
		return $insertPrepare->execute(array(
			':follower' => $_SESSION['login']['id'],
			':subscription' => $_GET['id']));
	}
}
?>
