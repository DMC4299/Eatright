<?php
include_once "../classes/alimentos.php";
include_once "../classes/user.php";
session_start();
# Comprobacion de que el usuario ha inciado sesion y sino manda un mensaje y lo envia a lo manda al index
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Usuario no registrado, inicie sesion.');window.location.href='../index.php'</script>";
}
# Creamos el objeto usuario y obtenemos el id, creamos el objeto alimentos
$user = unserialize($_SESSION['usuario']);
$id_u = $user->getUser()['id'];
$alimentos = new Alimentos();
# Llamamos al metodo borrar favorito e introducimos el id del alimento y el id del alimento
$result = $alimentos->borrar_favorito($id_u, $_POST['id_alimento']);
# Si el resultado no es igual a 1 mandara un mensaje de error que tiene el metodo
if ($result != 1) {
    echo json_encode($result);
} else {
    # Sino mandara la nueva lista de los favoritos de los usuarios y la mandara
    $code = $alimentos->list_favorites($id_u);
    echo json_encode($code, JSON_UNESCAPED_UNICODE);
}
?>