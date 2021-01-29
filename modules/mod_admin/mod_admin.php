<?php 
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './modules/mod_generic.php';
include_once 'cont_admin.php';

class ModAdmin extends ModGeneric {

	public function __construct() {
        $this->controller = new ContAdmin();
       
        if (!isset($_SESSION['login']) || empty(($_SESSION['login']) || !$_SESSION['login']['role'] == 1 || !isset($_GET['id']) || empty($_GET['id']))) 
            $this->controller->notFound_page();
        else {
            if (isset($_GET['action']))
                $action = $_GET['action'];
            else
                $action = 'default';

            switch ($action) {
                case 'delete_account':
                case 'delete_comment':
                case 'delete_guestsbook_msg':
                case 'delete_recipe';
                    $this->controller->$action();
                    break;
                default:
                    $this->controller->notFound_page();
            }
        }
	}
}
?>
