<?php
#Iniciamos sesion y incluimos la clase user
session_start();
include_once "../classes/user.php";
#Si la session usuario existe nos devolvera al index
if (isset($_SESSION['usuario'])) {
    header("Location:../index.php");
}
#Si existe el envio introducira los parametros del usuario y la contraseña
#Llamara al metodo registro que se le pasaran los parametros faltantes
#Al final comprueba la variable registrar que si no es igual a 1 nos mostrara el mensaje de error
#En caso de que sea 1 nos mandara al login y nos dira que el registro se ha completado
if (isset($_POST['envio'])) {
    $user = new User($_POST['n_user'], $_POST['password']);
    $registrar = $user->registro($_POST['mail'], $_POST['r_password'], $_POST['sexo'], $_POST['nacimiento'], $_POST['peso'], 
    $_POST['altura'], $_POST['n_completo']);
    if ($registrar != 1) {
        echo "<script>alert('" . $registrar . "')</script>";
    }
    if ($registrar == 1) {
        echo "<script>alert('El registro se ha completado correctamente, puede iniciar sesion.')</script>";
        echo "<script>window.location.href = './login.php'</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <link rel="stylesheet" href="../styles/sign-in.css">
</head>
<body>
    <?php
    include_once("../includes/header.php");
    ?>
    <div id="container">
        <form action="sign-in.php" method="POST">
            <div>
                <label for="n_user">Nombre de usuario: </label><br>
                <input type="text" name="n_user" placeholder="Nuevo nombre de usuario" required />
            </div>
            <div>
                <label for="mail">Email: </label><br>
                <input type="email" name="mail" placeholder="Introduce tu email" required />
            </div>
            <div>
                <label for="password">Contraseña: </label><br>
                <input type="password" name="password" placeholder="Contraseña" minlength="8" />
            </div>
            <div>
                <label for="r_password">Repita la contraseña: </label><br>
                <input type="password" name="r_password" placeholder="Repita la contraseña" minlength="8" />
            </div>
            <div>
                <label for="sexo">Selecciona un sexo: </label><br>
                <table>
                    <tr class="bg">
                        <td><input type="radio" id="M" name="sexo" value="M" checked /><label for="M">Masculino</label>
                        </td>
                        <td><input type="radio" id="F" name="sexo" value="F" /><label for="F">Femenino</label></td>
                    </tr>
                </table>
            </div>
            <div>
                <label for="nacimiento">Fecha de nacimiento:</label><br>
                <input type="date" id="nacimiento" name="nacimiento" required />
            </div>
            <table>
                <tr>
                    <td>
                        <div>
                            <label for="peso">Peso: </label><br>
                            <input type="number" id="peso" name="peso" step="0.01" value="0" required />
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="altura">Altura: </label><br>
                            <input type="number" id="altura" name="altura" step="0.01" value="0" required />
                        </div>
                    </td>
                </tr>
            </table>
            <div>
                <label for="n_completo">Nombre completo: </label><br>
                <input type="text" id="n_completo" name="n_completo" placeholder="Introduce tu nombre" required />
            </div>
            <input type="submit" name="envio" id="envio" value="Enviar" />
        </form>
    </div>
    <?php
    include_once("../includes/footer.php");
    ?>
</body>
</html>