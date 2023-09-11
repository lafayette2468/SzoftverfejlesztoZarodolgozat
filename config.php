<?php
define ('DB_SERVER', 'localhost');
define ('DB_USER', 'root');
define ('DB_PASS', '12345678');
define ('DB_NAME', 'te');

$conn = mysqli_connect( DB_SERVER, DB_USER, DB_PASS, DB_NAME );

if (mysqli_connect_errno())
{ echo "Csatlakozás nem sikerült: " . mysqli_connect_error();
exit;} 
mysqli_set_charset($conn, 'UTF8');

?>