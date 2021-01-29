<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/model_generic.php';

class ModelHome extends ModelGeneric {

	public function __construct() {

	}

	/**
	 * Get most popular recipes by views
	 * @return array|int
	 */
	public function get_most_popular_recipes_by_views() {
		$queryPrepare = parent::$db->prepare(
			'SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username
			FROM recipe INNER JOIN user USING (user_id)
			ORDER BY recipe_views DESC
			LIMIT 3;');
			
		if ($queryPrepare->execute()) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover;
		}
		return -1;
	}

	/**
	 * Get most popular recipes by rating
	 * @return array|int
	 */
	public function get_most_popular_recipes_by_rating() {
		$queryPrepare = parent::$db->prepare(
			'SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username
			FROM recipe INNER JOIN user USING (user_id)
			ORDER BY recipe_rating DESC
			LIMIT 6;');
			
		if ($queryPrepare->execute()) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover;
		}
		return -1;
	}

	/**
	 * Get most recent recipes
	 * @return array|int
	 */
	public function get_most_recent_recipes() {
		$queryPrepare = parent::$db->prepare(
			'SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username 
			FROM recipe INNER JOIN user USING (user_id) 
			ORDER BY recipe_date DESC LIMIT 6;');
		if ($queryPrepare->execute()) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover;
		}
		return -1;
	}
}
?>
