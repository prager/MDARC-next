<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
$host = "db5004986585.hosting-data.io";
$user = "dbu141600";
$pass = "PaaSsWrd041-99-awq";
$db   = "dbs4171223";
$link = mysqli_connect($host, $user, $pass, $db);
if (!$link) die("mysqli error: " . mysqli_connect_error());
echo "mysqli OK";
