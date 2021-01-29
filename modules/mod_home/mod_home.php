<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_home.php';

class ModHome extends ModGeneric {

	public function __construct() {
		$this->controller = new ContHome();
		$this->controller->home_page();
	}
}
?>
