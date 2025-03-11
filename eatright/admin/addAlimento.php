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
if (isset($_POST['add_alimento'])) {
    $result = $admin->add_alimento($_POST['nombre'], $_POST['marca'], $_POST['porcion'], $_POST['kcal'], $_POST['grasas'], $_POST['g_saturadas'], $_POST['carbohidratos'], $_POST['azucar'], $_POST['proteina'], $_POST['sal']);
    if ($result == 0) {
        echo "<script>alert('Ha ocurrido un error al intentar añadir el alimento.');</script>";
    } else {
        echo "<script>alert('Se ha añadido el alimento correctamente.'); window.location.href='admin.php'</script>";
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
        <form action='addAlimento.php' method='post'>
        <p>Nombre alimento: <input type='text' name='nombre' required></p>
        <p>Marca:                 <select name='marca'>
        <option value='hacendado' selected>Hacendado</option>
        <option value='aldi'>Aldi</option>
        <option value='alcampo'>Alcampo</option>
        <option value='dia'>DIA</option>
        <option value='carrefour'>Carrefour</option>
    </select></p>
        <p>Porcion: <input type='number' step='0.01' name='porcion' value='0'></p>
        <p>Kcal: <input type='number' step='0.01' name='kcal' value='0'></p>
        <p>Grasas: <input type='number' step='0.01' name='grasas' value='0'></p>
        <p>Grasas Saturadas: <input type='number' step='0.01' name='g_saturadas' value='0'></p>
        <p>Carbohidratos: <input type='number' step='0.01' name='carbohidratos' value='0'></p>
        <p>Azúcar: <input type='number' step='0.01' name='azucar' value='0'></p>
        <p>Proteina: <input type='number' step='0.01' name='proteina' value='0'></p>
        <p>Sal: <input type='number' step='0.01' name='sal' value='0'></p>
        <button type='submit' name='add_alimento'>Añadir Alimento</button>
        </form>";
        echo $formulario;
        ?>
    </div>
</body>
</html>