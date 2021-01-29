<?php
if (!defined('CONST_INCLUDE'))
    die('Direct access prohibited !');

include_once('./utilitaries/file_content_getter.php');

class JsonDatasHandler {

    /**
	 * Decode json file datas
     * @param array $fileName
	 * @return array
	 */
    public static function get_decoded_json_datas($fileName) {
        return json_decode(FileContentGetter::get_file_contents("./resources/json_datas/$fileName.json"), true);
    }

    /**
	 * Encode array to json datas
     * @param array $datas
	 * @return string
	 */
    public static function encode_datas($datas) {
        return json_encode($datas);
    }    
}

?>