<?php

define("Q_ALL_PERSONS", "
SELECT
	Persons.id,
	Persons.first_name as `voornaam`,
	Persons.last_name as `achternaam`,
	GROUP_CONCAT(DISTINCT par_Ban.short_name) as `bannen`,
	GROUP_CONCAT(DISTINCT Emails.email) as `emails`
FROM Persons
LEFT JOIN Bannen ON Persons.id = Bannen.person_id
LEFT JOIN Emails ON Persons.id = Emails.person_id
LEFT JOIN par_Ban ON par_Ban.id = Bannen.ban
GROUP BY Persons.id
");

/**
 * @param mysqli $conn a valid database connection via `mysqli`
 * @return mysqli_result|bool the database result. Use `$result->fetch_row()`
 * to iterate over the query result's rows.
 */
function query_all_persons($conn) {
    return $conn->query(constant("Q_ALL_PERSONS"));
}

/**
 * Query all persons, with the given filters
 */
function query_all_persons_with($conn) {
	die("TODO");
}

/** Query all emails from a ban, does not filter out any data */
function query_ban($conn, $ban) {
    $banS = $conn->real_escape_string($ban);
    return $conn->query("
SELECT Persons.first_name, Persons.last_name, Emails.email FROM Persons
INNER JOIN Emails ON Persons.id = Emails.person_id
INNER JOIN Bannen ON Persons.id = Bannen.person_id
WHERE Bannen.ban = $banS
");
}

/** Add a person to the database */
function add_person($conn, $firstname, $lastname, $emails, $bannen) {
	$firstNS = $conn->real_escape_string($firstname);
	$lastNS = $conn->real_escape_string($lastname);
	
	if (!$conn->query("
INSERT INTO Persons (first_name, last_name)
VALUES ('$firstNS', '$lastNS')
")) {
		die("Could not add new person" . $conn->err);
	}

	# Get person's id 
	$person_id_result = $conn->query("SELECT MAX(id) FROM Persons WHERE Persons.first_name = '$firstNS' AND last_name = '$lastNS'");
	if (!$person_id_result) {
		die("Could not retrieve person's id: " . $conn->error);
	}
	$person_id_row = $person_id_result->fetch_row();
	$person_id = $person_id_row[0];

	# add emails
	foreach ($emails as $email) {
		if (!add_email($conn, $person_id, $email)) {
			die("Could not add email to user $person_id: " . $conn->error);
		}
	}

	# add bannen
	foreach ($bannen as $ban) {
		if (!add_ban($conn, $person_id, $ban)) {
			die("Could not add ban to user $person_id: " . $conn->error);
		}
	}

	$person_id_result->free_result();

	return $person_id;
}

/** Add an email to a person */
function add_email($conn, $id, $email) {
	return $conn->query("INSERT INTO Emails VALUES ($id, '$email')");
}

/** Add a ban to a person */
function add_ban($conn, $id, $ban) {
	return $conn->query("INSERT INTO Bannen VALUES ($id, '$ban')");
}

?>
