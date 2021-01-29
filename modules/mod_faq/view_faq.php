<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewFaq extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display contact page
	 */
	public function faq_page() {
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>FAQ - Frequently asked questions</h2>
					<p class='font-weight-bold'>Find here most of the questions asked</p>	
				</div>
				<div id='faq-container' class='container text-left'>
					<div>
						<h3 class='h3 mb-3'>I'm looking for a recipe, how do I proceed?</h3>
						<p>First thing to do: use our search engine and enter the title or keywords corresponding to your search, then click on the 'Search' button and the list of recipes corresponding to this search will be displayed. You can add filters at the <a href='index.php?module=recipe&action=advanced_search_page'>advanced search page</a> to have only seasonal recipes, vegetarian recipes or only desserts for example. </p>
					<div>
					<div>
						<h3 class='h3 mb-3'>Who validates the recipes?</h3>
						<p>It is the team that validates. Well, we don't have time to test them all, of course, but just to judge their consistency, to put them back in shape a bit and sometimes to correct the spelling a bit. Also, we check that the recipe is not already in the database. By the way, before entering a recipe, please check that it does not already exist. We don't want to put you to work for nothing. For example, only send a recipe for chocolate cake if it is really, really different from the others.</p>
					<div>
					<div>
						<h3 class='h3 mb-3'>I sent you a question, but I didn't get an answer...</h3>
						<p>We are sometimes overloaded with work, if you need a quick answer, ask your question on the <a href='index.php?module=contact&action=contact_page'>contact page</a>.</p>
					<div>
					<div>
						<h3 class='h3 mb-3'>I would like to receive Cooking for Dummies' newsletters.</h3>
						<p>You can sign up here to receive our daily recipes or newsletters. If you don't receive it despite your subscriptions, remember to check that it hasn't gotten lost in your spam. If it is not there, please <a href='index.php?module=contact&action=contact_page'>contact us</a> !</p>
					<div>
					<div>
						<h3 class='h3 mb-3'>How to contact the Cooking for Dummies team?</h3>
						<p>You can contact the team very simply by clicking on this <a href='index.php?module=contact&action=contact_page'>link</a>. If you encounter a bug, please give us as many details as possible !</p>
					<div>
				</div>
			</div>";
	}
}
?>