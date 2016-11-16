<?php

$dsn = "mysql://moduleinfo:moduleinfo@127.0.0.1/moduleinfo";

$db = DB::connect($dsn);
if (DB::isError($db)) {
	var_dump($db);
	die("Connecting failed: ".$db->getMessage());
}
