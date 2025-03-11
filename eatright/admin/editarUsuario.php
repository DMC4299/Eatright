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
#Si el usuario le da a el boton de editar llamara al metodo para editarlo
if (isset($_POST['edita_usuario'])) {
    $result = $admin->editar_usuario($_POST['id_cli'], $_POST['nombre'], $_POST['email'], $_POST['sexo'], $_POST['f_nacimiento'], $_POST['peso'], $_POST['altura'], $_POST['n_completo'], $_POST['estado'], $_POST['intentos']);
    if ($result != 1) {
        echo $result;
    } else {


        echo "<script>alert('El usuario se ha modificado correctamente.'); window.location.href='usuarios.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar-Usuario</title>
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
                <form action='editarUsuario.php' method='post'>
                    <p>ID: <input type='text' name='id_cli' readonly value='" . $_POST['id'] . "'></p>
                    <p>Nombre de usuario: <input type='text' name='nombre' value='" . $_POST['n_user'] . "'></p>
                    <p>Email: <input type='email' name='email' value='" . $_POST['email'] . "'></p>
                    <p>Sexo: <input type='text' name='sexo' value='" . $_POST['sexo'] . "'></p>
                    <p>Fecha de nacimiento: <input type='date' name='f_nacimiento' value='" . $_POST['f_nacimiento'] . "'></p>
                    <p>Peso: <input type='number' step='0.01' name='peso' value='" . $_POST['peso'] . "'></p>
                    <p>Altura: <input type='number' step='0.01' name='altura' value='" . $_POST['altura'] . "'></p>
                    <p>Nombre completo: <input type='text' name='n_completo' value='" . $_POST['n_completo'] . "'></p>
                    <p>Estado: <input type='text' name='estado' value='" . $_POST['estado'] . "'></p>
                    <p>Intentos: <input type='number' name='intentos' value='" . $_POST['intentos'] . "'></p>
                    <button type='submit' name='edita_usuario'>Editar</button>
                </form>";
        echo $formulario;
        ?>
    </div>
</body>

</html>