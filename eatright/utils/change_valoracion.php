<?php
include_once "../classes/alimentos.php";
include_once "../classes/user.php";
session_start();
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Usuario no registrado, inicie sesion.');window.location.href='../index.php'</script>";
} else {
    $alimentos = new Alimentos();
    $user = unserialize($_SESSION['usuario']);
    $id_user = $user->getUser()['id'];
    $data = $alimentos->cambiar_valores($id_user, $_POST['id_alimen'], $_POST['puntuacion']);
    echo json_encode($data);
}
?>