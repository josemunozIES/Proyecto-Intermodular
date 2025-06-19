<?php
$host = "localhost"; 
$port = "5432"; 
$dbname = "Travel_Site_DataBase"; 
$user = "postgres"; 
$password = "4Tr0ll4cc0unt; 

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    echo "Error: Unable to connect to the database.\n";
} else{
   // echo "CONEXIÓN!";
}
?>