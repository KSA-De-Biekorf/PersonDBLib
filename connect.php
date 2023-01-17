<?php

require(dirname(__FILE__)."/server_info.php");

function new_connection() {
	$si = get_server_info();
	$conn = new mysqli($si->servername, $si->user, $si->pass);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if (!$conn->query("use k16461ks_mailing")) {
		die("Could not select database: " . $conn->error);
	}

	return $conn;
}

?>
