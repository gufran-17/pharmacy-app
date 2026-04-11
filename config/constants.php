<?php 
ob_start();
session_start();

// 🔥 Change this later to your domain / load balancer URL
define('SITEURL', 'http://a07e50217c9c0430599be724d3d7a805-1aac2c210db3cbfc.elb.ap-south-1.amazonaws.com/');

// 🔥 ENV VARIABLES (safe + production ready)
$db_host = getenv('DB_HOST') ?: 'pharmacy-db.c7qim6s6ogkd.ap-south-1.rds.amazonaws.com';
$db_user = getenv('DB_USER') ?: 'admin';
$db_pass = getenv('DB_PASS') ?: 'gufran2003';
$db_name = getenv('DB_NAME') ?: 'pharmacy_mgmt';

// 🔥 DB CONNECTION
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// 🔥 ERROR HANDLING
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>