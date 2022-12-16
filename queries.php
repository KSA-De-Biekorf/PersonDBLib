<?php

define("Q_ALL", "SELECT Persons.id, Persons.name as `naam`, GROUP_CONCAT(DISTINCT Bannen.ban) as `bannen`, GROUP_CONCAT(DISTINCT Emails.email) as `emails` FROM Persons INNER JOIN Bannen ON Persons.id = Bannen.person_id INNER JOIN Emails ON Persons.id = Emails.person_id GROUP BY Persons.id");

/**
 * @param mysqli $conn a valid database connection via `mysqli`
 * @return mysqli_result|bool the database result. Use `$result->fetch_assoc()`
 * to iterate over the query result's rows.
 */
function queryAll($conn) {
    return $conn->query(constant("Q_ALL"));
}

?>
