<?php
$servername = "localhost";
$username = "root";
$pass = "";
$db_name = "submission_site";


$connect_db = new mysqli($servername, $username, $pass, $db_name);

if(!$connect_db) {
    echo "Connection Fail";
} else {
    echo "Connection Success";
}


