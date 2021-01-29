<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');
	
class ViewAside {
	private $strAside;

	public function __construct() {
		
	}

	/**
	 * Init aside view
	 * @param array|int $most_popular_recipes
	 */
	public function init_view($most_popular_recipes) {
		$this->strAside = " 
				<aside id='aside' class='bg-white py-4 px-4'>
					<div class='d-sticky'>
						<div class='text-center mb-md-5'>
							<h3>Most viewed recipe</h3>
							<hr>
						</div>";
				
		if ($most_popular_recipes == -1) 
			$this->strAside .= " 
						<p>No recipe at this moment.<p>";
		else {
			$this->strAside .= " 
						<div class='container w-75'>";
			
			foreach ($most_popular_recipes as $recipe) {
				$date = new DateTime($recipe['date']);
				$this->strAside .= "
							<div class='card mb-5'>
								<a style='background: center no-repeat  url(\"" . $recipe['image'] . "\");' class='card__content' href='index.php?module=recipe&action=recipe_page&id=" . $recipe['id'] . "'>
									<p class='card__text card__title mt-5'>" . ucfirst($recipe['title']) . "</p>
									<p class='card__text card__username'>" . $recipe['username'] . "</p>
									<p class='card__text card__date'>" . $date->format('Y/m/d') . "</p>
									<ul class='card__text d-flex justify-content-center recipe__rating'>";
									
				for ($rating = 0; $rating < 5; $rating++) {
					if ($recipe['rating'] != null && $rating < $recipe['rating'])
						$this->strAside .= "
										<li class='recipe__heart--full'><i class='fas fa-heart'></i></li>";
					else
						$this->strAside .= "
										<li class='recipe__heart--empty'><i class='far fa-heart'></i></li>";
				}
				$this->strAside .= " 
									</ul>
								</a>
							</div>";
			}
			$this->strAside .= " 
						</div>";
		}
		$this->strAside  .= " 
					</div>
				</aside>
			</div>";
	}

	/**
	 * Display aside view
	 */
	public function display(){
		echo $this->strAside;
	}
}

?>