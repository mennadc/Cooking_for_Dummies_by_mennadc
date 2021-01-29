<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewAdmin extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display delete error message
	 * @param string $peviousPage
	 */
	public function delete_error($peviousPage) {
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>Deletion error</h2>
					<p>An error occurred during deletion ! Go back to <a href='$peviousPage'>the previous page</a>.</p>
				</div>
			</div>";
	}

	/**
	 * Display delete success message
	 * @param string $page
	 */
	public function delete_success($page) { 
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>Deletion success</h2>
					<p>The deletion operation was successful ! $page</p>
				</div>
			</div>";
	}

}
?>