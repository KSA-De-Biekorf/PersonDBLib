<?php

require(dirname(__FILE__)."/server_info.php");

function new_connection() {
	$conn = new mysqli($servername, $user, $pass);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
}

?>
