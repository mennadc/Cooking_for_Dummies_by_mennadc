<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewHome extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display home page
	 * @param array|int $most_popular_recipes_by_views
	 * @param array|int $most_popular_categories_by_rating
	 * @param array|int $most_recent_recipes
	 */
	public function home_page($most_popular_recipes_by_views, $most_popular_categories_by_rating, $most_recent_recipes) {
		if ($most_popular_recipes_by_views == -1)
			$result = '';
		else {
			$result = "
				<div id='myCarousel' class='carousel slide' data-ride='carousel'>
					<ol class='carousel-indicators'>
						<li data-target='#myCarousel' data-slide-to='0' class='active'></li>
						<li data-target='#myCarousel' data-slide-to='1' class=''></li>
						<li data-target='#myCarousel' data-slide-to='2' class=''></li>
					</ol>
					<div class='carousel-inner'>";

			for ($i = 0; $i < count($most_popular_recipes_by_views); $i++) {
				if ($i == 0)
					$result .= "<div class='carousel-item active'>";
				else 
					$result .= "<div class='carousel-item'>";

				$result .= "
							<img class='carousel-img' src='" . $most_popular_recipes_by_views[$i]['image'] . "' alt='carousel image 1'>
							<div class='carousel-caption'>
								<h2 class='h2'>" . ucfirst($most_popular_recipes_by_views[$i]['title']) . "</h2>
								<p class='card__text'>" . $most_popular_recipes_by_views[$i]['username'] . "</p>
								<p class='card__text'>" . (new DateTime($most_popular_recipes_by_views[$i]['date']))->format('Y/m/d') . "</p>
								<ul class='card__text d-flex justify-content-center recipe__rating'>";				

				for ($rating = 0; $rating < 5; $rating++) {
					if ($most_popular_recipes_by_views[$i]['rating'] != null && $rating < $most_popular_recipes_by_views[$i]['rating'])
						$result .= "<li class='recipe__heart--full'><i class='fas fa-heart'></i><li>";
					else
						$result .= "<li class='recipe__heart--empty'><i class='far fa-heart'></i><li>";
				}
				
				$result .= "
								</ul>
								<p><a class='btn btn-lg btn-primary' href='index.php?module=recipe&action=recipe_page&id=" . $most_popular_recipes_by_views[$i]['id'] . "'>See more</a></p>
							</div>
							<div class='overlay-dark'></div>
						</div>";
			}

			$result .= "
					</div>
					<a class='carousel-control-prev' href='#myCarousel' role='button' data-slide='prev'>
						<span class='carousel-control-prev-icon' aria-hidden='true'></span>
						<span class='sr-only'>Previous</span>
					</a>
					<a class='carousel-control-next' href='#myCarousel' role='button' data-slide='next'>
						<span class='carousel-control-next-icon' aria-hidden='true'></span>
						<span class='sr-only'>Next</span>
					</a>
				</div>";
		}

		$result .= "
			<div id='home' class='container mt-md-5 mb-md-5 p-4 rounded-lg bg-white'>
				<div class='album py-5'>
					<div class='container'>
						<h3 class='text-center mb-md-5 font-family-Open-Sans'>Most beloved recipes</h3>
						<hr>";

		if ($most_popular_categories_by_rating == -1)
			$result .= "<p class='text-center'>No recipes found</p>";
		else {
			$result .= "<div class='row cards mt-md-5'>";
		
			for ($i = 0; $i < count($most_popular_categories_by_rating); $i++) {
				$date = new DateTime($most_popular_categories_by_rating[$i]['date']);
				$result .= "
								<div class='card col-md-4 mb-4'>
									<a style='background: center no-repeat url(\"" . $most_popular_categories_by_rating[$i]['image'] . "\");' class='card__content' href='index.php?module=recipe&action=recipe_page&id=" . $most_popular_categories_by_rating[$i]['id'] . "'>
										<p class='card__text card__title'>" . ucfirst($most_popular_categories_by_rating[$i]['title']) . "</p>
										<p class='card__text card__username'>" . $most_popular_categories_by_rating[$i]['username'] . "</p>
										<p class='card__text card__date'>" . $date->format('Y/m/d') . "</p>
										<ul class='card__text d-flex justify-content-center recipe__rating'>";
									
				for ($rating = 0; $rating < 5; $rating++) {
					if ($most_popular_categories_by_rating[$i]['rating'] != null && $rating < $most_popular_categories_by_rating[$i]['rating'])
						$result .= "<li class='recipe__heart--full'><i class='fas fa-heart'></i><li>";
					else
						$result .= "<li class='recipe__heart--empty'><i class='far fa-heart'></i><li>";
				}
		
				$result .= "
										</ul>
									</a>
								</div>";
			}

			$result .= "</div>";
		}				

		$result .= "
					</div>
				</div>
				<div class='album py-5'>
					<div class='container'>
						<h3 class='text-center mb-md-5 font-family-Open-Sans'>Most recent recipes</h3>
						<hr>";

		if ($most_recent_recipes == -1)
			$result .= "<p class='text-center'>No recipes found</p>";
		else {
			$result .= "<div class='row cards mt-md-5'>";

			for ($i = 0; $i < count($most_recent_recipes); $i++) {
				$date = new DateTime($most_recent_recipes[$i]['date']);
				$result .= "
								<div class='card col-md-4 mb-4'>
									<a style='background: center no-repeat url(\"" . $most_recent_recipes[$i]['image'] . "\");' class='card__content' href='index.php?module=recipe&action=recipe_page&id=" . $most_recent_recipes[$i]['id'] . "'>
										<p class='card__text card__title'>" . ucfirst($most_recent_recipes[$i]['title']) . "</p>
										<p class='card__text card__username'>" . $most_recent_recipes[$i]['username'] . "</p>
										<p class='card__text card__date'>" . $date->format('Y/m/d') . "</p>
										<ul class='card__text d-flex justify-content-center recipe__rating'>";
									
				for ($rating = 0; $rating < 5; $rating++) {
					if ($most_recent_recipes[$i]['rating'] != null && $rating < $most_recent_recipes[$i]['rating'])
						$result .= "<li class='recipe__heart--full'><i class='fas fa-heart'></i><li>";
					else
						$result .= "<li class='recipe__heart--empty'><i class='far fa-heart'></i><li>";
				}
		
				$result .= "
										</ul>
									</a>
								</div>";
			}

			$result .= "</div>";
		}

		$result .= "
					</div>
				</div>
				<div class='album py-5'>
					<div class='container'>
						<h3 class='text-center mb-md-5 font-family-Open-Sans'>Categories</h3>
						<hr>
						<div class='row cards mt-md-5'>
							<div class='card col-md-4 mb-4'>
								<a style='background: center no-repeat url(\"./resources/img/categories/dessert.jpeg\");' class='card__content' href='index.php?module=recipe&action=search_category&entitled=dessert&category=dishtype&association=define'>
									<p class='card__text card__title'>Dessert</p>
								</a>
							</div>
							<div class='card col-md-4 mb-4'>
								<a style='background: center no-repeat url(\"./resources/img/categories/lunch.jpeg\");' class='card__content' href='index.php?module=recipe&action=search_category&entitled=lunch&category=dishtype&association=define'>
									<p class='card__text card__title'>Lunch</p>
								</a>
							</div>
							<div class='card col-md-4 mb-4'>
								<a style='background: center no-repeat url(\"./resources/img/categories/healthy.jpeg\");' class='card__content' href='index.php?module=recipe&action=search_category&entitled=healthy&category=diet&association=beon'>
									<p class='card__text card__title'>Healthy</p>
								</a>
							</div>
						</div>
						<div class='row cards mt-md-5'>
							<div class='card col-md-4 mb-4'>
								<a style='background: center no-repeat url(\"./resources/img/categories/birthday.jpeg\");' class='card__content' href='index.php?module=recipe&action=search_category&entitled=birthday&category=theme&association=belongto'>
									<p class='card__text card__title'>Birthday</p>
								</a>
							</div>
							<div class='card col-md-4 mb-4'>
								<a style='background: center no-repeat url(\"./resources/img/categories/breakfast.jpeg\");' class='card__content' href='index.php?module=recipe&action=search_category&entitled=breakfast&category=dishtype&association=define'>
									<p class='card__text card__title'>Breakfast</p>
								</a>
							</div>
							<div class='card col-md-4 mb-4'>
								<a style='background: center no-repeat url(\"./resources/img/categories/summer.jpeg\");' class='card__content' href='index.php?module=recipe&action=search_category&entitled=summer&category=dishtype&association=define'>
									<p class='card__text card__title'>Summer</p>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>";

		echo $result;
	}

}
