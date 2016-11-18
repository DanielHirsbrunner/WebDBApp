<?php

namespace App\Utils;

/**
 * Class with set of utils methods
 */
class OtherUtils {

	public static function redirect($url, $replace = true, $respose = 303) {
		header("Location: ".$_SESSION["basePath"].$url, $replace, $respose);
		die();
	}

	public static function hashPassword($plainText, $cost = 11) {
		$options = ["cost" => $cost];
		$hashed = password_hash($plainText, PASSWORD_BCRYPT, $options);
		return $hashed;
	}

	public static function checkAdmin() {
		if ($_SESSION["user"]["isAdmin"] != 1) {
			FlashMessage::add(FlashMessage::TYPE_AUTH, "You don't have permission to access this page. Redirected to default page.");
			OtherUtils::redirect("/");
		}
	}

	public static function getAdminPageAction(array $whitelist, $default = "list") {
		$action = "";
		if (isset($_GET["action"])) {
			$tempAction = $_GET["action"];
			if (in_array($tempAction, $whitelist)) { // already done in .htaccess, so once it is here, it should be always true
				$action = $tempAction;
			}
		} else {
			$action = $default;
		}
		return $action;
	}

}
