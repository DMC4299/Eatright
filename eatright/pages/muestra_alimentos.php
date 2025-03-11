<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <link rel="stylesheet" href="../styles/muestra_alimentos.css">
    <title>Alimentos de Eatright</title>
</head>
<body>
    <?php
    include_once("../includes/header.php");
    include_once "../classes/alimentos.php";
    $alimentos = new Alimentos();
    ?>
    <form action="muestra_alimentos.php" id="alimentos" method="GET">
        <fieldset>
            <legend>Ordenar por marca</legend>
            <label for="Hacendado"></label>
            <input type="checkbox" id="Hacendado" name="marca[]" value="Hacendado"> Hacendado
            <label for="Aldi"></label>
            <input type="checkbox" id="Aldi" name="marca[]" value="Aldi"> Aldi
            <label for="Alcampo"></label>
            <input type="checkbox" id="Alcampo" name="marca[]" value="Alcampo"> Alcampo
            <label for="DIA"></label>
            <input type="checkbox" id="DIA" name="marca[]" value="DIA"> DIA
            <label for="Carrefour"></label>
            <input type="checkbox" id="Carrefour" name="marca[]" value="Carrefour"> Carrefour
            <input type="submit" name="envio_orden" value="Buscar"> 
            <input type="reset" value="Limpiar filtros">
        </fieldset>
    </form>
    <?php
    if (isset($_GET['marca'])) {
        if (is_array($_GET['marca'])) {
            $alimentos->muestraAlimentos("'" . implode("','", $_GET['marca']) . "'");
        } else {
            $alimentos->muestraAlimentos($_GET['marca']);
        }
    } else {
        $alimentos->muestraAlimentos();
    }
    ?>
    <?php include_once "../includes/footer.php" ?>
</body>
</html>