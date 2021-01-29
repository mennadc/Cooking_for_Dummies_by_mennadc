<?php
if (!defined('CONST_INCLUDE'))
    die('Direct access prohibited !');

class ImageLoader {

    /**
	 * Load an image to base 64
     * @param $imgContent
	 * @return string
	 */
    public static function load_image($imgContent) {
        $type = pathinfo($imgContent, PATHINFO_EXTENSION);
        $data = file_get_contents($imgContent);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    /**
	 * Check size of an avatar image
     * @param $avatarContent
	 * @return bool
	 */
    public static function checkAvatarDimensions($avatarContent) {
        list($width, $height) = getimagesize($avatarContent);
		return $width = $height && $width >= 300 && $width <= 450 && $height >= 300 && $height <= 450;
    }
    
    /**
	 * Check size of an recipe image
     * @param $imageContent
	 * @return bool
	 */
    public static function checkRecipeImageDimensions($imageContent) {
        list($width, $height) = getimagesize($imageContent);
		return $width = $height && $width >= 300 && $width <= 2000 && $height >= 300 && $height <= 2000;
	}
}

?>