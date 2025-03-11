<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoritos</title>
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <link rel="stylesheet" href="../styles/favoritos.css">
</head>
<body>
    <script src="../js/formComida.js"></script>
    <?php
    include "../includes/header.php";
    include "./view_favoritos.php";
    include "../includes/footer.php";
    ?>
</body>
</html>