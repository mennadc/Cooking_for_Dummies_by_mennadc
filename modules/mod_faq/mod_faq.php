<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_faq.php';

class ModFaq extends ModGeneric {

	public function __construct() {
		$this->controller = new ContFaq();
		$this->controller->faq_page();
	}
}
?>
