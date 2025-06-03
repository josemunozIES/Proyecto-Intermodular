<?php
$host = "localhost"; 
$port = "5433"; 
$dbname = "Travel_Site_DataBase"; 
$user = "postgres"; 
$password = "1234567A.8"; 

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    echo "Error: Unable to connect to the database.\n";
} else{
   // echo "CONEXIÓN!";
}
?>