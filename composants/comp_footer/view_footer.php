<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

class ViewFooter {
	private $strFooter;

	public function __construct() {

	}

	/**
	 * Init footer view
	 */
	public function init_view() {
		$this->strFooter = "
			<footer class='main-background pb-md-3 pt-md-3 footer'>
				<div class='d-flex justify-content-around pb-md-3'>
					<div>
						<h4 class='font-weight-bold'>Recipes</h4>
						<ul class='list-unstyled text-small'>
							<li><a href='index.php?module=recipe&action=search_recipe&title=pizza'>Pizza</a></li>
							<li><a href='index.php?module=recipe&action=search_recipe&title=pancake'>Pancake</a></li>
							<li><a href='index.php?module=recipe&action=search_recipe&title=hamburger'>Hamburger</a></li>
							<li><a href='index.php?module=recipe&action=search_recipe&title=pasta'>Pasta</a></li>
						</ul>
					</div>
					<div>
						<h4 class='font-weight-bold'>Ingredients</h4>
						<ul class='list-unstyled text-small'>
							<li><a href='index.php?module=recipe&action=search_ingredient&name=tomato'>Tomatoes</a></li>
							<li><a href='index.php?module=recipe&action=search_ingredient&name=beef'>Beef</a></li>
							<li><a href='index.php?module=recipe&action=search_ingredient&name=apple'>Apple</a></li>
							<li><a href='index.php?module=recipe&action=search_ingredient&name=eggs'>Eggs</a></li>
						</ul>
					</div>
					<div>
						<h4 class='font-weight-bold'>Diets</h4>
						<ul class='list-unstyled text-small'>
							<li><a href='index.php?module=recipe&action=search_category&entitled=vegetarian&association=beon&category=diet'>Vegetarian</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=healthy&association=beon&category=diet'>Healthy</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=lamb&association=beon&category=diet'>Lamb</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=diabetic&association=beon&category=diet'>Diabetic</a></li>
						</ul>
					</div>
					<div>
						<h4 class='font-weight-bold'>Themes</h4>
						<ul class='list-unstyled text-small'>
							<li><a href='index.php?module=recipe&action=search_category&entitled=birthday&association=belongto&category=theme'>Birthday</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=summer&association=belongto&category=theme'>Summer</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=wedding&association=belongto&category=theme'>Wedding</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=barbeque&association=belongto&category=theme'>Barbeque</a></li>
						</ul>
					</div>
					<div>
						<h4 class='font-weight-bold'>Dish types</h4>
						<ul class='list-unstyled text-small'>
							<li><a href='index.php?module=recipe&action=search_category&entitled=lunch&association=define&category=dishtype'>Lunch</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=main&association=define&category=dishtype'>Main course</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=dessert&association=define&category=dishtype'>Dessert</a></li>
							<li><a href='index.php?module=recipe&action=search_category&entitled=breakfast&association=define&category=dishtype'>Breakfast</a></li>
						</ul>
					</div>
				</div>
				<div class='d-flex justify-content-around pb-md-3'>
					<a href='index.php?module=faq'>
						<h4>FAQ</h4>
					</a>
					<a href='index.php?module=guestsbook&action=guestsbook_page'>
						<h4>Guests book</h4>
					</a>
					<a href='index.php?module=contact&action=contact_page'>
						<h4>Contact</h4>
					</a>
				</div>
				<div class='d-flex justify-content-center pb-md-3'>
					<a class=' ml-md-5 share__network twitter-share-button' data-size='large' href='https://twitter.com/intent/tweet'>Tweet</a>
					<a class='ml-md-5 share__network' data-pin-do='buttonPin' href='https://www.pinterest.com/pin/create/button/?url=http://www.foodiecrush.com/2014/03/filet-mignon-with-porcini-mushroom-compound-butter/&media=https://i.pinimg.com/736x/17/34/8e/17348e163a3212c06e61c41c4b22b87a.jpg&description=So%20delicious!' data-pin-custom='true'><img src='https://addons.opera.com/media/extensions/55/19155/1.1-rev1/icons/icon_64x64.png' width='25' height='25'></a>
					<div class='ml-md-5 share__network fb-share-button' data-href='https://developers.facebook.com/docs/plugins/' data-layout='button' data-size='large'>
						<a target='_blank' href='https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse' class='fb-xfbml-parse-ignore'>Partager</a>
					</div>
				</div>
				<div class='text-center' id='copyright-text'>
						<p class='mb-md-0'>© Copyright 2020-2021 Cooking for Dummies</p>
						<p class='mb-md-0'><a href='index.php?module=legalnotice'>Legal notice</a></hp>
					<p>Concocted by mennadc</p>
				</div>
			</footer>
			<script async defer crossorigin='anonymous' src='https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v9.0' nonce='rJuaNf6s'></script>
			<script type='text/javascript' async src='https://platform.twitter.com/widgets.js'></script>";
	}

	/**
	 * Display footer view
	 */
	public function display() {
		echo $this->strFooter;
	}
}

?>
