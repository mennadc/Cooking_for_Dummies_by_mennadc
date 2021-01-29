<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/model_generic.php';

class ModelRecipe extends ModelGeneric {

	public function __construct() {

	}

	/**
	 * Get favorite reicpes of a user
	 * @return array|int 
	 */
	public function favorite_recipe() {
		$queryPrepare = parent::$db->prepare(
			'SELECT distinct l.recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, r.user_id, u.user_name as username 
			FROM recipe r INNER JOIN love l ON (l.recipe_id = r.recipe_id) 
			INNER JOIN user u ON (r.user_id = u.user_id)
			WHERE l.user_id = :id
			ORDER BY recipe_date DESC;');

		if (!$queryPrepare->execute(array(
			':id' => $_SESSION['login']['id'])))
			return -1;
		else 
			return $queryPrepare->fetchAll();
	}

	/**
	 * Get posting reicpes by a user
	 * @return array|int 
	 */
	public function get_posted_recipes() {
        $queryPrepare = parent::$db->prepare(
			'SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username 
			FROM recipe INNER JOIN user USING (user_id)
			WHERE user_id = :id
			ORDER BY recipe_date DESC;');

		if (!$queryPrepare->execute(array(
			':id' => $_GET['id'])))
			return -1;
		else 
			return $queryPrepare->fetchAll();
	}

	/**
	 * Search a recipe with simple recipe page
	 * @return array|int
	 */
	public function search_recipe($title) {
		$queryPrepare = parent::$db->prepare(
			'SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username 
			FROM recipe INNER JOIN user USING (user_id)
			WHERE recipe_title LIKE :title;');
		if (!$queryPrepare->execute(array(
			':title' => "%". $title . "%")))
			return -1;
		else
			return $queryPrepare->fetchAll();
	}

	/**
	 * Display recipe of subscriptions of a user by user id
	 */
	public function recipes_subscriptions() {
		$queryPrepare = parent::$db->prepare(
			"SELECT distinct r.recipe_id as id, r.recipe_title as title, r.recipe_date as date, r.user_id, user_name as username, r.recipe_rating as rating, r.recipe_image as image
			FROM recipe r
			INNER JOIN user using (user_id)
			INNER JOIN follow f ON (r.user_id = f.follow_subscription) 
			WHERE f.follow_follower = :id AND r.recipe_id NOT IN (
				SELECT r.recipe_id
				FROM recipe r
				INNER JOIN haveseen h ON (h.recipe_id = r.recipe_id)
				WHERE h.user_id = :id
			);");
		if (!$queryPrepare->execute(array(
			'id' => $_SESSION['login']['id'])))
			return -1;
		else 
			return $queryPrepare->fetchAll();
	}

	/**
	 * Search a recipe by ingredient name
	 * @return array|int
	 */
	public function search_ingredient() {
		$name = isset($_GET['name']) ? $_GET['name'] : '';

		if (!empty($name)) {
			$queryPrepare = parent::$db->prepare(
				"SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username 
				FROM recipe INNER JOIN user USING (user_id)
				INNER JOIN madeupof using (recipe_id)
				INNER JOIN ingredient using (ingredient_id)
				WHERE ingredient_name LIKE :name;");
			
			if ($queryPrepare->execute(array(
				':name' => "%" . $name . "%")))
				return $queryPrepare->fetchAll();
		}
		return -1;
	}

	/**
	 * Search a recipe by category entitled
	 * @return array|int
	 */
	public function search_category() {
		$category = isset($_GET['category']) ? $_GET['category'] : '';
		$table = isset($_GET['association']) ? $_GET['association'] : '';
		$entitled = isset($_GET['entitled']) ? $_GET['entitled'] : '';

		if (!empty($table) && !empty($entitled) && !empty($category)) {
			$queryPrepare = parent::$db->prepare(
				"SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username 
				FROM recipe r INNER JOIN user USING (user_id)
				INNER JOIN $table using (recipe_id)
				WHERE $category" . "_entitled LIKE :entitled;");

			if ($queryPrepare->execute(array(
				':entitled' => "%" . $entitled . "%")))
				return $queryPrepare->fetchAll();
		}
		return -1;
	}

	/**
	 * Get categories entitled
	 * @param array $categories
	 * @param string $tableName
	 * @return array
	 */
	private function get_categories_entitled($categories, $tableName) {
		$categoriesId = array();
		
		foreach ($categories as $category) {
			$categoryId = $this->get_category($category, $tableName);

			if ($categoryId == -1 || empty($categoryId))
				return -1;
			else 
				$categoriesId[] = $categoryId[0]; 
		}

		return $categoriesId;
	}

	/**
	 * Get ingredients id
	 * @param array $ingredients
	 * @return array
	 */
	private function get_ingredients_id($ingredients) {
		$ingredientsId = array();
		
		foreach ($ingredients as $ingredient) {
			$ingredientId = $this->get_ingredient($ingredient);

			if ($ingredientId == -1 || empty($ingredientId))
				return -1;
			else 
				$ingredientsId[] = $ingredientId[0]; 
		}
		
		return $ingredientsId;
	}

	/**
	 * Search a recipe with advanced recipe page
	 * @return array|int
	 */
	public function advanced_search() {
		$filters = [];
		$filters['title'] = isset($_POST['title']) ? strtolower($_POST['title']) : '';
		$filters['sliceNbr'] = isset($_POST['slicenbr']) ? $_POST['slicenbr'] : '';
		$filters['cost'] = isset($_POST['cost']) ? $_POST['cost'] : '';
		$filters['time'] = isset($_POST['time']) ? $_POST['time'] : '';
		$filters['origin'] = isset($_POST['origin']) ? strtolower($_POST['origin']) : '';
		$filters['difficultyLevel'] = isset($_POST['difficultylevel']) ? $this->init_difficulty_level(strtolower($_POST['difficultylevel'])) : '';
		$filters['calorie'] = isset($_POST['calorie']) && !empty($_POST['calorie']) ? strval($_POST['calorie']) : '';
		$filters['protide'] = isset($_POST['protide']) && !empty($_POST['protide']) ? strval($_POST['protide']) : '';
		$filters['lipid'] = isset($_POST['lipid']) && !empty($_POST['lipid']) ? strval($_POST['lipid']) : '';
		$filters['carbohydrate'] = isset($_POST['carbohydrate']) && !empty($_POST['carbohydrate']) ? strval($_POST['carbohydrate']) : '';
		$filters['fibre'] = isset($_POST['fibre']) && !empty($_POST['fibre']) ? strval($_POST['fibre']) : '';
		$filters['ingredients'] = isset($_POST['ingredient']) && !empty($_POST['ingredient'][0]) ? $_POST['ingredient'] : '';
		$filters['categories']['ustensile']['entitled'] = isset($_POST['ustensile']) && !empty($_POST['ustensile'][0]) ? $_POST['ustensile'] : '';
		$filters['categories']['ustensile']['association'] = 'need';
		$filters['categories']['theme']['entitled'] = isset($_POST['theme']) && !empty($_POST['theme'][0]) ? $_POST['theme'] : '';
		$filters['categories']['theme']['association'] = 'belongto';
		$filters['categories']['diet']['entitled'] = isset($_POST['diet']) && !empty($_POST['diet'][0]) ? $_POST['diet'] : '';
		$filters['categories']['diet']['association'] = 'beon';
		$filters['categories']['dishtype']['entitled'] = isset($_POST['dishtype']) && !empty($_POST['dishtype'][0]) ? $_POST['dishtype'] : '';
		$filters['categories']['dishtype']['association'] = 'define';

		if ((!empty($filters['ingredients']) && ($filters['ingredients'] = $this->get_ingredients_id($filters['ingredients'])) == -1)
			|| (!empty($filters['categories']['ustensile']['entitled']) && ($filters['categories']['ustensile']['entitled'] = $this->get_categories_entitled($filters['categories']['ustensile']['entitled'], 'ustensile'))  == -1)
			|| (!empty($filters['categories']['theme']['entitled']) && ($filters['categories']['theme']['entitled'] = $this->get_categories_entitled($filters['categories']['theme']['entitled'], 'theme')) == -1)
			|| (!empty($filters['categories']['diet']['entitled']) && ($filters['categories']['diet']['entitled'] = $this->get_categories_entitled($filters['categories']['diet']['entitled'], 'diet')) == -1)
			|| (!empty($filters['categories']['dishtype']['entitled']) && ($filters['categories']['dishtype']['entitled'] = $this->get_categories_entitled($filters['categories']['dishtype']['entitled'], 'dishtype')) == -1))
			return 1;
		else {
			$queryArgs = '';
			$joins = 'INNER JOIN user USING (user_id) ';

			foreach ($filters as $key => $value) {
				if (!empty($value)) {
					switch ($key) {
						case 'title':
						case 'time':
						case 'origin':
							$queryArgs .= "recipe_" . $key . " LIKE '%" . $value . "%' AND ";
							break;
						case 'ingredients':
							if (!strpos('INNER JOIN madeupof USING (ingredient_id)', $joins))
								$joins .= ' INNER JOIN madeupof USING (recipe_id)';

							foreach ($value as $ingredientId)
								$queryArgs .= "ingredient_id = $ingredientId OR ";

							$queryArgs = substr($queryArgs, 0, strlen($queryArgs) - 4) . ' AND ';
							break;
						case 'categories':
							foreach ($filters['categories'] as $key => $categories) {
								if (!empty($categories['entitled'])) {
									if (!strpos("INNER JOIN " . $categories['association'] . " USING (recipe_id)", $joins))
										$joins .= " INNER JOIN " . $categories['association'] . " USING (recipe_id)";
								
									foreach ($categories['entitled'] as $entitled)
										$queryArgs .= $key . "_entitled LIKE '%" . $entitled . "%' OR ";

									$queryArgs = substr($queryArgs, 0, strlen($queryArgs) - 4) . ' AND ';
								}
							}
							break;
						default:
							$queryArgs .= "recipe_" . $key . ' = ' . $value . ' AND ';
					}
				}
			}
			
			if (empty($queryArgs)) 
				return 2;
			else {
				$queryArgs = substr($queryArgs, 0, strlen($queryArgs) - 5);
				$queryPrepare = parent::$db->prepare(
					"SELECT recipe_id as id, recipe_title as title, recipe_date as date, recipe_rating as rating, recipe_image as image, user_id, user_name as username  
					FROM recipe $joins
					WHERE $queryArgs;");

				if (!$queryPrepare->execute())
					return -1;
				else 
					return $queryPrepare->fetchAll(); 
			}
		}
	}

	/**
	 * Get user avatar by user id
	 * @param $id
	 * @return string
	 */
	public function get_avatar($id) {
		$queryPrepare = parent::$db->prepare(
			'SELECT user_avatar 
			FROM user 
			WHERE user_id = :id;');

		if ($queryPrepare->execute(array(
			':id' => $id))) {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

			if (!empty($queryRecover))
				return $queryRecover[0];
		}

		return $this->get_decoded_json_datas('images')[0]['errorImage'];
	}

	/**
	 * Get recipe infos by recipe id
	 * @return array|int
	 */
	public function get_recipe_infos() {
		$queryPrepare = parent::$db->prepare(
			'SELECT r.*, user_name as username 
			FROM recipe r INNER JOIN user u USING (user_id)
			WHERE recipe_id = :id;');
		
		if ($queryPrepare->execute(array(
			':id' => $_GET['id']))) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover[0];
		}

		return -1;
	}

	/**
	 * Get recipe comments by recipe id
	 * @return array|int
	 */
	public function get_recipe_comments() {
		$queryPrepare = parent::$db->prepare(
			'SELECT comment_id as id, comment_date as date, comment_content as content, user_id, user_name as username 
			FROM comment INNER JOIN user using (user_id)
			WHERE recipe_id = :id;');
		
		if (!$queryPrepare->execute(array(
			':id' => $_GET['id'])))
			return -1;
		else
			return $queryPrepare->fetchAll();
	}

	/**
	 * Get recipe ingredients by recipe id
	 * @return array|int
	 */
	public function get_recipe_ingredients() {
		$queryPrepare = parent::$db->prepare(
			'SELECT madeupof_quantity as quantity, madeupof_unit as unit, ingredient_name as name
			FROM ingredient INNER JOIN madeupof using (ingredient_id) INNER JOIN recipe using (recipe_id) 
			WHERE recipe_id = :id;');
		
		if ($queryPrepare->execute(array(
			':id' => $_GET['id']))) {
			$queryRecover = $queryPrepare->fetchAll();

			if (!empty($queryRecover))
				return $queryRecover;
		}

		return -1;
	}

	/**
	 * Get categories by recipe id
	 * @return array|int
	 */
	public function get_categories() {
		$queryPrepare = parent::$db->prepare(
			"SELECT distinct coalesce(theme_entitled, dishtype_entitled, diet_entitled, ustensile_entitled)
			FROM recipe 
			INNER JOIN define using (recipe_id)
			LEFT JOIN belongto using (recipe_id)
			LEFT JOIN beon using (recipe_id)
			LEFT JOIN need using (recipe_id)
			WHERE recipe_id = :id
			LIMIT 7;");

		if ($queryPrepare->execute(array(
			':id' => $_GET['id']))) {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

			if (!empty($queryRecover))
				return $queryRecover;
		}

		return -1;
	}

	/**
	 * Get rating by recipe and user id
	 * @return int
	 */
	public function get_rating_user() {
		$queryPrepare = parent::$db->prepare(
			'SELECT rate_value
			FROM rate 
			WHERE user_id = :userId AND recipe_id = :id;');
		
		if ($queryPrepare->execute(array(
			':userId' => $_SESSION['login']['id'],
			':id' => $_GET['id']))) {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

			if (empty($queryRecover))
				return 0;
			else 
				return $queryRecover[0];
		}

		return -1;
	}

	/**
	 * Check if user liked the recipe by user id 
	 * @return bool
	 */
	public function is_liked() {
		$queryPrepare = parent::$db->prepare(
			'SELECT recipe_id 
			FROM love l
			WHERE user_id = :userid AND recipe_id = :id;');

		if ($queryPrepare->execute(array(
			':userid' => $_SESSION['login']['id'],
			':id' => $_GET['id']))) {
			if (!empty($queryPrepare->fetchAll()))
				return true;
		}
		return false;
	}

	/**
	 * Increment recipe views attribute 
	 * @return bool
	 */
	public function incrementViews() {
		$updatePrepare = parent::$db->prepare(
			'UPDATE recipe
			SET recipe_views = recipe_views + 1
			WHERE recipe_id = :id;');

		return $updatePrepare->execute(array(
			':id' => $_GET['id']));
	}

	/**
	 * Update have seen table
	 * @return bool
	 */
	public function update_have_seen() {
		$queryPrepare = parent::$db->prepare(
			"SELECT recipe_id
			FROM haveseen
			WHERE recipe_id = :id AND user_id = :userId;");

		if (!$queryPrepare->execute(array(
			':id' => $_GET['id'],
			':userId' => $_SESSION['login']['id']))) 
			return false;
		else {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

			if (empty($queryRecover)) {
				$insertPrepare = parent::$db->prepare(
					"INSERT INTO haveseen
					VALUES (:id, :userId);");
				
				return $insertPrepare->execute(array(
					':id' => $_GET['id'],
					'userId' => $_SESSION['login']['id']));
			}
			return true;
		}
	}

	/**
	 * Like a recipe
	 * @return bool
	 */
	public function like_recipe() {
		$insertPrepare = parent::$db->prepare(
			'INSERT INTO love
			(user_id, recipe_id)
			VALUES 
			(:userid, :id);');
		
		return $insertPrepare->execute(array(
			':userid' => $_SESSION['login']['id'],
			':id' => $_GET['id']));
	}

	/**
	 * Dislike a recipe
	 * @return bool
	 */
	public function dislike_recipe() {
		$deletePrepare = parent::$db->prepare(
			'DELETE FROM love
			WHERE user_id = :userid AND recipe_id = :id;');
		return $deletePrepare->execute(array(
			':userid' => $_SESSION['login']['id'],
			':id' => $_GET['id']));
	}

	/**
	 * Rate a recipe
	 * @return boolean
	 */
	public function rate_recipe() {
		$queryPrepare = parent::$db->prepare(
			'SELECT rate_value
			FROM rate r 
			WHERE user_id = :userId AND recipe_id = :id;');

		if ($queryPrepare->execute(array(
			':userId' => $_SESSION['login']['id'],
			':id' => $_GET['id']))) {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

			if (empty($queryRecover)) {
				// Insert rating in the db
				$insertPrepare = parent::$db->prepare(
					'INSERT INTO rate
					VALUES (:userid, :id, :rating);');
				
				if ($insertPrepare->execute(array(
					':userid' => $_SESSION['login']['id'],
					':id' => $_GET['id'],
					':rating' => $_GET['rating'])))
					return true;
			} else {
				// Update rating in the db
				$updatePrepare = parent::$db->prepare(
					'UPDATE rate
					SET rate_value = :rating
					WHERE user_id = :userId AND recipe_id = :id;');

				
				if ($updatePrepare->execute(array(
					':rating' => $_GET['rating'],
					':userId' => $_SESSION['login']['id'],
					':id' => $_GET['id'])))
					return true;
			}
		}

		return false;
	}

	/**
	 * Update recipe rating (average of all ratings)
	 * @return boolean|int 
	 */
	public function update_recipe_rating() {
		$queryPrepare = parent::$db->prepare(
			'SELECT avg(rate_value)
			FROM rate r 
			WHERE recipe_id = :id
			GROUP BY recipe_id;');

		if ($queryPrepare->execute(array(
			':id' => $_GET['id']))) {
			$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);

			if (!empty($queryRecover)) {
				$updatePrepare = parent::$db->prepare(
					'UPDATE recipe
					SET recipe_rating = :rating
					WHERE recipe_id = :id;');

				if ($updatePrepare->execute(array(
					':rating' => $queryRecover[0],
					':id' => $_GET['id'])))
					return true;
			}
		}

		return -1;
	}

	/**
	 * Comment a reicpe
	 * @return int 
	 */
	public function comment_recipe() {
		// Get comment data
		$content = isset($_POST['message']) ? $_POST['message'] : '';
		
		if (empty($content)) 
			return 1;
		else {
			// Insert comment in the db
			$insertPrepare = parent::$db->prepare(
				'INSERT INTO comment
				(comment_content, user_id, recipe_id)
				VALUES 
				(:content, :userid, :id);');
			
			if (!$insertPrepare->execute(array(
				':content' => $content,
				':userid' => $_SESSION['login']['id'],
				':id' => $_GET['id'])))
				return -1;
			else
				return 0;
		}
	}

	/**
	 * Get encoded json datas
	 * @param string $datas
	 */
	private function encode_datas($datas) {
		return JsonDatasHandler::encode_datas($datas);
	}

	/**
	 * Get decoded json datas of a file
	 * @param string $fileName
	 * @return string 
	 */
	private function get_decoded_json_datas($fileName) {
		return JsonDatasHandler::get_decoded_json_datas($fileName);
	}

	/**
	 * Get decoded json countries datas
	 * @return array
	 */
	public function get_decoded_countries() {
		return $this->get_decoded_json_datas('recipe')['recipe'][0]['countries'];
	}

	/**
	 * Get decoded json units datas
	 * @return array
	 */
	public function get_decoded_units() {
		return $this->get_decoded_json_datas('recipe')['recipe'][0]['ingredients'][0]['units'];
	}

	/**
	 * Get encoded json units datas
	 */
	public function get_encoded_units() {
		return $this->encode_datas($this->get_decoded_json_datas('recipe')['recipe'][0]['ingredients'][0]['units']);
	}

	/**
	 * Get a recipe category by category entitled and name
	 * @param string $entitled
	 * @param string $tableName
	 * @return bool
	 */
	private function get_category($entitled, $tableName) {
		$queryPrepare = parent::$db->prepare(
			"SELECT $tableName" . "_entitled
			FROM $tableName
			WHERE $tableName" . "_entitled = :entitled;");
		
		if (!$queryPrepare->execute(array(
			':entitled' => $entitled)))
			return -1;
		else
			return $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
	}

	/**
	 * Check if category already exists and insert it
	 * @param string $entitled
	 * @param string $tableName
	 * @return bool
	 */
	private function check_new_category($entitled, $tableName) {
		$categoryEntitled = $this->get_category($entitled, $tableName);

		if ($categoryEntitled == -1)
			return false;
		else if (empty($categoryEntitled)) {
			$insertPrepare = parent::$db->prepare(
				"INSERT INTO $tableName
				VALUES (:entitled);");
			
			if (!$insertPrepare->execute(array(
				':entitled' => $entitled)))
				return false;
		}

		return true;
	}

	/**
	 * Inserts categories
	 * @param array $categories
	 * @param int $recipeId
	 * @param string $tableName
	 * @param string $associationName
	 * @return bool
	 */
	private function insert_categories($categories, $recipeId, $tableName, $associationName) {
		foreach ($categories as $category) {
			$entitled = trim($category);
			
			if (empty($entitled) || !$this->check_new_category($entitled, $tableName))
				return false;
			else {
				$insertPrepare = parent::$db->prepare(
					"INSERT INTO $associationName
					VALUES (:id, :entitled);");

				if (!$insertPrepare->execute(array(
					':id' => $recipeId,
					':entitled' => $entitled)))
					return false;
			}
		}
		return true;
	}

	/**
	 * Get a ingredient id by it name
	 * @param string $name
	 * @return array|int
	 */
	private function get_ingredient($name) {
		$queryPrepare = parent::$db->prepare(
			'SELECT ingredient_id
			FROM ingredient
			where ingredient_name = :name;');

		if (!$queryPrepare->execute(array(
			':name' => $name)))
			return -1;
		else 
			return $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
	}

	/**
	 * Insert an new ingredient by it name
	 * @return array|int
	 */
	private function insert_new_ingredient($name) {
		$insertPrepare = parent::$db->prepare(
			'INSERT INTO ingredient
			(ingredient_name) VALUES (:name);');
		
		return $insertPrepare->execute(array(
			':name' => $name));
	}

	/**
	 * Get and insert an ingredient by it name
	 * @param string $name
	 * @return array|int
	 */
	private function get_ingredient_id($name) {
		$ingredientId = $this->get_ingredient($name);
		
		if ($ingredientId == -1)
			return -1;
		else if (empty($ingredientId)) {
			if ($this->insert_new_ingredient($name)) {
				$ingredientId = $this->get_ingredient($name);
				
				if ($ingredientId == -1 || empty($ingredientId))
					return -1;
			}
		}

		return $ingredientId;
	}

	/**
	 * Insert ingredients
	 * @param array $ingredients
	 * @param int $recipeId
	 * @return boolean
	 */
	private function insert_ingredients($ingredients, $recipeId) {
		foreach ($ingredients as $ingredient) {
			$name = trim($ingredient['name']);
			$quantity = trim($ingredient['quantity']);
			$unit = isset($ingredient['unit']) ? trim($ingredient['unit']) : '';
			
			if (empty($name))
				return false;
			else {
				$ingredientId = $this->get_ingredient_id($name);
				
				if ($ingredientId == -1)
					return false;
				else {
					$insertPrepare = parent::$db->prepare(
						'INSERT INTO madeupof
						VALUES (:id, :ingredientId, :quantity, :unit);');

					if (!$insertPrepare->execute(array(
						':id' => $recipeId,
						':ingredientId' => $ingredientId[0],
						':quantity' => $quantity,
						':unit' => $unit)))
						return false;
				}
			}
		}
		
		return true;
	}

	/**
	 * Init difficulty level value
	 * @param string $difficultyLevel
	 * @return int 
	 */
	private function init_difficulty_level($difficultyLevel) {
		if (strcmp($difficultyLevel, 'easy') == 0)
			$value =  1;
		else if (strcmp($difficultyLevel, 'medium') == 0)
			$value =  2;
		else if (strcmp($difficultyLevel, 'hard') == 0)
			$value =  3;
		else
			$value =  '';

		return $value;
	}

	/**
	 * Post a recipe
	 * @return int 
	 */
	public function post_recipe() {
		// Get image
		if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
			// Check image dimensions
			if (!ImageLoader::checkRecipeImageDimensions($_FILES['image']['tmp_name']))
				return 1;
			else 
				$image = ImageLoader::load_image($_FILES['image']['tmp_name']);
		} else
			$image = '';
	
		// Get recipe datas
		$title = isset($_POST['title']) ? strtolower($_POST['title']) : '';
		$sliceNbr = isset($_POST['slicenbr']) ? $_POST['slicenbr'] : '';
		$cost = isset($_POST['cost']) ? $_POST['cost'] : '';
		$time = isset($_POST['time']) ? $_POST['time'] : '';
		$origin = isset($_POST['origin']) ? strtolower($_POST['origin']) : '';
		$difficultyLevel = isset($_POST['difficultylevel']) ? $this->init_difficulty_level(strtolower($_POST['difficultylevel'])) : '';
		$calorie = isset($_POST['calorie']) && !empty($_POST['calorie']) ? strval($_POST['calorie']) : '';
		$protide = isset($_POST['protide']) && !empty($_POST['protide']) ? strval($_POST['protide']) : '';
		$lipid = isset($_POST['lipid']) && !empty($_POST['lipid']) ? strval($_POST['lipid']) : '';
		$carbohydrate = isset($_POST['carbohydrate']) && !empty($_POST['carbohydrate']) ? strval($_POST['carbohydrate']) : '';
		$fibre = isset($_POST['fibre']) && !empty($_POST['fibre']) ? strval($_POST['fibre']) : '';
		$preparation = isset($_POST['preparation']) ? $_POST['preparation'] : '';
		$ingredients = isset($_POST['ingredient']) && !empty($_POST['ingredient'][0]['name']) ? $_POST['ingredient'] : '';
		$ustensiles = isset($_POST['ustensile']) && !empty($_POST['ustensile'][0]) ? $_POST['ustensile'] : '';
		$themes = isset($_POST['theme']) && !empty($_POST['theme'][0]) ? $_POST['theme'] : '';
		$diets = isset($_POST['diet']) && !empty($_POST['diet'][0]) ? $_POST['diet'] : '';
		$dishtypes = isset($_POST['dishtype']) && !empty($_POST['dishtype'][0]) ? $_POST['dishtype'] : '';
		
		if (empty($image) || empty($title) || empty($sliceNbr) || empty($cost) || empty($time) || empty($difficultyLevel) || empty($preparation) || empty($ingredients) || empty($dishtypes)) 
			return 2;
		else {
			// Insert recipe in the db
			$insertPrepare = parent::$db->prepare(
				'INSERT INTO recipe 
				(recipe_title, recipe_slicenbr, 
				recipe_cost, recipe_time, 
				recipe_origin, recipe_difficultylevel, 
				recipe_calorie, 
				recipe_protide, recipe_lipid, 
				recipe_carbohydrate, recipe_fibre, 
				recipe_preparation, recipe_image, user_id)
				VALUES 
				(:title, :slicenbr,
				:cost, :time,
				:origin, :difficultylvl,
				:calorie,
				:protide, :lipid,
				:carbohydrate, :fibre,
				:preparation, :image, :user_id);');
			
			if (!$insertPrepare->execute(array(
				':title' => $title,
				':slicenbr' => $sliceNbr,
				':cost' => $cost,
				':time' => $time,
				':origin' => $origin,
				':difficultylvl' => $difficultyLevel,
				':calorie' => $calorie,
				':protide' => $protide,
				':lipid' => $lipid,
				':carbohydrate' => $carbohydrate,
				':fibre' => $fibre,
				':preparation' => $preparation,
				':image' => $image,
				':user_id' => $_SESSION['login']['id'])))
					return -1;
			else {
				$queryPrepare = parent::$db->prepare(
					'SELECT max(recipe_id) as max
					FROM recipe
					WHERE user_id = :id
					GROUP BY user_id;');
				if (!$queryPrepare->execute(array(
					':id' => $_SESSION['login']['id'])))
					return 3;
				else {
					$queryRecover =	$queryPrepare->fetchAll(PDO::FETCH_COLUMN);

					if (empty($queryRecover))
						return 2;
					else if (!$this->insert_ingredients($ingredients, $queryRecover[0]) 
						|| (!empty($ustensiles) && !$this->insert_categories($ustensiles, $queryRecover[0], 'ustensile', 'need'))
						|| !$this->insert_categories($dishtypes, $queryRecover[0], 'dishtype', 'define')
						|| (!empty($themes) && !$this->insert_categories($themes, $queryRecover[0], 'theme', 'belongto'))
						|| (!empty($diets) && !$this->insert_categories($diets, $queryRecover[0], 'diet', 'beon')))
						return -1;
					else
						return $queryRecover;
				}
			}
		}
	}

	/**
	 * Autocomplete a input element
	 */
	public function autocomplete() {
		$table = isset($_POST['table']) ? $_POST['table'] : '';
		$search = isset($_POST['search']) ? $_POST['search'] : '';

		if (!empty($table) && !empty($search)) {
			if (strcmp($table, 'ingredient') == 0)
				$column = 'ingredient_name'; 
			else if (strcmp($table, 'recipe') == 0)
				$column = 'recipe_title'; 
			else 
				$column = $table . '_entitled';

			$queryPrepare = parent::$db->prepare(
				"SELECT $column
				FROM $table
				WHERE $column LIKE '%$search%';");

			if ($queryPrepare->execute()) {
				$response = array();
				$queryRecover = $queryPrepare->fetchAll(PDO::FETCH_COLUMN);
					
				for ($i = 0; $i < count($queryRecover); $i++)
					$response[] = array('value' => $queryRecover[$i]);

				echo $this->encode_datas($response);
			}
		}
		exit;
	}

	/**
	 * Check if category exists for a recipe
	 * @param string $tableName
	 * @return boolean
	 */
	private function check_category($tableName) {
		$queryPrepare = parent::$db->prepare(
			"SELECT *
			FROM $tableName
			WHERE recipe_id = :id"); 

		if ($queryPrepare->execute(array(
			':id' => $_GET['id']))) {
			if (!empty($queryPrepare->fetchAll()))
				return true;
		}
		return false;
	}
}
?>