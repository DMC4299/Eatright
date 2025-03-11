<?php
# Creacion de la funcion para conectar con la base de datos
function conectarDB()
{
    # Archivo donde se encuentran los datos de configuracion
    $file = __DIR__ . "/config.json";
    # Decodificamos el json con los datos y con el true lo metemos en un array asociativo 
    $config = json_decode(file_get_contents($file), true);
    # Ponemos el nombre de usuario de nuestra base de datos
    # Contraseña y el nombre de la base de datos
    $username = $config['username'];
    $password = $config['password'];
    $database = $config['database'];
    # Creamos la conexión poniendo todos los datos incluyendo nuestro hosting
    $mysqli = new mysqli("localhost", $username, $password, $database) or die("No se ha podido conectar con la base de datos");
    return $mysqli;
}
?>