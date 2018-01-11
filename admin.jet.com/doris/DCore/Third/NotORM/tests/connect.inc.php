<?php
error_reporting(E_ALL | E_STRICT);
include dirname(__FILE__) . "/../NotORM.php";

$user="root"; $pass="";$connection = new PDO('mysql:host=localhost;dbname=test1', $user, $pass);
// $connection = new PDO("mysql:dbname=software", "ODBC");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
$software = new NotORM($connection);
//~ $software->debug = true;
