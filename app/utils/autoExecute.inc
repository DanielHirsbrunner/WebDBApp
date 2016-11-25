<?php

function improvedAutoExecute($db, $tableName, $fieldsValues, $method, $condition = "") {

	$result = $db->autoExecute($tableName, $fieldsValues, $method, $condition);

	if (\DB::isError($result)) {
		\App\Utils\FlashMessage::add(\App\Utils\FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
		return false;
	} else {
		return true;
	}
}