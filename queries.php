<?php

define("Q_ALL", "SELECT Persons.id, Persons.first_name as `voornaam`, Persons.last_name as `achternaam`, GROUP_CONCAT(DISTINCT Bannen.ban) as `bannen`, GROUP_CONCAT(DISTINCT Emails.email) as `emails` FROM Persons INNER JOIN Bannen ON Persons.id = Bannen.person_id INNER JOIN Emails ON Persons.id = Emails.person_id GROUP BY Persons.id");

/**
 * @param mysqli $conn a valid database connection via `mysqli`
 * @return mysqli_result|bool the database result. Use `$result->fetch_row()`
 * to iterate over the query result's rows.
 */
function queryAll($conn) {
    return $conn->query(constant("Q_ALL"));
}

/** Query all emails from a ban, does not filter out any data */
function queryBan($conn, $ban) {
    $banS = $conn->real_escape_string($ban);
    return $conn->query("
SELECT Persons.first_name, Persons.last_name, Emails.email FROM Persons
INNER JOIN Emails ON Persons.id = Emails.person_id
INNER JOIN Bannen ON Persons.id = Bannen.person_id
WHERE Bannen.ban = $banS
");
}

?>
