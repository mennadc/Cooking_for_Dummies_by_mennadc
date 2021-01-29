<?php  
if (!defined('CONST_INCLUDE'))
	die('Acces direct interdit !');
	
abstract class ContComp {
	protected $view;
	protected $model;

	public function __construct() {
		
	}

	/**
	 * Display composant view
	 */
	public function display() {
		$this->view->display();
	}
}

?>