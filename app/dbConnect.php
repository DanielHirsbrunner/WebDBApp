<?php
require_once("DB.php");

$dsn = "mysql://root@localhost/moduleinfo";

$db = \DB::connect($dsn);
if (\DB::isError($db)) {
	die("Connecting failed: ".$db->getMessage());
}
