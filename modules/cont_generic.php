<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

class ContGeneric {
	protected $model;
	protected $view;

	public function __construct() {
		
    }
	
	/**
	 * Get display of modules views
	 * @return string|boolean
	 */
    public function getDisplay() {
		return $this->view->getDisplay();
	}

	/**
	 * Display not found page
	 */
	public function notFound_page() {
		$this->view->notFound_page();
	}
}

?>