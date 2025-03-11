<?php
include_once("../classes/alimentos.php");
# Comprueba no existe el metodo post changePorcion y en este caso nos manda al index
if (!isset($_POST['changePorcion'])) {
    echo "<script>window.location.href='../index.php'</script>";
}
# Creamos el objeto alimentos
$alimentos = new Alimentos();
# Imprimimos el metodo change_comida
echo $alimentos->change_comida($_POST['id_comida'], $_POST['p_u']);
?>