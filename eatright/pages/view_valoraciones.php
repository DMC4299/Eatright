<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
$user = unserialize($_SESSION['usuario']);
include_once "../classes/alimentos.php";
$alimentos = new Alimentos();
$id_u = $user->getUser()['id'];
?>
<div class="container">
    <div class="contenido1">
        <h3>Añadir valoracion a un alimento</h3>
        <p>Buscar alimento: <input type="text" name="n_alimento" id="n_alimento" onchange="noValorados('con1')" />
            <select name="a_marca" id="a_marca" onchange="noValorados('con1')">
                <option value="hacendado" selected>Hacendado</option>
                <option value="aldi">Aldi</option>
                <option value="alcampo">Alcampo</option>
                <option value="dia">DIA</option>
                <option value="carrefour">Carrefour</option>
            </select>
        </p>
        <?php
        $data = $alimentos->alimentos_novals($id_u, '', 'hacendado');
        if ($data != 0) {
            $code = "<div id='con1' class='con1'><ul>";
            for ($i = 0; $i < count($data); $i++) {
                $text = $data[$i][2] . ", " . $data[$i][3] . " (gr o ml), " . $data[$i][4] . "kcal";
                $code .= "<li onclick='addValoracion(" . json_encode($data[$i][0]) . "," . json_encode($data[$i][1]) . ")'><a href='#my-vals'>" . $data[$i][1] . "</a><p>" . $text . "</p></li>";
            }
            $code .= "</ul></div>";
        } else {
            $code = "<div class='con1'><p>No se ha encontrado ningun alimento para valorar</p></div>";
        }
        echo $code;
        ?>
    </div>
    <div class="contenido2" id="contenido2">
        <div class="my-vals" id="my-vals">
            <h3>Mis valoraciones:</h3>
            <p>Buscar valoracion: <input type="text" name="v_alimento" id="v_alimento"
                    onchange="valorados('v-content')" />
            </p>
            <p>Marca:
                <select name="v_marca" id="v_marca" onchange="valorados('v-content')">
                    <option value="hacendado" selected>Hacendado</option>
                    <option value="aldi">Aldi</option>
                    <option value="alcampo">Alcampo</option>
                    <option value="dia">DIA</option>
                    <option value="carrefour">Carrefour</option>
                </select>
            </p>
            <div class="v-content" id="v-content">
                <?php
                $data = $alimentos->alimentos_valorados($id_u);
                $code = "";
                if ($data != 0) {
                    $code .= "<ul>";
                    for ($i = 0; $i < count($data); $i++) {
                        $alimento = $alimentos->data_Alimento($data[$i][1]);
                        $code .= "<li><span>" . $alimento[1] . "(" . $alimento[2] . "): " . $data[$i][2] . " puntos</span><div class='options'><a onclick='eliminarValoracion(" . $alimento[0] . ")' href='#'><img src='../images/eliminar.png'/></a><a href='#' onclick='changeValoracion(" . json_encode($data[$i][1]) . "," . json_encode($alimento[1]) . "," . json_encode($data[$i][2]) . ")'><img src='../images/editar.png'/></a></div></li>";
                    }
                    $code .= "</ul>";
                } else {
                    $code .= "<p>No ha valorado ningun alimento</p>";
                }
                echo $code;
                ?>
            </div>
        </div>
        <div class="valoraciones" id="valoraciones">
            <div class="topten_valoraciones">
                <h3>Top 10 Valoraciones</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Nombre alimento</th>
                            <th>Puntuacion Media</th>
                        </tr>
                    </thead>
                    <tbody id="list-toptenvaloraciones">
                        <?php
                        $code = $alimentos->top_ten_valoracion();
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