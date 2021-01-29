<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');
	
class ViewNav {
	private $strNav;

	public function __construct() {

	}

	/**
	 * Init nav view
	 * @param array|int $recipes
	 */
	public function init_view($recipes) {
		if (!isset($_SESSION['login']) || empty($_SESSION['login']))
			$navButtons = "
			<a href='index.php?module=connection&action=form_signin' class='btn navbar-button mr-md-4'>Sign in</a>
            <a href='index.php?module=connection&action=form_signup' class='btn navbar-button mr-md-4'>Sign up</a>";
		else {
			if (!is_array($recipes) || empty($recipes))
				$notifIcon = "bell-slash";
			else
				$notifIcon = "bell";

			$navButtons = "
				<button id='notif-button' class='btn mr-md-4'><i class='fas fa-$notifIcon'></i></button>
				<div id='recipes-notif' class='bg-white py-3 px-5'>";
			
			if (!is_array($recipes) || empty($recipes))
				$navButtons .= "
						<p class='text-center'>No recipes found.</p>";
			else {
				foreach($recipes as $recipe) {
					$date = new DateTime($recipe['date']);
					$navButtons .= "
							<div class='text-center recipe_notif mb-md-4 my-1 p-3 w-100'>
								<p><a href='index.php?module=recipe&action=recipe_page&id=" . $recipe['id'] . "'>" . ucfirst($recipe['title']) . "</a></p>
								<p><a class='recipe__username' href='index.php?module=user&action=profile_page&id=" . $recipe['user_id'] . "' >". $recipe['username'] ."</a></p>
								<p class='recipe__date'>". $date->format('Y/m/d') . "</p>	
							</div>";
				}
				$navButtons .= "
						<p class='mx-md-5 d-flex justify-content-center btn btn-primary'><a class='text-white' href='index.php?module=recipe&action=recipes_subscriptions'>See more</a></p>";
			}

			$navButtons .=	"
				</div>
				<a href='index.php?module=recipe&action=write_recipe' class='btn navbar-button mr-md-4'><i class='fas fa-book'></i> New recipe</a>
				<a href='index.php?module=user&action=overview' class='btn navbar-button mr-md-4'><i class='fas fa-cog'></i> Account</a>
				<a href='index.php?module=connection&action=signout' id='signout-button' class='btn mr-md-4'><i class='fas fa-sign-out-alt'></i> Sign out</a>";
		}
		$this->strNav = "
			<nav class='navbar navbar-dark d-flex space-between main-background'>
				<div class='navbar'>
					<button class='navbar-toggler collapsed mr-md-4' type='button' data-toggle='collapse' data-target='#navbarsExample01'>
						<span class='navbar-toggler-icon'></span>
					</button>
					<h1 class='h1'><a class='navbar-title' id='logo' href='index.php?module=home'>Cooking for Dummies.</a></h1>
				</div>
				<form class='form-inline' action='index.php?module=recipe&action=simple_search_recipe' method='post'>
					<input name='title' id='recipe_search' class='autocomplete form-control' type='text' placeholder='Search a recipe' aria-label='Search'>
					<button id='navsearch__btn' class='nav-form font-weight-bold ml-md-3 form-control-icon'><i class='fas fa-search'></i></button>
					<a href='index.php?module=recipe&action=advanced_search_page' class='nav-form font-weight-bold ml-md-1 form-control-link'>Advanced Search</a>
				</form>
				<div class='navbar d-flex space-between align-items-center'>
					$navButtons
				</div>
				<div class='navbar-collapse collapse' id='navbarsExample01'>
					<div class='nav-wrapper navbar-menu mt-md-4'>
						<ul class='navbar flex-grow-1 d-flex align-items-center'>
							<li>
								<h4 class='font-weight-bold'>Recipes</h4>
								<ul class='list-unstyled text-small'>
									<li><a href='index.php?module=recipe&action=search_recipe&title=pizza'>Pizza</a></li>
									<li><a href='index.php?module=recipe&action=search_recipe&title=pancake'>Pancake</a></li>
									<li><a href='index.php?module=recipe&action=search_recipe&title=hamburger'>Hamburger</a></li>
									<li><a href='index.php?module=recipe&action=search_recipe&title=pasta'>Pasta</a></li>
								</ul>
							</li>
							<li>
								<h4 class='font-weight-bold'>Ingredients</h4>
								<ul class='list-unstyled text-small'>
									<li><a href='index.php?module=recipe&action=search_ingredient&name=tomato'>Tomatoes</a></li>
									<li><a href='index.php?module=recipe&action=search_ingredient&name=beef'>Beef</a></li>
									<li><a href='index.php?module=recipe&action=search_ingredient&name=apple'>Apple</a></li>
									<li><a href='index.php?module=recipe&action=search_ingredient&name=eggs'>Eggs</a></li>
								</ul>
							</li>
							<li>
								<h4 class='font-weight-bold'>Diets</h4>
								<ul class='list-unstyled text-small'>
									<li><a href='index.php?module=recipe&action=search_category&entitled=vegetarian&association=beon&category=diet'>Vegetarian</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=healthy&association=beon&category=diet'>Healthy</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=lamb&association=beon&category=diet'>Lamb</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=diabetic&association=beon&category=diet'>Diabetic</a></li>
								</ul>
							</li>
							<li>
								<h4 class='font-weight-bold'>Themes</h4>
								<ul class='list-unstyled text-small'>
									<li><a href='index.php?module=recipe&action=search_category&entitled=birthday&association=belongto&category=theme'>Birthday</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=summer&association=belongto&category=theme'>Summer</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=wedding&association=belongto&category=theme'>Wedding</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=barbeque&association=belongto&category=theme'>Barbeque</a></li>
								</ul>
							</li>
							<li>
								<h4 class='font-weight-bold'>Dish types</h4>
								<ul class='list-unstyled text-small'>
									<li><a href='index.php?module=recipe&action=search_category&entitled=lunch&association=define&category=dishtype'>Lunch</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=main&association=define&category=dishtype'>Main course</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=dessert&association=define&category=dishtype'>Dessert</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=breakfast&association=define&category=dishtype'>Breakfast</a></li>
								</ul>
							</li>
							<li>
								<h4 class='font-weight-bold'>Ustensiles</h4>
								<ul class='list-unstyled text-small'>
									<li><a href='index.php?module=recipe&action=search_category&entitled=casseroles&association=need&category=ustensile'>Casseroles</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=board&association=need&category=ustensile'>Cutting Board</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=salade&association=need&category=ustensile'>Salade spinner</a></li>
									<li><a href='index.php?module=recipe&action=search_category&entitled=spatula&association=need&category=ustensile'>Spatula</a></li>
								</ul>
							</li>
						</ul>
						<div>
							<a href='index.php?module=faq'>
								<h4 class='font-weight-bold'>FAQ</h4>
							</a>
							<a href='index.php?module=contact&action=contact_page'>
								<h4 class='font-weight-bold'>Contact</h4>
							</a>
							<a href='index.php?module=guestsbook&action=guestsbook_page'>
								<h4 class='font-weight-bold'>Guests book</h4>
							</a>
						</div>
					</div>
				</div>
			</nav>";
	}
	
	/**
	 * Display nav view
	 */
	public function display() {
		echo $this->strNav;
	}
}

?>