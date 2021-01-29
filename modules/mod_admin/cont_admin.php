<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_admin.php';
include_once 'model_admin.php';

class ContAdmin extends ContGeneric {

	public function __construct() {
		$this->model = new ModelAdmin();
		$this->view = new ViewAdmin();
	}
		
	/**
	 * Display delete error message
	 * @param string $peviousPage
	 */
	private function delete_error($previousPage) {
		$this->view->delete_error($previousPage);
	}

	/**
	 * Display delete success message
	 * @param string $page
	 */
	private function delete_success($page) {
		$this->view->delete_success($page);
	}

	/**
	 * Delete user account by user id
	 * and display delete success or error message
	 */
	public function delete_account() {
		if ($_SESSION['login']['id'] == $_GET['id']) 
			$this->notFound_page();
		else {
			if (!$this->model->delete_account())
				$this->delete_error('index.php?module=user&action=profile_page&id=' . $_GET['id']);
			else
				$this->delete_success("Go to <a href='index.php?module=home'>the home page</a>.");
		}
	}

	/**
	 * Delete comment by comment id
	 * and display delete success or error message
	 */
	public function delete_comment() {
		if (!isset($_GET['recipe_id']) || empty($_GET['recipe_id'])) 
			$this->notFound_page();
		else {
			if (!$this->model->delete_comment())
				$this->delete_error('index.php?module=recipe&action=recipe_page&id=' . $_GET['recipe_id']);
			else
				$this->delete_success("Go back to <a href='index.php?module=recipe&action=recipe_page&id=" . $_GET['recipe_id'] . "'>the previous page</a>."); 
		}	
	}

	/**
	 * Delete guests book message by guests book message id
	 * and display delete success or error message
	 */
	public function delete_guestsbook_msg() {
		if (!$this->model->delete_guestsbook_msg())
			$this->delete_error('index.php?module=guestsbook&action=guestsbook_page');
		else	
			$this->delete_success("Go back to <a href='index.php?module=guestsbook&action=guestsbook_page'>the previous page</a>."); 
	}

	/**
	 * Delete recip by recipe id
	 * and display delete success or error message
	 */
	public function delete_recipe() {
		if (!$this->model->delete_recipe())
			$this->delete_error('index.php?module=recipe&action=recipe_page&id=' . $_GET['id']);
		else
			$this->delete_success("Go to <a href='index.php?module=home'>the home page</a>."); 
	}
}
?>
