<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
$user = unserialize($_SESSION['usuario']);
include_once "../classes/alimentos.php";
$alimentos = new Alimentos();
?>
<div class="container">
    <div class="contenido1">
        <h3>Añadir alimento a favoritos</h3>
        <p>Buscar alimento: <input type="text" name="n_alimento" id="n_alimento" onchange="listFavorites('con1')" />
            <select name="a_marca" id="a_marca" onchange="listFavorites('con1')">
                <option value="hacendado" selected>Hacendado</option>
                <option value="aldi">Aldi</option>
                <option value="alcampo">Alcampo</option>
                <option value="dia">DIA</option>
                <option value="carrefour">Carrefour</option>
            </select>
        </p>
        <div class="con1" id="con1"></div>
        <a class="linea"><img src="../images/lineas.png" alt="lineas"></a>
    </div>
    <div class="contenido2" id="contenido2">
        <div class="my-favorites" id="my-favorites">
            <h3>Mis favoritos:</h3>
            <p>Buscar favorito: <input type="text" name="f_alimento" id="f_alimento" onchange="buscarFavoritos()" /></p>
            <p>Marca:
                <select name="f_marca" id="f_marca" onchange="buscarFavoritos()">
                    <option value="hacendado" selected>Hacendado</option>
                    <option value="aldi">Aldi</option>
                    <option value="alcampo">Alcampo</option>
                    <option value="dia">DIA</option>
                    <option value="carrefour">Carrefour</option>
                </select>
            </p>
            <div class="f-content" id="f-content">
                <ul>
                    <?php
                    $code = $alimentos->list_favorites($user->getUser()['id']);
                    echo $code;
                    ?>
                </ul>
            </div>
        </div>
        <div class="favorites" id="favorites">
            <div class="topten_favorites">
                <h3>Top 10 Favoritos</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Nombre alimento</th>
                            <th>Veces añadido</th>
                        </tr>
                    </thead>
                    <tbody id='list-toptenfavorites'>
                        <?php
                        $code = $alimentos->top_ten_favorites();
                        echo $code[0];
                        ?>
                    </tbody>
                </table>
            </div>
            <canvas id="topten-pie">
            </canvas>
            <?php
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.2/chart.min.js" integrity="sha512-dnUg2JxjlVoXHVdSMWDYm2Y5xcIrJg1N+juOuRi0yLVkku/g26rwHwysJDAMwahaDfRpr1AxFz43ktuMPr/l1A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
            echo "<script>graficaPie(" . json_encode($code[1]) . "," . json_encode($code[2]) . ",'topten-pie')</script>";
            ?>
        </div>
    </div>
</div>