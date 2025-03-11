<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if (isset($_SESSION['usuario'])) {
        ?>
        <link rel="stylesheet" href="./styles/index_user.css">
        <link rel="stylesheet" href="./styles/add_comida.css">
        <?php
    } else {
        ?>
        <link rel="stylesheet" href="./styles/index_otros.css">
        <?php
    }
    ?>
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/body.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <title>Eatright</title>
</head>
<body>
    <?php
    include './includes/header.php';
    include './pages/index_user.php';
    include "./includes/footer.php";
    ?>
</body>
</html>