<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_home.php';
include_once 'model_home.php';

class ContHome extends ContGeneric {

	public function __construct() {
		$this->model = new ModelHome();
		$this->view = new ViewHome();
	}
		
	/**
	 * Display home page
	 */
	public function home_page() {
		$this->view->home_page($this->model->get_most_popular_recipes_by_views(), $this->model->get_most_popular_recipes_by_rating(), $this->model->get_most_recent_recipes());
	}
}
?>
