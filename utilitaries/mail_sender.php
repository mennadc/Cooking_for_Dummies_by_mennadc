<?php
if (!defined('CONST_INCLUDE'))
    die('Direct access prohibited !');

class MailSender {

    /**
	 * Send an email
     * @param string $email
     * @param string $subject
     * @param string $content
	 * @return boolean
	 */
    public static function send_mail($email, $subject, $content) {
        if (empty($email) || empty($subject) || empty($content) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))
            return false;
        else {
            $msg = "
            <html>
                <head>
                    <title>$subject</title>
                </head>
                <body>
                    $content
                </body>
            </html>";
            $headers = "From: Cooking for Dummies <cookingfordummies@contact.com>\r\n" . "MIME-Version: 1.0" . "\r\n" . "Content-type: text/html; charset=UTF-8" . "\r\n"; 

            return mail($email, $subject, $msg, $headers);
        }
    }
}

?>