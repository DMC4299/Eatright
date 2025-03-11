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
#Si el usuario le da a eliminar eliminara el usuario seleccionado
if (isset($_POST['eliminar'])) {
    $result = $admin->eliminar_usuario($_POST['id_cli']);
    if ($result != 1) {
        echo $result;
    } else {
        echo "<script>alert('El usuario se ha eliminado correctamente.')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador-Usuarios</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="menuAdmin.css">
</head>

<body>
    <?php
    include "menuAdmin.php";
    ?>
    <div class="container">
        <h3>Buscar usuarios</h3>
        <form action="usuarios.php" method="POST">
            <p>Nombre del usuario: <input type="text" name='n_user' /></p>
            <p>Email: <input type="text" name='email' /> <input type="submit" value='Buscar' /> <a
                    href="registrarUsuario.php">Crear usuario</a></p>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Sexo</th>
                    <th>Fecha de nacimiento</th>
                    <th>Peso</th>
                    <th>Altura</th>
                    <th>Nombre completo</th>
                    <th>Estado</th>
                    <th>Intentos</th>
                </tr>
            </thead>
            <?php
            $n_user = isset($_POST['n_user']) ? $_POST['n_user'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $data = $admin->view_clientes($n_user, $email);
            if ($data != 0) {
                $code = "";
                for ($i = 0; $i < count($data); $i++) {
                    $code .= "<tr>";
                    $code .= "<td>" . $data[$i]['id_cli'] . "</td>" . "<td>" . $data[$i]['n_user'] . "</td>" . "<td>" . $data[$i]['email'] . "</td>" . "<td>" . $data[$i]['sexo'] . "</td>" . "<td>" . $data[$i]['f_cumple'] . "</td>" . "<td>" . $data[$i]['peso'] . "</td>" . "<td>" . $data[$i]['altura'] . "</td>" . "<td>" . $data[$i]['nombre_completo'] . "</td>" . "<td>" . $data[$i]['estado'] . "</td>" . "<td>" . $data[$i]['intentos'] . "</td>";
                    $code .= "<td>
                    <form action='editarUsuario.php' method='POST'>
                    <input type='hidden' name='id' value='" . $data[$i]['id_cli'] . "'>
                    <input type='hidden' name='n_user' value='" . $data[$i]['n_user'] . "'>
                    <input type='hidden' name='email' value='" . $data[$i]['email'] . "'>
                    <input type='hidden' name='sexo' value='" . $data[$i]['sexo'] . "'>
                    <input type='hidden' name='f_nacimiento' value='" . $data[$i]['f_cumple'] . "'>
                    <input type='hidden' name='peso' value='" . $data[$i]['peso'] . "'>
                    <input type='hidden' name='altura' value='" . $data[$i]['altura'] . "'>
                    <input type='hidden' name='n_completo' value='" . $data[$i]['nombre_completo'] . "'>
                    <input type='hidden' name='estado' value='" . $data[$i]['estado'] . "'>
                    <input type='hidden' name='intentos' value='" . $data[$i]['intentos'] . "'>
                    <button type='submit'>Editar</button>
                    </form></td>";
                    $code .= "<td><form action='cambiarPass.php' method='post'>
                        <input type='hidden' name='id' value='" . $data[$i]['id_cli'] . "'>
                        <button type='submit'>Cambiar contraseña</button>
                    </form></td>";

                    $code .= "<td><form action='usuarios.php' method='post'> <input type='hidden' name='id_cli' value='" . $data[$i]['id_cli'] . "'> <button type='submit' name='eliminar'>Eliminar</button></form></td>";

                    $code .= "</tr>";
                }
                echo $code;
            } else {
                echo "<script>alert('No se ha encontrado ningun usuario.')</script>";
            }
            ?>
        </table>
    </div>
</body>

</html>