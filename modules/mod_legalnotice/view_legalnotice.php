<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewLegalNotice extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display legal notice page
	 */
	public function legal_notice_page() {
		echo "
			<div class='container mt-md-5 mb-md-5 p-4 bg-white'> 
				<div class='text-center mb-md-5'>
					<h2 class='h2 mb-md-3'>Legal notice</h2>
				</div>
				<div id='legalnotice-container' class='container text-left'>
					<div>
						<h3 class='h3 mb-3'>Copyrights</h3>
						<p>Any reproduction or copy of the contents present on the site (in particular recipes) for purposes other than private use is only possible after written authorization from the site managers.</p>
					<div>
					<div class='mt-md-4'>
						<h3 class='h3 mb-3'>Responsibility for content</h3>
						<ul>
							<li>Cooking for Dummies is not responsible for the origin of the recipes. The managers of the site can in no way be held responsible for the content entered online. The only commitment we make is not to publish any offensive, insulting or unrelated content.</li>
							<br>
							<li>If by mistake, one of the receipts received is specifically protected by a copyright, we will remove it from our database, but disclaim any responsibility for the rights concerning it. We believe that the world's culinary heritage is vast enough that there is no need to copy books!</li>
							<br>
							<li>Cooking for Dummies can in no way be held responsible for the result of any of the recipes on the site. These recipes are given for information only and it is the responsibility of the users of these recipes to ensure that no risk results from the preparation or consumption of the resulting products.</li>
							<br>
							<li>
								We provide you with a system allowing you to contact the site moderator immediately <a href='index.php?module=contact&action=contact_page'>by clicking here</a>
								<br><br>
								You may report to the moderator any behaviour or comments that do not comply with these Rules of Good Conduct or that may present any danger to Internet users (especially minors) of which you may be a victim or witness.
								<br><br>
								In particular, you may report the following to the moderator
								<br>
								- Proposals or content (images, comments) of a sexual or pornographic nature,
								<br>
								- Suicidal behavior or comments by a user expressing distress or a situation that could endanger his or her life,
								<br>
								- Statements expressing a willingness to commit an illegal or dangerous act,
								<br>
								- Comments that condone risky, illegal or non-compliant behaviour.
								<br><br>
								Once alerted, the moderator may take any measures he or she deems necessary to prevent the recurrence of such behavior and may notably ban the perpetrators.
								<br>
								The moderator may also be required to transmit the reported information to the competent authorities.
							</li>
						</ul>
					<div>
				</div>
			</div>";
	}
}
?>