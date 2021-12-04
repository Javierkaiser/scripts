<?php
function db_connect() {
    $db = new mysqli('localhost', 'root', '', 'suelta');
    if ($db->connect_error) {
        die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
    }
    return $db;
}
