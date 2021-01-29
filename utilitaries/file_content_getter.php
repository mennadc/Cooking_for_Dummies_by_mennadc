<?php
if (!defined('CONST_INCLUDE'))
    die('Direct access prohibited !');

class FileContentGetter {

    /**
	 * Return file contents
     * @param string $filePath
	 * @return string
	 */
    public static function get_file_contents($filePath) {
        return file_get_contents($filePath);
    }
}

?>