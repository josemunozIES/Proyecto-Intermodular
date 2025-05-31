<?php
$host = "localhost"; // <------ Configurar el local host con xampp
$port = "5432"; // <------- Puerto de entrada postgres
$dbname = "Tavel_Site_DB"; // <------- Nombre de la base de datos.
$user = "postgres"; // <------ Aqui tu nombre usuario, "postgres" es el normal predeterminado.
$password = " "; // <------ Aquí tu contraseña de postgres.

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    echo "Error: Unable to connect to the database.\n";
}
?>