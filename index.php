<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
define('CONST_INCLUDE', NULL);

if (isset($_GET['module']))
	$module	= $_GET['module'];
else 
	$module = 'default';

switch ($module) {
	case 'home':
	case 'connection':
	case 'user':
	case 'admin':
	case 'contact':
	case 'faq':
	case 'legalnotice':
	case 'guestsbook':
	case 'recipe':
		include_once "modules/mod_$module/mod_$module.php";
		$module = 'Mod' . ucfirst(str_replace('_', '', $module));
		include_once 'db/db_connection.php';
		DBConnection::initDBConnection();
		include_once 'composants/comp_nav/cont_nav.php';
		include_once 'composants/comp_footer/cont_footer.php';
		break;
	default:
		die('Access to this module is not allowed');
}
$mod = new $module();
$modView = $mod->getDisplay();
$contNav = new ContNav();
$contFooter = new ContFooter();
include_once './template.php';
?>
