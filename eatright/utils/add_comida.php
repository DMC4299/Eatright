<?php
# Se incluyen los archivos y se inicia la sesion
include_once "../classes/alimentos.php";
include_once "../classes/user.php";
session_start();
# Si no existe una sesion llamada usuario entonces nos llevara a la pagina del index
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Debe iniciar sesion.'); window.location.href='../index.php'</script>";
}
# Crea el objeto alimentos, el usuario y obtiene su id
$alimentos = new Alimentos();
$user = unserialize($_SESSION['usuario']);
$id_u = $user->getUser()['id'];
#Comprueba si el check fav esta en falso, en ese caso usara buscar alimentos y en caso de que sea verdadero usara buscar favoritos
if ($_POST['check_fav'] == 'f') {
    $result = $alimentos->buscar_alimentos($_POST['n_alimento'], $_POST['a_marca']);
} else {
    $result = $alimentos->buscar_favoritos($_POST['n_alimento'], $_POST['a_marca'], $id_u, true);
}
# Si el resultado devuelve 0 mostrara un contenedor que dira que no se ha encontrado ningun alimento
if ($result === 0) {
    $code = "<div id='c1'>No se ha encontrado ningun alimento</div>";
} else {
    # Sino creara un contenedor con una lista y se añadiran los elementos a la lista html segun si son favoritos o no
    $code = "<div id='c1'><ul>";
    if ($_POST['check_fav'] == 'f') {
        for ($i = 0; $i < sizeof($result); $i++) {
            $r = json_encode($result[$i]);
            $text = $result[$i]['marca'] . ", " . $result[$i]['porcion'] . " (gr o ml), " . $result[$i]['kcal'] . "kcal";
            $code .= "<li onclick='muestraAlimentos($r)'><a href='#c1'>" . $result[$i]['nombre'] . "</a><p>" . $text . "</p></li>";
        }
    } else {
        $code .= $result;
    }
    $code .= "</ul>";
}
# Crea otro contenedor
$code .= "</div><div id='c2'></div>";
# Devuelve todo el codigo
echo json_encode($code, JSON_UNESCAPED_UNICODE);
?>