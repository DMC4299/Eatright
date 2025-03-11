<?php
session_start();
require_once('../classes/alimentos.php');
require_once "../classes/user.php";
#Comprueba si el usuario ha iniciado sesion
if (!isset($_SESSION['usuario'])) {
    header("Location: ./login.php");
    exit();
}
#Comprueba si se le ha dado al boton send_food
if (!isset($_POST['send_food'])) {
    header("Location: ./index.php");
    exit();
}
#Se crea el objeto alimento
$alimento = new Alimentos();
#Se convierte la session a un objeto con unserialize
$user = unserialize($_SESSION['usuario']);
#Se usar el metodo get user para obtener los datos del usuario
$data = $user->getUser();
#Para registrar la comida se introducen los parametros del id del usuario, id del alimento, la fecha, la porcion del usuario y la comida
$comp = $alimento->registrar_comida($data['id'], $_POST['id_alimen'], $_POST['fecha'], $_POST['uporcion'], $_POST['comidas']);
#Si lo que devuelve no es un 1 significa que ha habido un problema
if ($comp != 1) {
    echo "<script>alert('" . $comp . "')</script>";
    echo "<script>window.location.href = '../index.php'</script>";
    exit;
}
#Si devuelve 1 significa que todo ha salido correctamente
echo "<script>alert('Se ha registrado la comida correctamente.')</script>";
echo "<script>window.location.href = '../index.php'</script>";
exit;
?>