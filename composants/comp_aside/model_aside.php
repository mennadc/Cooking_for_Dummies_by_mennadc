<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');
	
include_once './composants/model_comp.php';

class ModelAside extends ModelComp {
	
	public function __construct() {

	}

	/**
	 * Get most popular recipes
	 * @return array|int
	 */
	public function get_most_popular_recipes() {
		$queryPrepare = parent::$db->prepare(
			'SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, recipe_views as views, user_id, user_name as username
			FROM recipe INNER JOIN user USING (user_id)
			ORDER BY recipe_views DESC, recipe_rating DESC LIMIT 3;');
		if ($queryPrepare->execute()) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover;
		}
		return -1;
	}
}
?>