<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');
	
class ViewGeneric {

	public function __construct() {
		ob_start();
	}

	/**
	 * Get display of modules views
	 * @return string|boolean
	 */
	public function getDisplay() {
		return ob_get_clean();
	}

	/**
	 * Display not found page
	 */
	public function notFound_page() {
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>404 error- Page not found</h2>
					<p>You might want to go to the <a href='index.php?module=home'>home page</a> or contact us on the <a href='index.php?module=contact&action=contact_page'>contact page</a></p>
				</div>
			</div>";
	}
}
