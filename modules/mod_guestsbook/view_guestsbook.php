<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/view_generic.php';

class ViewGuestsBook extends ViewGeneric {

	public function __construct() {
		new ViewGeneric();
	}

	/**
	 * Display contact page
	 * @param string $msg
	 * @param string $fullName
	 * @param array|int $sentMessages
	 */
	public function guestsbook_page($msg, $fullName, $sentMessages) {
		$result = "
			<div class='guestBook container mt-md-5 mb-md-5 p-4 bg-white'>
				<div class='text-center mb-md-5'>	
					<h2 class='h2 mb-md-3'>Guest Book</h2>
				</div>
				<div class='container mb-md-5'>";
		
		if (!is_array($sentMessages) || empty($sentMessages)) {
			$result .= "
					<p class='text-center'>No messages found.</p>";
		} else {
			$result .= "
					<table class='table table-striped'>";

			foreach ($sentMessages as $message) {
				$result .= "<tr><td class='pt-3'>";

				if (isset($_SESSION['login']) && !empty($_SESSION['login']) && $_SESSION['login']['role'] == 1)
					$deleteMsgButton = "<a class='btn btn-danger mr-md-3 font-weight-bold' href='index.php?module=admin&action=delete_guestsbook_msg&id=" . $message['guestsbook_id'] ."'>Delete message</a>";
				else			
					$deleteMsgButton = '';

				$message_date = new DateTime($message['guestsbook_date']);
				$result .= "
							<div class='d-flex justify-content-between mb-md-3'>
								<p>" 
								. $message['guestsbook_message'] . 
								"</p>
								$deleteMsgButton
							</div>"
							. "<p class='guestBook_author text-secondary text-right'>from "
							. $message['guestsbook_message'] . " - ". $message_date->format('Y/m/d h:m:s') . "</p></div>";
				
				$result .= "</tr></td>";	
			}

			$result .= "
					</table>";
		}

		$result .= "
				</div>
				<hr>
				<p class='text-center mt-md-5 mb-md-5'>Leave a comment on the site here !</p>
				$msg
				<form class='container form-signin' action='index.php?module=guestsbook&action=send_guestsbook_msg' method='post'>
					<div class='form-label-group mb-md-3'>
						<label for='name'>Your name <span class='text-danger'>*</span></label>
						<input type='text' name='name' class='form-control' placeholder='Enter your name' value='$fullName' required>	
					</div>
					<div class='form-label-group mb-md-5'>
						<label for='message'>Your message <span class='text-danger'>*</span></label>
						<textarea type='text' name='message' rows='4' class='form-control md-textarea' placeholder='Enter your message' maxlength='1000' required></textarea>
					</div>
					<input class='btn btn-lg btn-primary btn-block' type='submit' value='Send'/>
				</form>
			</div>";

		echo $result;
	}
}

?>
