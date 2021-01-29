<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_legalnotice.php';

class ModLegalNotice extends ModGeneric {

	public function __construct() {
		$this->controller = new ContLegalNotice();
		$this->controller->legal_notice_page();
	}
}
?>
