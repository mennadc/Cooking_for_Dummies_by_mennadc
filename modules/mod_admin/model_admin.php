<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/model_generic.php';

class ModelAdmin extends ModelGeneric {

	public function __construct() {

	}

	/**
	 * Delete user account by user id
	 * @return boolean
	 */
	public function delete_account() {
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM user
			WHERE user_id = :id;');
		return $deletePrepare->execute(array(
			':id' => $_GET['id']));
	}

	/**
	 * Delete comment by comment id
	 * @return boolean
	 */
	public function delete_comment() {
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM comment
			WHERE comment_id = :id;');
		return $deletePrepare->execute(array(
			':id' => $_GET['id']));
	}

	/**
	 * Delete guests book message by guests book message id
	 * @return boolean
	 */
	public function delete_guestsbook_msg() {
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM guestsbook
			WHERE guestsbook_id = :id;');
		return $deletePrepare->execute(array(
			':id' => $_GET['id']));
	}

	/**
	 * Delete recip by recipe id
	 * @return boolean
	 */
	public function delete_recipe() {
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM recipe
			WHERE recipe_id = :id;');
		return $deletePrepare->execute(array(
			':id' => $_GET['id']));
	}
}
?>
