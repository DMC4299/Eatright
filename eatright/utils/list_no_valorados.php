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
    $data = $alimentos->alimentos_novals($id_user, $_POST['n_alimen'], $_POST['marca']);
    if ($data != 0) {
        $code = "<ul>";
        for ($i = 0; $i < count($data); $i++) {
            $text = $data[$i][2] . ", " . $data[$i][3] . " (gr o ml), " . $data[$i][4] . "kcal";
            $code .= "<li onclick='addValoracion(" . json_encode($data[$i][0]) . "," . json_encode($data[$i][1]) . ")'><a href='#my-vals'>" . $data[$i][1] . "</a><p>" . $text . "</p></li>";
        }
        $code .= "</ul>";
    } else {
        $code = "<p>No se ha encontrado ningun alimento para valorar</p>";
    }
    echo json_encode($code);
}
?>