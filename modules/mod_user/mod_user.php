<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_user.php';

class ModUser extends ModGeneric {

	public function __construct() {
        $this->controller = new ContUser();
       
        if (isset($_GET['action']))
            $action = $_GET['action'];
        else
            $action = 'default';

        switch ($action) {
            case 'overview':
            case 'avatar_settings':
            case 'change_avatar':
            case 'password_settings':
            case 'reset_password':
            case 'email_settings':
            case 'reset_email':
            case 'deletion_page':
            case 'delete_account':
            case 'newsletter_page':
            case 'subscribe_newsletter':
            case 'unsubscribe_newsletter':
                if (!isset($_SESSION['login']) || empty($_SESSION['login']))
                    header('Location: ?module=home');
                else 
                    $this->controller->account_page($action);

                break;
            case 'unfollow':
            case 'follow':
                if (!isset($_SESSION['login']) || empty($_SESSION['login'])) { 
                    header('Location: ?module=home');
                    break;
				}
            case 'profile_page':
            case 'subscriptions':
            case 'followers':
                if (!isset($_GET['id']) || empty(($_GET['id'])))
                    $this->controller->notFound_page();
                else
                    $this->controller->$action();
                break;
            default:
                $this->controller->notFound_page();
        }
	}
}
?>
