<?php

/** Prints out the table header
 * @parameter string[] $headers Array of the column names
 */
function tableHeader($headers) {
    echo "<tr>";
    foreach ($headers as $header) {
        echo "<th>" . $header . "</th>";
    }
    echo "</tr>";
}

/** Prints out a table row */
function tableRow($cells) {
    echo "<tr>";
    foreach ($cells as $cell) {
        echo "<td>" . $cell . "</td>";
    }
    echo "</tr>";
}

?>
