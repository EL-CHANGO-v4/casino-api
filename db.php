<?php
$host = "mysql.railway.internal"; 
$user = "root"; 
$pass = "rJwwdlNlDAZRnKiYJUyYUxsJMZOIlySV"; 
$db   = "railway"; 
$conn = new mysqli($host, $user, $pass, $db, 3306);
if ($conn->connect_error) { die("Error"); }
$conn->set_charset("utf8mb4");
?>
