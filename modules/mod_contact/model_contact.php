<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/model_generic.php';

class ModelContact extends ModelGeneric {

	public function __construct() {

	}

	/**
	 * Get user informations by user id
	 * @return array|int
	 */
	public function get_user_informations() {
		$queryPrepare = parent::$db->prepare(
			'SELECT user_firstname as firstname, user_lastname as lastname, user_email as email 
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
	 * Send user contact message
	 * @param int
	 */
	public function send_contact_msg() {
		// Get contact message datas
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
		$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
		$message = isset($_POST['message']) ? trim($_POST['message']) : '';

		// Check contact message datas are valid
		if (empty($name) || empty($email) || empty($subject) || empty($message) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email)) 
			return 1;
		else {
			// Insert the contact message in the db
			$insertPrepare = parent::$db->prepare(
				'INSERT INTO contact 
				(contact_name, contact_email, contact_subject, contact_message)
				VALUES 
				(:name, :email, :subject, :message);');
			
			if (!$insertPrepare->execute(array(
				':name' => $name,
				':email' => $email,
				':subject' => $subject,
				':message' => $message)))
				return -1;
			else
				return 0;
		}
	}
}
?>
