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

	public static function improvedAutoExecute($db, $tableName, $fieldsValues, $method, $condition = "") {

		$result = $db->autoExecute($tableName, $fieldsValues, $method, $condition);

		if (\DB::isError($result)) {
			FlashMessage::add(FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
			return false;
		} else {
			return true;
		}
	}

	public static function handleDeleteResult($postDelete, $result2, $errorMsg, $successMsg, $redirect) {
		if ($postDelete == "ajax") {
			if (!$result2) {
				http_response_code(400);
				echo $errorMsg;
			} else {
				echo $successMsg;
			}
			exit;
		} else {
			if (!$result2) {
				FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMsg);
			} else {
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, $successMsg);
			}
			OtherUtils::redirect($redirect);
		}
	}
	
	public static function getSyllabusVarText($variable) {
		if (isset($_SESSION['syllabus'])) {
			return htmlspecialchars(stripslashes($_SESSION['syllabus'][$variable]));
		} else {
			return '';
		}
	}
	
	public static function getSyllabusVarNr($variable) {
		if (!isset($_SESSION['syllabus']) || empty($_SESSION['syllabus'][$variable])) {
			return 0; 
		} else {
			return $_SESSION['syllabus'][$variable];
		}
	} 
	
	public static function getPostVarNumber($variable) {
		if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
			return 0; 
		} else {
			return $_POST[$variable];
		}
	}

}
