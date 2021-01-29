<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once 'view_footer.php';
include_once 'model_footer.php';
include_once './composants/cont_comp.php';

class ContFooter extends ContComp {
	public function __construct() {
		$this->view = new ViewFooter();
		$this->model = new ModelFooter();
	}

	/**
	 * Display footer view
	 */
	public function display_view() {
		$this->view->init_view();
		$this->display();
	}
}

?>
