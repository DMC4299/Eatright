<?php
include_once "../classes/user.php";
include_once "../classes/administrador.php";
session_start();
#Comprueba si el usuario es administrador
if (!isset($_SESSION['admin'])) {
    echo "<script>window.location.href='../index.php'</script>";
}
#Crea el objeto admin
$admin = unserialize($_SESSION['admin']);


if (isset($_POST['changepass'])) {
    $result = $admin->change_password($_POST['id'], $_POST['pass'], $_POST['pass_rep']);
    if ($result != 1) {
        echo $result;
    } else {
        echo "<script>alert('Se ha cambiado correctamente la contraseña.'); window.location.href='usuarios.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar-Contraseña</title>
    <link rel="stylesheet" href="menuAdmin.css">
    <link rel="stylesheet" href="editarAlimento.css">
</head>

<body>
    <?php
    include "menuAdmin.php";
    ?>
    <div class="container">
        <?php
        $formulario = "
        <form action='cambiarPass.php' method='post'>
        <p>ID del usuario: <input type='text' name='id' value='" . $_POST['id'] . "' readonly/></p>
        <p>Nueva contraseña: <input type='password' name='pass' required></p>
        <p>Repita la nueva contraseña:<input type='password' name='pass_rep' required></p>
        <button type='submit' name='changepass'>Cambiar contraseña</button>
        </form>";
        echo $formulario;
        ?>
    </div>
</body>

</html>