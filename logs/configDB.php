<?php

//********DB Credentials*****************
require_once('../admin/config.php');
//***************************************

define('DB_SERVER', $_servername);  // fill server location

define('DB_USERNAME', $_username ); // fill username

define('DB_PASSWORD', $_password); // fill password

define('DB_NAME', $_dbname);   // fill database name (default is flora)

$DBconnect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$DBconnect) {
    die("Connection failed: " . mysqli_connect_error());
}
