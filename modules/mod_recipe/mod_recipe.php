<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_recipe.php';

class ModRecipe extends ModGeneric {

	public function __construct() {
		$this->controller = new ContRecipe();
		if (isset($_GET['action']))
			$action = $_GET['action'];
		else
			$action = 'default';

		switch ($action) {
			case 'rate_recipe':
				if (!isset($_GET['rating']) || empty($_GET['rating'])) {
					$this->controller->notFound_page();
					break;
				}
			case 'comment_recipe':
			case 'favorite_recipe':
			case 'like_recipe':
			case 'dislike_recipe':
			
				if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
					$this->controller->notFound_page();	
					break;
				}
			case 'recipe_page':
			case 'posted_recipes':
				if (!isset($_GET['id']) || empty(($_GET['id']))) {
					$this->controller->notFound_page();
					break;
				}
			case 'write_recipe':
			case 'post_recipe':
			case 'simple_search_recipe':
			case 'advanced_search_page':
			case 'advanced_search':
			case 'get_encoded_units':
			case 'autocomplete':
			case 'search_recipe':
			case 'search_ingredient':
			case 'search_category':
			case 'recipes_subscriptions':
				$this->controller->$action();
				break;
			default:
				$this->controller->notFound_page();
		}
	}
}
?>
