<?php
include_once "../classes/alimentos.php";
include_once "../classes/user.php";
session_start();
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Usuario no registrado, inicie sesion.');window.location.href='../index.php'</script>";
}
# Creamos el objeto alimentos y el usuario
$alimentos = new Alimentos();
$user = unserialize($_SESSION['usuario']);
# Llamamos al metodo add favorite y le introducimos el id del usuario a traves del metodo getUser y el id del alimento
$add_favoritos = $alimentos->add_favorite($user->getUser()['id'], $_POST['id_alimento']);
# Si la respuesta no es igual a 1 mandara un mensaje de error que incluye el propio metodo
if ($add_favoritos != 1) {
    echo json_encode($add_favoritos);
} else {
    # Sino llamara al metodo list favoritos para recoger la nueva lista de favoritos del usuario
    $code = $alimentos->list_favorites($user->getUser()['id']);
    echo json_encode($code, JSON_UNESCAPED_UNICODE);
}
?>