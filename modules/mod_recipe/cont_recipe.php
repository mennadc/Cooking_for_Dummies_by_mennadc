<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/cont_generic.php';
include_once 'view_recipe.php';
include_once 'model_recipe.php';
include_once 'composants/comp_aside/cont_aside.php';

class ContRecipe extends ContGeneric {

	public function __construct() {
		$this->model = new ModelRecipe();
		$this->view = new ViewRecipe();
		$this->contAside= new ContAside();

	}

	/**
	 * Display recipes page
	 * @param string $title
	 * @param array|int $recipes
	 */
	private function recipes_page($title, $recipes) {
		$this->view->recipes_page($title, $recipes);
		$this->contAside->display_view();
	}

	/**
	 * Display favorite recipe page
	 */
	public function favorite_recipe() {
		$favoriteRecipes = $this->model->favorite_recipe();

		if ($favoriteRecipes == -1)
			$this->notFound_page();
		else
			$this->recipes_page('Favorite recipes list', $favoriteRecipes);
	}

	/**
	 * Display posted recipe page
	 */
	public function posted_recipes() {
		$postedRecipes = $this->model->get_posted_recipes();
		
		if ($postedRecipes == -1)
			$this->notFound_page();
		else
			$this->recipes_page('Posted recipes', $postedRecipes);
	}

	/**
	 * Display recipe of subscriptions of a user 
	 */
	public function recipes_subscriptions() {
		if (!isset($_SESSION['login']) || empty($_SESSION['login']))
			$this->controller->notFound_page();	
		else {
			$recipes = $this->model->recipes_subscriptions();

			if ($recipes == -1) 
				$this->notFound_page();
			else 
				$this->recipes_page('Subscriptions recipes', $recipes);
		}
	}

	/**
	 * Search a recipe with simple search page
	 */
	public function simple_search_recipe() {
		if (!isset($_POST['title']) || empty($_POST['title']))
			header('Location: index.php?module=home');
		else {
			$recipes = $this->model->search_recipe($_POST['title']);

			if($recipes == -1)
				$this->notFound_page();
			else 
				$this->recipes_page('Search results', $recipes);
		}
	}

	/**
	 * Search recipe by name
	 */
	public function search_recipe() {
		if (!isset($_GET['title']) || empty($_GET['title']))
			header('Location: index.php?module=home');
		else {
			$recipes = $this->model->search_recipe($_GET['title']);

			if($recipes == -1)
				$this->notFound_page();
			else 
				$this->recipes_page('Search results', $recipes);
		}
	}

	/**
	 * Search recipe by an ingredient
	 */
	public function search_ingredient() {
		$recipesSearch = $this->model->search_ingredient();

		if ($recipesSearch == -1)
			$this->notFound_page();
		else
			$this->recipes_page('Search results', $recipesSearch);
	}

	/**
	 * Search recipe by a category
	 */
	public function search_category() {
		$recipesSearch = $this->model->search_category();

		if ($recipesSearch == -1)
			$this->notFound_page();
		else
			$this->recipes_page('Search results', $recipesSearch);
	}

	/**
	 * Display advanced search page
	 * @param string $errorMsg
	 */
	public function advanced_search_page($errorMsg = '') {
		$this->view->advanced_search_page($errorMsg, $this->get_decoded_countries());
	}

	/**
	 * Search a recipe with advanced search page
	 */
	public function advanced_search() {
		$recipesSearch = $this->model->advanced_search();

		if (is_array($recipesSearch))
			$this->recipes_page('Search results', $recipesSearch);
		else {
			switch ($recipesSearch) {
				case -1:
					$errorMsg = "An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.";
					break;
				case 1:
					$errorMsg = "Invalid informations entered !";
					break;
				case 2:
					$errorMsg = 'Choose atleast one field to use the advanced search !';
					
			}
			$this->advanced_search_page($errorMsg);
		}
	}

	/**
	 * Return user avatar by user id
	 * @return string
	 */
	private function get_avatar($id) {
		return $this->model->get_avatar($id);
	}

	/**
	 * Display recipe page
	 * @param string $msg
	 */
	public function recipe_page($msg = '') {
		$recipe = $this->model->get_recipe_infos();
		$comments = $this->model->get_recipe_comments();
		$ingredients = $this->model->get_recipe_ingredients();
		$categories = $this->model->get_categories();

		if ($recipe == -1 || $comments == -1 || $ingredients == -1 || $categories == -1) 
			$this->notFound_page();
		else  {
			if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
				$favoritesBtn = '';
				$deleteRecipeBtn = '';
				$ratingUser = 0;
			} else {
				$ratingUser = $this->model->get_rating_user();

				if ($_SESSION['login']['id'] == $recipe['user_id'])
					$favoritesBtn = '';
				else {
					if ($this->model->is_liked())
						$favoritesBtn = "<a class='btn buttons__undo float-right' href='index.php?module=recipe&action=dislike_recipe&id=" . $_GET['id'] . "'><i class='fas fa-thumbs-down'></i></a>";
					else
						$favoritesBtn = "<a class='btn buttons__validation float-right' href='index.php?module=recipe&action=like_recipe&id=" . $_GET['id'] . "'><i class='fas fa-thumbs-up'></i></a>";
				}
	
				if ($_SESSION['login']['role'] == 1)
					$deleteRecipeBtn = "<a class='btn btn-danger float-right mt-md-3' href='index.php?module=admin&action=delete_recipe&id=" . $_GET['id'] . "'>Delete recipe</a>";
				else
					$deleteRecipeBtn = '';	
			}
			if ($ratingUser == -1 || !$this->model->incrementViews() || (isset($_SESSION['login']) && !empty($_SESSION['login']) && !$this->model->update_have_seen()))
				$this->notFound_page();
			else 
				$this->view->recipe_page($msg, $recipe, $comments, $ingredients, $categories, $this->get_avatar($recipe['user_id']), $ratingUser, $deleteRecipeBtn, $favoritesBtn);
		}
	}

	/**
	 * Like a recipe
	 */
	public function like_recipe() {
		$liked = $this->model->like_recipe();

		if (!$liked)
			$this->notFound_page();
		else
			$this->recipe_page();
	}

	/**
	 * DisLike a recipe
	 */
	public function dislike_recipe() {
		$diliked = $this->model->dislike_recipe();

		if (!$diliked)
			$this->notFound_page();
		else
			$this->recipe_page();
	}

	/**
	 * Rate a recipe
	 */
	public function rate_recipe() {
		if (!$this->model->rate_recipe())
			$this->notFound_page();
		else {
			if (!$this->model->update_recipe_rating())
				$this->notFound_page();
			else 
				header('Location: index.php?module=recipe&action=recipe_page&id=' . $_GET['id']);
		}
	}

	/**
	 * Comment a recipe
	 */
	public function comment_recipe() {
		$msgSent = $this->model->comment_recipe();

		switch ($msgSent) {
			case 0:
				$msg = "<p class='text-center text-success'>Your message has been sent successfully !</p>";
				break;
			case 1:
				$msg = "<p class='text-center text-danger'>Invalid informations entered !</p>";
				break;
			case 2:
				$msg = "<p class='text-center text-danger'>An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.</p>";
		}
		$this->recipe_page($msg);
	}

	/**
	 * Get decoded json countries datas
	 * @return array
	 */
	private function get_decoded_countries() {
		return $this->model->get_decoded_countries();
	}

	/**
	 * Get decoded json units datas
	 * @return array
	 */
	private function get_decoded_units() {
		return $this->model->get_decoded_units();
	}

	/**
	 * Get encoded json units datas
	 */
	public function get_encoded_units() {
		echo $this->model->get_encoded_units();
		exit;
	}

	/**
	 * Display recipe writing page
	 * @param string $errorMsg
	 */
	private function recipe_writing_page($errorMsg = '') {
		if (!isset($_SESSION['login']) || empty($_SESSION['login']))
			header('Location: index.php?module=home');
		else
			$this->view->recipe_writing_page($errorMsg, $this->get_decoded_countries(), $this->get_decoded_units());
	}

	/**
	 * Write a recipe
	 */
	public function write_recipe() {
		if (!isset($_SESSION['login']) || empty($_SESSION['login']))
			header('Location: index.php?module=home');	
		else 
			$this->recipe_writing_page();
	}

	/**
	 * Post a recipe
	 */
	public function post_recipe() {
		if (!isset($_SESSION['login']) || empty($_SESSION['login']))
			header('Location: index.php?module=home');	
		else {
			$recipePost = $this->model->post_recipe();

			if (is_array($recipePost) && !empty($recipePost))
				header('Location: index.php?module=recipe&action=recipe_page&id=' . $recipePost[0]);
			else if ($recipePost == 3)
				header('Location: index.php?module=home');
			else {
				switch ($recipePost) {
					case -1:
						$errorMsg = "An error has occurred ! Retry and if the problem persists, <a href='index.php?module=contact&action=contact_page'>contact us</a>.";
						break;
					case 1:
						$errorMsg = "Avatar dimensions not respected ! (between 300x300px and 2000x2000px)";
						break;
					case 2:
						$errorMsg = "Invalid informations entered !";
				}
				$this->recipe_writing_page($errorMsg);
			}
		}
	}

	/**
	 * Autocomplete a input element
	 */
	public function autocomplete() {
		$this->model->autocomplete();
	}
}
?>
