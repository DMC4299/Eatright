<?php
session_start();
include_once "../classes/alimentos.php";
include_once "../classes/user.php";
$alimentos = new Alimentos();
#Comprueba si le ha dado a la imagen de borrar comida y si el usuario ha iniciado sesion, sino lo llevara de nuevo al index
if (isset($_POST['borrar_x']) && isset($_POST['borrar_y']) && isset($_SESSION['usuario'])) {
    $user = unserialize($_SESSION['usuario']);
    $data_user = $user->getUser();
    #Llama al metodo checkComida y luego borra la comida
    echo $alimentos->checkComida($data_user['id'], $_POST['id_comida']);
} else {
    header("Location:../index.php");
    exit;
}
?>