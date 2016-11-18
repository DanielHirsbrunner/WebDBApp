<?php

namespace App\Utils;

/**
 * Class with set of utils methods
 */
class FlashMessage {

	const TYPE_SUCCESS = "success";
	const TYPE_ERROR = "error";
	const TYPE_AUTH = "auth";
	const TYPE_DEBUGGING = "debugging";

	public static function add($type, $message) {
		if (!isset($_SESSION["messages"])) {
			$_SESSION["messages"] = [];
		}
		if (!isset($_SESSION["messages"][$type])) {
			$_SESSION["messages"][$type] = [];
		}
		array_push($_SESSION["messages"][$type], $message);
	}

	public static function show($template) {
		if (isset($_SESSION["messages"])) {

			foreach ($_SESSION["messages"] as $type => $value) {
				if ($type == FlashMessage::TYPE_DEBUGGING && !DEBUGGING) {
					continue;
				}
				for ($i = 0; $i < sizeof($value); $i++) { 
					$template->setCurrentBlock("FLASH_MESSAGE");
					$template->setVariable("MSG_TYPE", $type);
					$template->setVariable("MSG_TEXT", $value[$i]);
					$template->parseCurrentBlock("FLASH_MESSAGE");
				}
			}

			unset($_SESSION["messages"]);
		}
	}

}
