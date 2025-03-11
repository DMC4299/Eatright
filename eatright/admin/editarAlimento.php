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
if (isset($_POST['edita_alimento'])) {
    $result = $admin->editarAlimento($_POST['id'], $_POST['nombre'], $_POST['marca'], $_POST['porcion'], $_POST['kcal'], $_POST['grasas'], $_POST['g_saturadas'], $_POST['carbohidratos'], $_POST['azucar'], $_POST['proteina'], $_POST['sal']);
    if ($result == 0) {
        echo "<script>alert('Ha ocurrido un error, intentelo de nuevo.');</script>";
    } else {
        echo "<script>alert('Se han hecho los cambios correctamente.'); window.location.href='admin.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar-Alimento</title>
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
                <form action='editarAlimento.php' method='post'>
                    <p>ID: <input type='text' name='id' readonly value='" . $_POST['id'] . "'></p>
                    <p>Nombre alimento: <input type='text' name='nombre' value='" . $_POST['nombre'] . "'></p>
                    <p>Marca: <input type='text' name='marca' value='" . $_POST['marca'] . "'></p>
                    <p>Porcion: <input type='number' step='0.01' name='porcion' value='" . $_POST['porcion'] . "'></p>
                    <p>Kcal: <input type='number' step='0.01' name='kcal' value='" . $_POST['kcal'] . "'></p>
                    <p>Grasas: <input type='number' step='0.01' name='grasas' value='" . $_POST['grasas'] . "'></p>
                    <p>Grasas Saturadas: <input type='number' step='0.01' name='g_saturadas' value='" . $_POST['g_saturadas'] . "'></p>
                    <p>Carbohidratos: <input type='number' step='0.01' name='carbohidratos' value='" . $_POST['carbohidratos'] . "'></p>
                    <p>Azúcar: <input type='number' step='0.01' name='azucar' value='" . $_POST['azucar'] . "'></p>
                    <p>Proteina: <input type='number' step='0.01' name='proteina' value='" . $_POST['proteina'] . "'></p>
                    <p>Sal: <input type='number' step='0.01' name='sal' value='" . $_POST['sal'] . "'></p>
                    <button type='submit' name='edita_alimento'>Editar</button>
                </form>";
        echo $formulario;
        ?>
    </div>
</body>
</html>