<?php
session_start();
include_once "../classes/alimentos.php";
include_once "../classes/user.php";
# Comprueba si el usuario ha iniciado sesion, en caso de que no le devuelve a la pagina principal
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Usuario no registrado, inicie sesion.');window.location.href='../index.php'</script>";
}
# Crea el objeto alimentos y el usuario, obtienemos el id del usuario
$alimentos = new Alimentos();
$user = unserialize($_SESSION['usuario']);
$id_u = $user->getUser()['id'];
# Buscamos los favoritos del usuario 
$code = $alimentos->buscar_favoritos($_POST['name_ali'], $_POST['marca'], $id_u);
# En caso de que devuelva 0 nos mostrara un mensaje de que no se ha encontrado ningun favorito
if ($code === 0) {
    echo json_encode("No se ha encontrado ningun favorito.");
    # Sino mostrara el codigo
} else {
    echo json_encode($code);
}
?>