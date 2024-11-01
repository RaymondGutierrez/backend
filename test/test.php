<?php
include '../db/database.php';
$db = new Database();
if ($db->isConnected()) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed: ";
    print_r($db->getResult()); // Print error messages
}
