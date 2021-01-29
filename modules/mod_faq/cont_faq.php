<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_faq.php';
include_once 'model_faq.php';

class ContFaq extends ContGeneric {

	public function __construct() {
		$this->model = new ModelFaq();
		$this->view = new ViewFaq();
	}
		
	/**
	 * Display faq page
	 */
	public function faq_page() {
		$this->view->faq_page();
	}
}
?>
