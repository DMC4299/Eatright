<?php
#Url de la clase alimentos
include_once(__DIR__ . "/../classes/alimentos.php");
#Comprueba si existe la session usuario
if (isset($_SESSION['usuario'])) {
    #Trae los datos del usuario y crea un objeto llamado alimentos
    $user = unserialize($_SESSION['usuario']);
    $datos = $user->getUser();
    $alimentos = new Alimentos();
    #Fecha actual
    $fecha_actual = date("Y-m-d");
    #Llamada al metodo get_comidas que se le pasa por parametro el id del usuario con un condicional ternario si existe la fecha usa esa fecha sino usa la fecha actual
    $comidas = $alimentos->get_comidas($datos['id'], (isset($_GET['fecha'])) ? $_GET['fecha'] : $fecha_actual);
    #Llamada al metodo total_diario que se le pasa por parametro el id del usuario con un condicional ternario si existe la fecha usa esa fecha sino usa la fecha actual
    $r_diario = $alimentos->total_diario($datos['id'], (isset($_GET['fecha'])) ? $_GET['fecha'] : $fecha_actual, $comidas);
    ?>
    <div class="content">
    <div class="linea1">
        <a><img src="./images/lineas.png" alt="lineas"></a>
    </div>
        <div class="fecha">
            <!--
                Crea dos flechas, una para restar un dia y otra para añadir un dia, y en el calendario introduce la fecha que haya elegido el usuario o la actual    
            !-->
            <a
                href="../index.php?fecha=<?php echo isset($_GET['fecha']) ? $fecha = date("Y-m-d", strtotime($_GET['fecha'] . " -1 day")) : $fecha = date("Y-m-d", strtotime($fecha_actual . " -1 day")); ?>">
                <img alt="left" src="./images/flecha-izquierda.png" />
            </a>
            <input type="date" id="calendar" name="calendar" onchange="cambiaFecha(event)"
                value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : $fecha_actual ?>">
            <a
                href="../index.php?fecha=<?php echo isset($_GET['fecha']) ? $fecha = date("Y-m-d", strtotime($_GET['fecha'] . " +1 day")) : $fecha = date("Y-m-d", strtotime($fecha_actual . " +1 day")); ?>">
                <img alt="left" src="./images/flecha-derecha.png" />
            </a>
        </div>
        <script src="./js/changeDate.js"></script>
        <div class="r-comidas">
            <div class="comida">
                <div class="header-comida">
                    <h2>Desayuno</h2>
                    <div class="valores">
                        <p>Calorias (kcal)</p>
                        <p>Carbohidratos</p>
                        <p>Grasas</p>
                        <p>Grasas Saturadas</p>
                        <p>Azucar</p>
                        <p>Proteina</p>
                        <p>Sal</p>
                    </div>
                </div>
                <?php
                #Si hay desayuno muestra los datos y sino solo muestra añadir alimento
                if (isset($comidas['desayuno'])) {
                    $r = $alimentos->get_comida($comidas['desayuno']);
                    echo $r;
                } else {
                    echo "<a href='#container2' onclick='addComida()' class='add_alimen'>Añadir alimento</a>";
                    echo "<hr></hr>";
                }
                ?>
            </div>
            <div class="comida">
                <div class="header-comida">
                    <h2>Almuerzo</h2>
                </div>
                <?php
                #Si hay almuerzo muestra los datos y sino solo muestra añadir alimento            
                if (isset($comidas['almuerzo'])) {
                    $r = $alimentos->get_comida($comidas['almuerzo']);
                    echo $r;
                } else {
                    echo "<a href='#container2' onclick='addComida()' class='add_alimen'>Añadir alimento</a>";
                    echo "<hr></hr>";
                }
                ?>
            </div>
            <div class="comida">
                <div class="header-comida">
                    <h2>Comida</h2>
                </div>
                <?php
                #Si hay comida muestra los datos y sino solo muestra añadir alimento            
                if (isset($comidas['comida'])) {
                    $r = $alimentos->get_comida($comidas['comida']);
                    echo $r;
                } else {
                    echo "<a href='#container2' onclick='addComida()' class='add_alimen'>Añadir alimento</a>";
                    echo "<hr></hr>";
                }
                ?>
            </div>
            <div class="comida">
                <div class="header-comida">
                    <h2>Merienda</h2>
                </div>
                <?php
                #Si hay merienda muestra los datos y sino solo muestra añadir alimento            
                if (isset($comidas['merienda'])) {
                    $r = $alimentos->get_comida($comidas['merienda']);
                    echo $r;
                } else {
                    echo "<a href='#container2' onclick='addComida()' class='add_alimen'>Añadir alimento</a>";
                    echo "<hr></hr>";
                }
                ?>
            </div>
            <div class="comida">
                <div class="header-comida">
                    <h2>Cena</h2>
                </div>
                <?php
                #Si hay cena muestra los datos y sino solo muestra añadir alimento            
                if (isset($comidas['cena'])) {
                    $r = $alimentos->get_comida($comidas['cena']);
                    echo $r;
                } else {
                    echo "<a href='#container2' onclick='addComida()' class='add_alimen'>Añadir alimento</a>";
                    echo "<hr></hr>";
                }
                ?>
                <div class="resumen-diario">
                    <h2>Resumen Diario</h2>
                    <div class="valores-comida">
                        <?php
                        #Si el resumen diario no devuelve 0 mostrara los datos con el resumen diario
                        if ($r_diario != 0) {
                            echo "
                        <p>" . $r_diario[0]['calc_kcal'] . "</p>
                        <p>" . $r_diario[0]['calc_carbos'] . "</p>
                        <p>" . $r_diario[0]['calc_grasas'] . "</p>
                        <p>" . $r_diario[0]['calc_saturadas'] . "</p>
                        <p>" . $r_diario[0]['calc_azucar'] . "</p>
                        <p>" . $r_diario[0]['calc_proteina'] . "</p>
                        <p>" . $r_diario[0]['calc_sal'] . "</p>
                        ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container2" id="container2">
        </div>
        <script src="./js/formComida.js"></script>
        <a id="back-top" href="#"><img src="./images/flecha-hacia-arriba-sobre-un-fondo-de-circulo-negro.png"></a>
    <div class="linea1">
        <a><img src="./images/lineas.png" alt="lineas"></a>
    </div>    
    </div>
    <?php
} else {
    #Si el usuario no ha iniciado sesion mostrara el index de otros
    include_once(__DIR__ . "/index_otros.html");
}
?>