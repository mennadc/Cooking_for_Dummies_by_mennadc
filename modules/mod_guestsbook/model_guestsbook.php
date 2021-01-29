<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/model_generic.php';

class ModelGuestsBook extends ModelGeneric {

	public function __construct() {
	}

	/**
	 * Get user informations by user id
	 * @return array|int
	 */
	public function get_user_informations() {
		$queryPrepare = parent::$db->prepare(
			'SELECT user_firstname as firstname, user_lastname as lastname
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
	 * Send user guestsbook message
	 * @return int
	 */
	public function send_guestsbook_msg() {
		// Get guests book message datas
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$message = isset($_POST['message']) ? $_POST['message'] : '';

		// Check guests book message datas
		if (empty($name) || empty($message)) 
			return 1;
		else {
			// Insert guests book message in the db
			$insertPrepare = parent::$db->prepare(
				'INSERT INTO guestsbook 
				(guestsbook_author, guestsbook_message)
				VALUES 
				(:author, :message);');
			
			if (!$insertPrepare->execute(array(
				':author' => $name,
				':message' => $message)))
				return -1;
			else
				return 0;
		}
	}

	/**
	 * Get all guestsbook messages
	 * @return array|int
	 */
	public function get_guestsbook_msg() {
		$queryPrepare = parent::$db->prepare(
			'SELECT * 
			FROM guestsbook;');

		if ($queryPrepare->execute()) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover;
		}

		return -1;
	}
}
?>
