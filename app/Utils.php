<?php

namespace App;

/**
 * Class with set of utils methods
 */
class Utils {

	public static function redirect($url, $replace = true, $respose = 303) {
		header("Location: ".$_SESSION["basePath"].$url, $replace, $respose);
		die();
	}

	public static function hashPassword($plainText, $cost = 11) {
		$options = ["cost" => $cost];
		$hashed = password_hash($password, PASSWORD_BCRYPT, $options);
		return $hashed;
	}

}
