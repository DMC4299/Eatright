<?php
# Se incluyen los archivos y se inicia la sesion
include_once "../classes/alimentos.php";
include_once "../classes/user.php";
session_start();
# Si el usuario no existe entonce envia un mensaje
if (!isset($_SESSION['usuario'])) {
    echo json_encode("Debe iniciar sesion", JSON_UNESCAPED_UNICODE);
}
# Crea el objeto alimentos y el usuario con sus datos
$alimentos = new Alimentos();

$data = $alimentos->top_ten_favorites();


echo json_encode($data);