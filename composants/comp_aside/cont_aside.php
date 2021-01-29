<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once 'view_aside.php';
include_once 'model_aside.php';
include_once './composants/cont_comp.php';

class ContAside extends ContComp {
	
	public function __construct() {
		$this->model = new ModelAside();
		$this->view = new ViewAside();
	}

	/**
	 * Display aside view
	 */
	public function display_view() {
		$this->view->init_view($this->model->get_most_popular_recipes());
		$this->display();
	}
}

?>