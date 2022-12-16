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

/** Returns a string with the ban ids replaced by the ban name (short form) and adds a space after "," */
function replaceBan($str) {
    return str_replace(
        [0, 1, 2, 3, 4, 5, 6, 7, ","],
        ["kab", "pag", "jkn", "kn", "jhn", "leiding", "vwb", "oud-leiding", ", "],
        $str
    );
}

?>
