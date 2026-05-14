<?php
// Configuración de los parámetros de conexión
$host = "db";          // Nombre del servicio de base de datos definido en docker-compose.yml [4]
$user = "root";        // Usuario por defecto de MariaDB
$password = "root";    // Contraseña configurada en tu entorno Docker
$database = "veterinaria"; // Nombre obligatorio de la base de datos [1]

// Crear la conexión utilizando la extensión mysqli
$conn = mysqli_connect($host, $user, $password, $database);

// Verificar si la conexión es exitosa [5]
if (!$conn) {
    die("Error de conexión con la base de datos: " . mysqli_connect_error());
}

// Establecer el conjunto de caracteres a UTF-8 para mostrar correctamente tildes y eñes
mysqli_set_charset($conn, "utf8");
?>