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
$user = unserialize($_SESSION['usuario']);
# Llamamos a la funcion para ver la lista de los alimentos no agregados a favoritos por el usuario y se le pasa como parametro el nombre del alimento que queremos buscar
# Marca y el id del usuario 
$result = $alimentos->list_notfavorites($_POST['n_alimento'], $_POST['a_marca'], $user->getUser()['id']);
# Si el resultado es igual a 0 devuelve un contenedor con un mensaje de que no se ha encontrado nada
if ($result === 0) {
    $code = "<div id='c1'>No se ha encontrado ningun alimento</div>";
} else {
    # Sino se crea un contenedor que contiene la lista de los alimentos no añadidos a favoritos, que al hacerles click en la estrella se añadiran a favoritos
    $code = "<div id='c1'><ul>";
    for ($i = 0; $i < sizeof($result); $i++) {
        $r = json_encode($result[$i]);
        $text = $result[$i]['marca'] . ", " . $result[$i]['porcion'] . " (gr o ml), " . $result[$i]['kcal'] . "kcal";
        $code .= "<li><a href='#'>" . $result[$i]['nombre'] . "</a><div class='favorite-content'><p>" . $text . "</p><span onclick='addFavorites($r)'><a href='#my-favorites'><img src='../images/estrella.png' alt='estrella'/></a></span></div></li>";
    }
    $code .= "</ul>";
}
# Devuelve el codigo
echo json_encode($code, JSON_UNESCAPED_UNICODE);
?>