<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewRecipe extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display recipe page
	 * @param string $msg
	 * @param array $recipe
	 * @param array|int $comments
	 * @param array|int $ingredients
	 * @param array|int $categories
	 * @param string $avatar
	 * @param int $ratingUser
	 * @param string $deleteRecipeBtn
	 * @param string $favoritesBtn
	 */
	public function recipe_page($msg, $recipe, $comments, $ingredients, $categories,  $avatar, $ratingUser, $deleteRecipeBtn, $favoritesBtn) {
		if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
			$commentForm = "
				<p class='text-center mb-md-5'>Leave a comment on the site here !</p>
				$msg
				<form class='container form-signin' action='?module=recipe&action=comment_recipe&id=".$_GET['id']."' method='post'>
					<div class='form-label-group mb-md-5'>
						<label for='message'>Your message</label>
						<textarea type='text' name='message' rows='4' class='form-control md-textarea' placeholder='Enter your message' required></textarea>
					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Send'/>
				</form>";
			if ($_SESSION['login']['id'] != $recipe['user_id']) {
				$strRating = "
					<p>Rate this recipe !</p>
					<ul class='recipe__rating d-flex justify-content-center'>";

				for ($rating = 1; $rating <= 5; $rating++) {
					if ($ratingUser != null && $rating - 1 < $ratingUser)
						$strRating .= "<li><a class='recipe__heart--full' href='index.php?module=recipe&action=rate_recipe&id=" . $_GET['id'] . "&rating=$rating'><i class='fas fa-heart'></i></a><li>";
					else
						$strRating .= "<li><a class='recipe__heart--empty text-muted' href='index.php?module=recipe&action=rate_recipe&id=" . $_GET['id'] . "&rating=$rating'><i class='far fa-heart'></a></i><li>";
				}

				$strRating .= "</ul>";
			} else 
				$strRating = '';
		} else {
			$commentForm = "
				<p class='text-center'>You must be logged in to post a comment. Sign in <a href='index.php?module=connection&action=form_signin'>here</a>.</p>";
			$strRating = "
				<p class='text-center'>You must be logged in to rate a recipe. Sign in <a href='index.php?module=connection&action=form_signin'>here</a>.<p>";
		}

		$date = new DateTime($recipe['recipe_date']);
		
		$result = "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'>
				<div class='d-flex justify-content-between mb-md-5 px-5 w-100'>
					<div class='w-25 row'>
						<div class='w-25'>
							<a href='index.php?module=user&action=profile_page&id=" . $recipe['user_id'] . "'>
								<img src='$avatar' alt='avatar' class='d-inline align-middle rounded-circle w-75'>
							</a>
						</div>
						<div class='w-75'>
							<p class='d-inline align-middle'><a class='user-name_link' href='index.php?module=user&action=profile_page&id=" . $recipe['user_id'] . "'>" . $recipe['username'] . "</a></p>
							<p class='recipe__date text-secondary'>". $date->format('Y/m/d') . "</p>
						</div>
					</div>
					<div class='w-50'>		
						<h2 class='h2 mb-md-2 text-center recipe__title font-weight-bold'>". ucfirst($recipe['recipe_title']) ."</h2>
						<ul class='d-flex justify-content-center recipe__rating mt-md-0'>";

		for ($rating = 0; $rating < 5; $rating++) {
			if ($recipe['recipe_rating'] != null && $rating < $recipe['recipe_rating'])
				$result .= "<li class='recipe__heart--full'><i class='fas fa-heart'></i><li>";
			else
				$result .= "<li class='recipe__heart--empty text-muted'><i class='far fa-heart'></i><li>";
		}

		$result .= "
						</ul>
					</div>
					<div class='d-flex flex-column w-25'>
						<div>
							$favoritesBtn
						</div>
						<div>
							$deleteRecipeBtn
						</div>
					</div>
				</div>
				<div class='mb-md-5 '>
					<ul class='flex-wrap d-flex list-unstyled text-center'>";
					
		if (is_array($categories) && !empty($categories)) {
			foreach ($categories as $category) 
				$result .= "<li class='recipe__categories'>$category</li>";
		}

		if (!empty($recipe['recipe_origin'])) 
			$result .= "<li class='recipe__categories'>" . $recipe['recipe_origin'] . "</li>";
		

		$result .="
					</ul>
				</div>
				<div class='recipe__content mb-md-5 px-5 pb-md-5'>
					<div class='d-flex justify-content-around'>
						<div class='recipe__image w-50'>
							<img class='mx-auto d-block w-75 rounded' src='" . $recipe['recipe_image'] . "' alt='recipe image'>
						</div>
						<div class='recipe__infos w-50'>
							<div class='recipe__infos-row align-items-center mb-md-3 d-flex justify-content-between'>
								<h3>Difficulty :</h3>
								<ul class='d-flex justify-content-center recipe__rating ml-md-5'>";
			
		for ($difficultylevel = 0; $difficultylevel < 3; $difficultylevel++) {
			if ($difficultylevel < $recipe['recipe_difficultylevel'])
				$result .= "<li class='recipe__star--full'><i class='fas fa-star'></i><li>";
			else
				$result .= "<li class='recipe__star--empty text-muted'><i class='far fa-star'></i><li>";
		}

		$result .= "
								</ul>
								<i id='test' class='recipe__infocircle fas fa-info-circle'></i>
								<div class='recipe__nutrition bg-white'>
									<div class='d-flex justify-content-between text-align-baseline'>
										<p class='mr-5'>Calorie : </p>
										<p>" . (!empty($recipe['recipe_calorie']) ? $recipe['recipe_calorie'] . ' kcal/g' : '/') . "</p>
									</div>
									<div class='d-flex justify-content-between text-align-baseline'>
										<p class='mr-5'>Protide : </p>
										<p>" . (!empty($recipe['recipe_protide']) ? $recipe['recipe_protide'] . ' g' : '/') . "</p>
									</div>
									<div class='d-flex justify-content-between text-align-baseline'>
										<p class='mr-5'>Lipid : </p>
										<p>" . (!empty($recipe['recipe_lipid']) ? $recipe['recipe_lipid'] . ' g' : '/') . "</p>
									</div>
									<div class='d-flex justify-content-between text-align-baseline'>
										<p class='mr-5'>Carbohydrate : </p>
										<p>" . (!empty($recipe['recipe_carbohydrate']) ? $recipe['recipe_carbohydrate'] . ' g' : '/') . "</p>
									</div>
									<div class='d-flex justify-content-between text-align-baseline'>
										<p class='mr-5'>Fibre : </p>
										<p>" . (!empty($recipe['recipe_fibre']) ? $recipe['recipe_fibre'] . ' g' : '/') . "</p>
									</div>
								</div>
							</div>
							<div class='mb-md-3 d-flex justify-content-between'>
								<div class='recipe__slice'>
									<i class='d-inline align-baseline fas fa-user'></i>
									<p class='ml-md-3 d-inline align-baseline'>" . $recipe['recipe_slicenbr'] . "</p>
								</div>
								<div class='recipe__clock'>							
									<i class='d-inline fas fa-clock'></i>
									<p class='ml-md-3 d-inline'>" . $recipe['recipe_time'] . "</p>
								</div>
								<div class='recipe__euro'>
									<i class='d-inline fas fa-euro-sign'></i>
									<p class='ml-md-3 d-inline'>" . $recipe['recipe_cost'] . "</p>
								</div>	
							</div>
							<div class='mb-md-3'>
								<h3 class='mb-md-3'>Ingredients :</h3>
								<div class='ingredient__frame'>
									<ul>";

		if (is_array($ingredients) && !empty($ingredients)) {
			foreach ($ingredients as $ingredient) {
				$result .= "
						<li class=''>" . $ingredient['quantity'] . " " . $ingredient['unit'] . " " . $ingredient['name'] . "</li>";
			}
		}

		$result .= "
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class='container mt-md-5'>
						<h3>Preparation :</h3>
						<p class='px-5 recipe__preparation'>" . $recipe['recipe_preparation'] . "</p>
					</div>
				</div>
				<div class='d-flex justify-content-end align-items-center mb-md-3'>
					<p class='mr-md-2 mb-md-0'>" . $recipe['recipe_views'] . "</p>
					<i class='mb-md-0 recipe__views fas fa-eye'></i>
				</div>
				<div class='d-flex justify-content-between align-items-center'>
					<div>
						<a href='index.php?module=home&action=homepage' class='btn btn-primary recipe__btn'>Back to home page</a>
					</div>
					<div class='share'>
						<a class='share__network twitter-share-button' data-size='large' href='https://twitter.com/intent/tweet'>Tweet</a>
						<a class='share__network' data-pin-do='buttonPin' href='https://www.pinterest.com/pin/create/button/?url=http://www.foodiecrush.com/2014/03/filet-mignon-with-porcini-mushroom-compound-butter/&media=https://i.pinimg.com/736x/17/34/8e/17348e163a3212c06e61c41c4b22b87a.jpg&description=So%20delicious!' data-pin-custom='true'><img src='https://addons.opera.com/media/extensions/55/19155/1.1-rev1/icons/icon_64x64.png' width='25' height='25'></a>
						<div class='share__network fb-share-button' data-href='https://developers.facebook.com/docs/plugins/' data-layout='button' data-size='large'>
							<a target='_blank' href='https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse' class='fb-xfbml-parse-ignore'>Partager</a>
						</div>
					</div>
				</div>
				<div class='text-center mt-md-5 mb-md-5'>
					$strRating
				</div>
				<hr> 
				<div class='mt-md-5 mb-md-5'>
					$commentForm
				</div>
				<hr>
				<div class='mt-md-5'>";

		if (!is_array($comments) || empty($comments)) 
			$result .= "
					<p class='text-center'>No comments found.</p>";
		else {
			$result .= "
					<table class='table table-striped'>";

			foreach ($comments as $comment) {
				$result .= "<tr><td class='pt-3'>";

				if (isset($_SESSION['login']) && !empty($_SESSION['login']) && $_SESSION['login']['role'] == 1)
					$deleteCommentButton = "<a class='btn btn-danger mr-md-3 font-weight-bold' href='index.php?module=admin&action=delete_comment&id=" . $comment['id'] . "&recipe_id=" . $_GET['id'] . "'>Delete comment</a>";
				else			
					$deleteCommentButton = '';

				

				$comment_date = new DateTime($comment['date']);
				$result .= "
						<div class='d-flex justify-content-between mb-md-3'>
							<p>" 
							. $comment['content'] . 
							"</p>
							$deleteCommentButton
						</div>"
						. "<p class='comment_author text-secondary text-right'>from <a href='index.php?module=user&action=profile_page&id=" . $comment['user_id'] . "'>"
						. $comment['username'] . "</a> - ". $comment_date->format('Y/m/d') . "</p>";

				$result .= "</tr></td>";
			}
			$result .= "
					</table>";
		}


		$result .= "
					</div>
				</div>
			</div>
			<script async defer crossorigin='anonymous' src='https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v9.0' nonce='rJuaNf6s'></script>
			<script type='text/javascript' async src='https://platform.twitter.com/widgets.js'></script>";

		echo $result;
	}

	/**
	 * Display recipe writing page
	 * @param string $errorMsg
	 * @param array|int $countries
	 * @param array|int $units
	 */
	public function recipe_writing_page($errorMsg, $countries, $units) {
		if (!empty($errorMsg))
			$errorMsg = "<p class='text-center text-danger'>$errorMsg</p>";

		$result = "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2'>New recipe</h2>
					<p>Post a new recipe here !</p>
				</div>
				$errorMsg
				<form id='recipe_form' class='container form-signin' action='index.php?module=recipe&action=post_recipe' method='post' enctype='multipart/form-data'>
					<div class='mb-md-5'>
						<div class='d-flex mb-md-3'>			
							<div class='flex-grow-1 form-label-group'>
								<label for='name'>Title <span class='text-danger'>*</span></label>
								<input type='text' name='title' class='form-control' placeholder='Enter title' required>	
							</div>		
							<div class='ml-md-4 form-label-group align-self-center'>
								<label for='image'>Image <span class='text-danger'>*</span></label>
								<input class='form-control-file' type='file' name='image' accept='.png, .jpg, .jpeg' required/>
							</div>
						</div>
						<div class='form-label-group w-50 mb-md-3'>
							<label for='slicenbr'>Slice Number <span class='text-danger'>*</span></label>
							<input type='number' name='slicenbr' min='1' class='form-control' placeholder='Enter slice number' required></input>
						</div>
						<div class='form-label-group w-50 mb-md-3'>
							<label for='cost'>Cost ($) <span class='text-danger'>*</span></label>
							<input type='number' name='cost' min='0' class='form-control'  placeholder='Enter cost' required></input>
						</div>
						<div class='form-label-group w-25 mb-md-3'>
							<label for='time'>Preparation Time (HH:MM) <span class='text-danger'>*</span></label>
							<input type='time' name='time' min='00:05' class='form-control' required></input>
						</div>
						<div class='form-label-group w-25 mb-md-3'>
							<label for='difficultylevel'>Difficulty level <span class='text-danger'>*</span></label>
							<select class='form-control' id='difficultylevel' name='difficultylevel'>
								<option>Easy</option>
								<option>Medium</option>
								<option>Hard</option>
							</select>
						</div>
						<div class='form-label-group w-50'>
							<label for='origin'>Origin</label>
							<select name='origin' class='form-control'>
								<option></option>";

		if (is_array($countries) && !empty($countries)) {
			foreach ($countries as $country)
				foreach ($country as $c)
					$result .= "<option>" . $c . "</option>";
		}

		$result .= "
							</select>
						</div>
					</div>
					<hr>
					<div class='mb-md-5 mt-md-4'>
						<h3 class='h3 text-center'>Nutritional informations</h3>
						<div class='row mt-md-4'>
							<div class='col'>
								<div class='form-label-group mb-md-3'>
									<label for='calorie'>Calorie (kcal/g)</label>
									<input type='number' name='calorie' min='0' class='form-control' placeholder='Enter calorie'></input>
								</div>
								<div class='form-label-group'>
									<label for='protide'>Protide (g)</label>
									<input type='number' name='protide' min='0' class='form-control' placeholder='Enter protide'></input>
								</div>
								<div class='form-label-group mb-md-3'>
									<label for='lipid'>Lipid (g)</label>
									<input type='number' name='lipid' min='0' class='form-control' placeholder='Enter lipid'></input>
								</div>
							</div>
							<div class='col'>
								<div class='form-label-group mb-md-3'>
									<label for='carbohydrate'>Carbohydrate (g)</label>
									<input type='number' name='carbohydrate' min='0' class='form-control' placeholder='Enter carbohydrate'></input>
								</div>
								<div class='form-label-group'>
									<label for='fibre'>Fibre (g)</label>
									<input type='number' name='fibre' min='0' class='form-control' placeholder='Enter fibre'></input>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class='form-label-group text-center mb-md-5 mt-md-4'>
						<label class='h3' for='ingredient'>Ingredients</label>
						<table class='table table-bordered table-striped mt-md-4' id='dynamic_ingredient'>
							<tr> 
								<td class='form-label-group'>
									<label for='ingredient[0][name]'>Name <span class='text-danger'>*</span></label>
									<input type='text' id='ingredient_0' class='form-control autocomplete' name='ingredient[0][name]' placeholder='Enter ingredient name' required>
								</td>
								<td class='form-label-group'>
									<label for='ingredient[0][quantity]'>Quantity <span class='text-danger'>*</span></label>
									<input type='number' name='ingredient[0][quantity]' min='0' placeholder='Enter ingredient quantity' class='form-control' required>
								</td>
								<td class='form-label-group'>
									<label for='ingredient[0][unit]'>Unit</label>
									<select name='ingredient[0][unit]' class='form-control'>
										<option> </option>";	
						
		if (is_array($units) && !empty($units)) {
			foreach ($units as $unit)
					foreach ($unit as $u)
						$result .= "<option>" . $u . "</option>";
		}

		$result .= "	
									</select>
								</td>
								<td>
									<div class='d-flex justify-content-center'>
										<button type='button' id='add_ingredient_writing_recipe' class='btn btn-success font-weight-bold mt-md-3'>Add more</button>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<hr>
					<div class='mt-md-4'>
						<h3 class='text-center h3'>Categories</h3>
						<div class='d-flex justify-content-around mb-md-4 mt-md-4'>
							<div class='form-label-group text-center'>
								<label for='ustensile'>Ustensiles</label>
								<table class='table table-bordered table-striped mt-md-4' id='dynamic_ustensile'>
									<tr> 
										<td>
											<input id='ustensile_0' class='form-control autocomplete' type='text' name='ustensile[0]' placeholder='Enter ustensile'>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_ustensile' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div class='form-label-group'>
								<div class='d-flex justify-content-center align-items-baseline mb-md-4'>
									<label for='theme'>Themes</label>
								</div>
								<table class='table table-bordered table-striped' id='dynamic_theme'>
									<tr> 
										<td>
											<input id='theme_0' class='form-control autocomplete' type='text' name='theme[0]' placeholder='Enter theme'>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_theme' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class='d-flex justify-content-around mb-md-5'>
							<div class='form-label-group'>
								<div class='d-flex justify-content-center align-items-baseline mb-md-4'>
									<label for='diet'>Diets</label>
								</div>
								<table class='table table-bordered table-striped' id='dynamic_diet'>
									<tr> 
										<td>
											<input id='diet_0' class='form-control autocomplete' type='text' name='diet[0]' placeholder='Enter diet'>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_diet' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div class='form-label-group'>
								<div class='d-flex justify-content-center align-items-baseline mb-md-4'>
									<label for='dishtype'>Dish types <span class='text-danger'>*</span></label>
								</div>
								<table class='table table-bordered table-striped' id='dynamic_dishtype'>
									<tr> 
										<td>
											<input id='dishtype_0' class='form-control autocomplete' type='text' name='dishtype[0]' placeholder='Enter dish type' required>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_dishtype' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<hr>
					<div class='form-label-group mb-md-5 mt-md-4'>
						<label for='preparation'>The recipe preparation <span class='text-danger'>*</span></label>
						<textarea type='text' name='preparation' rows='4' class='form-control md-textarea' placeholder='Enter your preparation' required></textarea>
					</div>
					<input class='btn btn-lg btn-primary btn-block' id='submit' type='submit' value='Post recipe'/>
				</form>
			</div>";

		echo $result;
	}

	/**
	 * Display recipes page
	 * @param string $title
	 * @param array|int $recipes
	 */
	public function recipes_page($title, $recipes) {
		$result = "
			<div class='d-flex justify-content-around my-md-5'>
				<div class='p-5 bg-white w-50'>
					<div class='text-center mb-md-5'>	
						<h2 class='h2'>$title</h2>
						<hr>
					</div>";
		
		if (!is_array($recipes) || empty($recipes))
			$result .= "
					<p class='text-center'>No recipes found.</p>";
		else {
			foreach($recipes as $recipe) {
				$date = new DateTime($recipe['date']);
				$result .= "
					<div class='recipe-user_cards mt-5 container-sm'>
						<div class='d-flex justify-content-around'>
							<div class='recipe__image w-25'>
								<a href='?module=recipe&action=recipe_page&id=" . $recipe['id'] . "'>
									<img class='rounded-circle mx-auto d-block w-100' src='" . $recipe['image'] . "' alt='recipe image'>
								</a>
							</div>
							<div class='recipe__body d-flex flex-column w-50'>
								<h3 class='h3 text-center'><a  href='?module=recipe&action=recipe_page&id=" . $recipe['id'] . "'>" . ucfirst($recipe['title']) . "</a></h3>
								<ul class='recipe__rating d-flex justify-content-center'>";

				for($counter = 1; $counter <= 5; $counter++) {
					if($counter <= $recipe['rating'])
						$result .= "<li class='recipe__heart--full'><i class='fas fa-heart'></i><li>";
					else
						$result .= "<li class='recipe__heart--empty'><i class='far fa-heart'></i><li>";
				}

				$result .= "
								</ul>
								<a class='recipe__username text-center' href='index.php?module=user&action=profile_page&id=" . $recipe['user_id'] . "' >". $recipe['username'] ."</a>
								<p class='recipe__date text-center'>". $date->format('Y/m/d') . "</p>	
							</div>
						</div>
					</div>";
			}
	    }
	  	$result .= "
				</div>";
		
	  	echo $result;
   }

   /**
	 * Display advanced search page
	 * @param string $errorMsg
	 * @param array|int $countries
	 */
   public function advanced_search_page($errorMsg, $countries) {
		if (!empty($errorMsg))
			$errorMsg = "<p class='text-center text-danger'>$errorMsg</p>";

		$result = "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2'>Advanced search </h2>
					<p>Search a recipe on the title, the ingredients, categories...</p>
				</div>
				$errorMsg
				<form id='recipe_form' class='container form-signin' action='index.php?module=recipe&action=advanced_search' method='post' enctype='multipart/form-data'>
					<div class='mb-md-5'>
						<div class='mb-md-3 form-label-group'>
							<label for='name'>Title</label>
							<input type='text' name='title' id='recipe_title' class='autocomplete form-control' placeholder='Enter title'>	
						</div>
						<div class='form-label-group w-50 mb-md-3'>
							<label for='slicenbr'>Slice Number</label>
							<input type='number' name='slicenbr' min='1' class='form-control' placeholder='Enter slice number'></input>
						</div>
						<div class='form-label-group w-50 mb-md-3'>
							<label for='cost'>Cost ($)</label>
							<input type='number' name='cost' min='0' class='form-control'  placeholder='Enter cost'></input>
						</div>
						<div class='form-label-group w-25 mb-md-3'>
							<label for='time'>Preparation Time (HH:MM)</label>
							<input type='time' name='time' min='00:05' class='form-control'></input>
						</div>
						<div class='form-label-group w-25 mb-md-3'>
							<label for='difficultylevel'>Difficulty level</label>
							<select class='form-control' id='difficultylevel' name='difficultylevel'>
								<option></option>
								<option>Easy</option>
								<option>Medium</option>
								<option>Hard</option>
							</select>
						</div>
						<div class='form-label-group w-50'>
							<label for='origin'>Origin</label>
							<select name='origin' class='form-control'>
								<option> </option>";

		if (is_array($countries) && !empty($countries)) {
			foreach ($countries as $country)
				foreach ($country as $c)
					$result .= "<option>" . $c . "</option>";
		}

		$result .= "
							</select>
						</div>
					</div>
					<hr>
					<div class='mb-md-5 mt-md-4'>
						<h3 class='h3 text-center'>Nutritional informations</h3>
						<div class='row mt-md-4'>
							<div class='col'>
								<div class='form-label-group mb-md-3'>
									<label for='calorie'>Calorie (kcal/g)</label>
									<input type='number' name='calorie' min='0' class='form-control'></input>
								</div>
								<div class='form-label-group'>
									<label for='protide'>Protide (g)</label>
									<input type='number' name='protide' min='0' class='form-control'></input>
								</div>
								<div class='form-label-group mb-md-3'>
									<label for='lipid'>Lipid (g)</label>
									<input type='number' name='lipid' min='0' class='form-control'></input>
								</div>
							</div>
							<div class='col'>
								<div class='form-label-group mb-md-3'>
									<label for='carbohydrate'>Carbohydrate (g)</label>
									<input type='number' name='carbohydrate' min='0' class='form-control'></input>
								</div>
								<div class='form-label-group'>
									<label for='fibre'>Fibre (g)</label>
									<input type='number' name='fibre' min='0' class='form-control'></input>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class='form-label-group text-center mb-md-5 mt-md-4'>
						<label class='h3' for='ingredient'>Ingredients</label>
						<table class='table table-bordered table-striped mt-md-4' id='dynamic_ingredient'>
							<tr> 
								<td class='form-label-group text-left'>
									<label for='ingredient[0]'>Name</label>
									<input type='text' id='ingredient_0' class='form-control autocomplete' name='ingredient[0]' placeholder='Enter ingredient name'>
								</td>
								<td>
									<div class='d-flex justify-content-center'>
										<button type='button' id='add_ingredient_searching_recipe' class='btn btn-success font-weight-bold mt-md-3'>Add more</button>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<hr>
					<div class='mt-md-4'>
						<h3 class='text-center h3'>Categories</h3>
						<div class='d-flex justify-content-around mb-md-4 mt-md-4'>
							<div class='form-label-group text-center'>
								<label for='ustensile'>Ustensiles</label>
								<table class='table table-bordered table-striped mt-md-4' id='dynamic_ustensile'>
									<tr> 
										<td>
											<input id='ustensile_0' class='form-control autocomplete' type='text' name='ustensile[0]' placeholder='Enter ustensile'>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_ustensile' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div class='form-label-group'>
								<div class='d-flex justify-content-center align-items-baseline mb-md-4'>
									<label for='theme'>Themes</label>
								</div>
								<table class='table table-bordered table-striped' id='dynamic_theme'>
									<tr> 
										<td>
											<input id='theme_0' class='form-control autocomplete' type='text' name='theme[0]' placeholder='Enter theme'>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_theme' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class='d-flex justify-content-around mb-md-5'>
							<div class='form-label-group'>
								<div class='d-flex justify-content-center align-items-baseline mb-md-4'>
									<label for='diet'>Diets</label>
								</div>
								<table class='table table-bordered table-striped' id='dynamic_diet'>
									<tr> 
										<td>
											<input id='diet_0' class='form-control autocomplete' type='text' name='diet[0]' placeholder='Enter diet'>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_diet' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div class='form-label-group'>
								<div class='d-flex justify-content-center align-items-baseline mb-md-4'>
									<label for='dishtype'>Dish types</label>
								</div>
								<table class='table table-bordered table-striped' id='dynamic_dishtype'>
									<tr> 
										<td>
											<input id='dishtype_0' class='form-control autocomplete' type='text' name='dishtype[0]' placeholder='Enter dish type'>
										</td>
										<td>
											<div class='d-flex justify-content-center'>
												<button type='button' id='add_dishtype' class='btn btn-success font-weight-bold'>Add more</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<input class='btn btn-lg btn-primary btn-block' id='submit' type='submit' value='Search recipe'/>
				</form>
			</div>";

		echo $result;
	}
}
