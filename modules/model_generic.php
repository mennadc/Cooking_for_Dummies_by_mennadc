<?php  
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');

include_once './db/db_connection.php';
include_once './utilitaries/image_loader.php';
include_once './utilitaries/json_datas_handler.php';
include_once './utilitaries/mail_sender.php';

class ModelGeneric extends DBConnection {

	public function __construct() {

	}
}

?>