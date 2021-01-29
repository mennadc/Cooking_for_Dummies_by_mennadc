<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once 'view_nav.php';
include_once 'model_nav.php';
include_once './composants/cont_comp.php';

class ContNav extends ContComp {
	public function __construct() {
		$this->model = new ModelNav();
		$this->view = new ViewNav();
	}

	/**
	 * Display nav view
	 */
	public function display_view() {
		if (isset($_SESSION['login']) && !empty($_SESSION['login']))
			$recipes = $this->model->get_new_recipes();
		else 
			$recipes = '';

		$this->view->init_view($recipes);
		$this->display();
	}
}

?>