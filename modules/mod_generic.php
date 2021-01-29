<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

abstract class ModGeneric {
	protected $controller;

	public function __construct() {

	}
	
	/**
	 * Get display of modules views
	 * @return string|boolean
	 */
	public function getDisplay() {
		return $this->controller->getDisplay();
	}
}
?>
