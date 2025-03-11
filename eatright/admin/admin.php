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
if (isset($_POST['eliminar'])) {
    $result = $admin->eliminar_alimento($_POST['id']);
    if ($result == 0) {
        echo "<script>alert('Ha ocurrido un error al intentar eliminar, intentelo de nuevo.');</script>";
    } else {
        echo "<script>alert('Se ha eliminado el alimento correctamente.'); window.location.href='admin.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador-Alimentos</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="menuAdmin.css">
</head>
<body>
    <?php
    include "menuAdmin.php";
    ?>
    <div class="container">
        <h3>Buscar alimento</h3>
        <form action="admin.php" method="POST">
            <p>Nombre del alimento: <input type="text" name='n_alimento' />
                <select name="marca">
                    <option value="hacendado" selected>Hacendado</option>
                    <option value="aldi">Aldi</option>
                    <option value="alcampo">Alcampo</option>
                    <option value="dia">DIA</option>
                    <option value="carrefour">Carrefour</option>
                </select>
                <input type="submit" value='Buscar' /> <a href='addAlimento.php'>Añadir Alimento</a>
            </p>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Porcion</th>
                    <th>Kcal</th>
                    <th>Grasas</th>
                    <th>Grasas Saturadas</th>
                    <th>Carbohidratos</th>
                    <th>Azucar</th>
                    <th>Proteina</th>
                    <th>Sal</th>
                    <th>Calidad</th>
                    <th>Valoracion</th>
                </tr>
            </thead>
            <?php
            $marca = isset($_POST['marca']) ? $_POST['marca'] : '';
            $n_alimento = isset($_POST['n_alimento']) ? $_POST['n_alimento'] : '';
            $data = $admin->alimentos($marca, $n_alimento);
            if ($data != 0) {
                $code = "";
                for ($i = 0; $i < count($data); $i++) {
                    $code .= "<tr>";
                    $row = "<td>" . $data[$i]['id_alimento'] . "</td>" . "<td>" . $data[$i]['nombre_alimen'] . "</td>" . "<td>" . $data[$i]['marca'] . "</td>" . "<td>" . $data[$i]['porcion'] . "</td>" . "<td>" . $data[$i]['kcal'] . "</td>" . "<td>" . $data[$i]['grasas'] . "</td>" . "<td>" . $data[$i]['g_saturadas'] . "</td>" . "<td>" . $data[$i]['carbohidratos'] . "</td>" . "<td>" . $data[$i]['azucar'] . "</td>" . "<td>" . $data[$i]['proteina'] . "</td>" . "<td>" . $data[$i]['sal'] . "</td>" . "<td>" . $data[$i]['calidad'] . "</td>" . "<td>" . $data[$i]['valoracion'] . "</td>";
                    $row .= "<td><form action='editarAlimento.php' method='post'>
                    <input type='hidden' name='id' value='" . $data[$i]['id_alimento'] . "'>
                    <input type='hidden' name='nombre' value='" . $data[$i]['nombre_alimen'] . "'>
                    <input type='hidden' name='marca' value='" . $data[$i]['marca'] . "'>
                    <input type='hidden' name='porcion' value='" . $data[$i]['porcion'] . "'>
                    <input type='hidden' name='kcal' value='" . $data[$i]['kcal'] . "'>
                    <input type='hidden' name='grasas' value='" . $data[$i]['grasas'] . "'>
                    <input type='hidden' name='g_saturadas' value='" . $data[$i]['g_saturadas'] . "'>
                    <input type='hidden' name='carbohidratos' value='" . $data[$i]['carbohidratos'] . "'>
                    <input type='hidden' name='azucar' value='" . $data[$i]['azucar'] . "'>
                    <input type='hidden' name='proteina' value='" . $data[$i]['proteina'] . "'>
                    <input type='hidden' name='sal' value='" . $data[$i]['sal'] . "'>
                    <button type='submit'>Editar</button>
                </form></td>";
                    $row .= "<td><form action='admin.php' method='post'> <input type='hidden' name='id' value='" . $data[$i]['id_alimento'] . "'> <button type='submit' name='eliminar'>Eliminar</button></form></td>";
                    $code .= $row;
                    $code .= "</tr>";
                }
                echo $code;
            } else {
                echo "<script>alert('No se ha encontrado ningun alimento.')</script>";
            }
            ?>
        </table>
    </div>
</body>
</html>