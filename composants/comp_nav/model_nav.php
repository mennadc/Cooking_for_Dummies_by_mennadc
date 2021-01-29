<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');
	
include_once './db/db_connection.php';
include_once './composants/model_comp.php';

class ModelNav extends ModelComp {
	
	public function __construct() {

	}
	public function get_new_recipes(){
		$queryPrepare = parent::$db->prepare(
			"SELECT distinct r.recipe_id as id, r.recipe_title as title, r.recipe_date as date, r.user_id, user_name as username
			FROM recipe r
			INNER JOIN user using (user_id)
			INNER JOIN follow f ON (r.user_id = f.follow_subscription) 
			WHERE f.follow_follower = :id AND r.recipe_id NOT IN (
				SELECT r.recipe_id
				FROM recipe r
				INNER JOIN haveseen h ON (h.recipe_id = r.recipe_id)
				WHERE h.user_id = :id
			)
			LIMIT 2;");

		if (!$queryPrepare->execute(array(
			':id' => $_SESSION['login']['id'])))
			return -1;
		else  
			return $queryPrepare->fetchAll();
	}

}
?>





