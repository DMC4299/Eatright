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
    $data = $alimentos->alimentos_valorados($id_user, $_POST['n_alimen'], $_POST['marca']);
    $code = "";
    if ($data != 0) {
        $code .= "<ul>";
        for ($i = 0; $i < count($data); $i++) {
            $alimento = $alimentos->data_Alimento($data[$i][1]);
            $code .= "<li><span>" . $alimento[1] . "(" . $alimento[2] . "): " . $data[$i][2] . " puntos</span><div class='options'><a onclick='eliminarValoracion(" . json_encode($alimento[0]) . ")' href='#'><img src='../images/eliminar.png'/></a><a href='#' onclick='changeValoracion(" . json_encode($data[$i][1]) . "," . json_encode($alimento[1]) . "," . json_encode($data[$i][2]) . ")'><img src='../images/editar.png'/></a></div></li>";
        }
        $code .= "</ul>";
    } else {
        $code .= "<p>No ha valorado ningun alimento</p>";
    }
    echo json_encode($code);
}
?>