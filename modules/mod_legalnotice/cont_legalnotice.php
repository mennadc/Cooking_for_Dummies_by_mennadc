<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_legalnotice.php';
include_once 'model_legalnotice.php';

class ContLegalNotice extends ContGeneric {

	public function __construct() {
		$this->model = new ModelLegalNotice();
		$this->view = new ViewLegalNotice();
	}
		
	/**
	 * Display legal notice page
	 */
	public function legal_notice_page() {
		$this->view->legal_notice_page();
	}
}
?>
