<?php

function format_persons($qres) {
    echo "<table>";
    print_header(array("id", "voornaam", "achternaam", "bannen", "emails"));
    while ($row = $qres->fetch_array(MYSQLI_NUM)) {
        print_table_row($row);
    }
    echo "</table>";

    $qres->free_result();
}

function print_header($headers) {
    echo "<tr>";
    foreach ($headers as $header) {
        echo "<th>$header</th>";
    }
    echo "<tr>";
}

function print_table_row($row) {
    echo "<tr>";
    foreach ($row as $cell) {
        echo "<td>$cell</td>";
    }
    echo "</tr>";
}

?>
