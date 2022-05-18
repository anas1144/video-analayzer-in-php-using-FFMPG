<?php
$mysql_server = "localhost";
$mysql_admin = "root";
$mysql_pass = "123456";
$mysql_db = "analyser";

$connection = mysqli_connect($mysql_server, $mysql_admin, $mysql_pass, $mysql_db) or die('Temporary unavailable.');

