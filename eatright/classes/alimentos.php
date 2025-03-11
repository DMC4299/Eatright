<?php
#Ruta del archivo de la conexion
include_once(__DIR__ . "/../config/connect.php");
#Clase Alimentos
class Alimentos
{
    #Metodo privado que regresa el total de registros de una consulta
    private function numeroAlimentos($i_sql)
    {
        $connect = conectarDB();
        $sql = $i_sql;
        $result = $connect->query($i_sql);
        $row = $result->fetch_assoc();
        mysqli_close($connect);
        return $row["total"];
    }
    #Metodo que muestra todos los alimentos segun la marca
    function muestraAlimentos($marca = '')
    {
        #Comprueba si existe una pagina y en caso de que sea la primera lleva a la pagina
        #En caso de que no introduce por el metodo get la pagina elegida por el usuario
        if (isset($_GET["pagina"])) {
            if ($_GET["pagina"] == 1) {
                header("Location:muestra_alimentos.php");
                exit();
            } else {
                $pagina = $_GET["pagina"];
            }
            #Si no existe ninguna pagina devuelve a la primera pagina
        } else {
            $pagina = 1;
        }
        #Crea la consulta sql y cuentasql tambien para saber el numero de registros
        $sql = "SELECT * FROM alimentos WHERE 1=1";
        $cuentasql = "SELECT COUNT(*) AS total FROM alimentos WHERE 1=1";
        #Si el parametro marca no esta vacio añade la marca a las dos consultas anteriores
        if ($marca != '') {
            $sql .= " AND marca IN ($marca)";
            $cuentasql .= " AND marca IN ($marca)";
        }
        #El tamaño que va a mostrar cada vez va a ser de 10 en 10 alimentos y para saber desde donde empieza le resta 1 a la pagina y lo multiplica por el numero de alimentos que queramos mostrar
        $tamano_pag = 10;
        $empieza_desde = ($pagina - 1) * $tamano_pag;
        #Comprueba el numero total de registros y con el ceil hace la division para sacar el numero total de paginas
        $n_registros = $this->numeroAlimentos($cuentasql);
        $total_paginas = ceil($n_registros / $tamano_pag);
        #En el limit se pone desde donde empieza y el numero de registros que queremos
        $sql .= " LIMIT $empieza_desde,$tamano_pag";
        #Se crea la conexion con la base de datos, se obtiene los resultados y se cierra la conexion
        $connect = conectarDB();
        $result = $connect->query($sql);
        mysqli_close($connect);
        #Se mete los indices de la tabla con los datos de los alimentos
        $code = "<table>\n<tr class='indice'>\n<td class='borde1'>Nombre</td>\n<td>Marca</td>\n<td>Porcion</td>\n<td>Kcal</td>\n<td>Grasa</td>\n<td>Grasas saturadas</td>\n<td>Carbohidratos</td>\n<td>Azúcar</td>\n<td>Proteina</td>\n<td>Sal</td>\n<td class='borde2'>Calidad</td>\n</tr>";
        #Se recorre cada resultado y se extraen los datos del alimento y se añaden al codigo en forma de tabla segun la posicion de su indice
        while ($data = mysqli_fetch_array($result)) {
            $code .= "<tr><td class='color1'>" . $data[1] . "</td><td class='color2'>" . $data[2] . "</td><td class='color1'>" . $data[3] . "</td><td class='color2'>" . $data[4] . "</td><td class='color1'>" . $data[5] . "</td><td class='color2'>" . $data[6] . "</td><td class='color1'>" . $data[7] . "</td><td class='color2'>" . $data[8] . "</td><td class='color1'>" . $data[9] . "</td><td class='color2'>" . $data[10] . "</td><td class='color1'>" . $data[11] . "</td></tr>";
        }
        #Se llama al metodo privado paginacion alimentos y se le pasa por parametro la pagina actual, el numero total de paginas y la marca si existe
        $codigo_paginacion = $this->paginacion_alimentos($pagina, $total_paginas, $marca);
        #Al final del todo se junta el codigo con el de la paginacion y se muestra la tabla
        echo $code . "</table>" . $codigo_paginacion;
    }
    #Metodo que sirve para paginar los alimentos, se introducen tres parametros
    #La pagina actual, el total de paginas y la marca si existe
    private function paginacion_alimentos($pagina, $total_paginas, $marca = "")
    {
        #Si la marca no esta vacia entonces añade &marca a la url
        $marca_parametro = ($marca != "") ? "&marca=" . $marca : "";
        #Codigo de la paginacion
        $code_paginacion = '<div class="paginacion">';
        #Si es mayor que 1 mostrara la opcion de anterior
        if ($pagina > 1) {
            $code_paginacion .= '<p><a href="muestra_alimentos.php?pagina=' . ($pagina - 1) . $marca_parametro . '">Anterior</a> ... </p>';
        }
        # Genera enlaces numerados de páginas en un rango específico alrededor de la página actual
        for ($i = max(1, $pagina - 3); $i <= min($total_paginas, $pagina + 3); $i++) {
            if ($i <= $total_paginas) {
                #Agrega un enlace de página a la variable $code_paginacion
                $code_paginacion .= '<p><a href="muestra_alimentos.php?pagina=' . $i . $marca_parametro . '">' . $i . '</a></p>';
            }
        }
        if ($pagina < $total_paginas) {
            #Agrega un enlace "Siguiente" a la variable $code_paginacion si hay más páginas disponibles
            $code_paginacion .= '<p> ...<a href="muestra_alimentos.php?pagina=' . ($pagina + 1) . $marca_parametro . '"> Siguiente</a></p>';
        }
        $code_paginacion .= "</div>";
        #Devuelve el contenido de $code_paginacion, que contiene todos los enlaces generados
        return $code_paginacion;
    }
    #Metodo que busca los alimentos de una base de datos por su nombre y marca
    #Guarda todas las coincidencias en un array y muestra su nombre, marca, porcion y kcal
    function buscar_alimentos($name, $marca)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM alimentos WHERE nombre_alimen LIKE CONCAT('%', ?, '%') AND marca=? LIMIT 50";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $marca);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            return 0;
        }
        $alimentos = array();
        while ($row = $result->fetch_assoc()) {
            $alimento = array(
                "id" => $row["id_alimento"],
                "nombre" => $row['nombre_alimen'],
                "marca" => $row['marca'],
                "porcion" => $row['porcion'],
                "kcal" => $row['kcal']
            );
            $alimentos[] = $alimento;
        }
        $stmt->close();
        $conn->close();
        return $alimentos;
    }
    #Metodo que registra la comida del usuario, recibiendo como parametros los datos de la comida del usuario y los datos del propio usuario
    function registrar_comida($u_id, $id_alimento, $fecha, $u_porcion, $comida)
    {
        #Comprueba que existe el alimento y comprueba que existe la comida
        $comprobacion_alimento = $this->numeroAlimentos("SELECT COUNT(*) AS total FROM alimentos WHERE id_alimento=" . $id_alimento . "");
        $comprobacion_comida = $this->numeroAlimentos("SELECT COUNT(*) AS total FROM comen WHERE alimen_id = '" . $id_alimento . "' AND fecha = '" . $fecha . "' AND moment_comida = '" . $comida . "' AND cli_id = " . $u_id);
        #Si la comprobacion del alimento no es 1 entonces el alimento no existe
        if ($comprobacion_alimento != 1) {
            return "El alimento no existe y no se ha podido registrar.";
        }
        #Si la comprobacion de la comida es mayor a 0 significa que tiene que editarlo
        if ($comprobacion_comida > 0) {
            return "Ya has introducido este alimento en esta comida, ve a " . $comida . " para editarlo.";
        }
        #Se introducen los datos de los parametros y se crea la conexion y la consulta sql
        $datos = $u_id . "," . $id_alimento . ",'" . $fecha . "'," . $u_porcion . ",'" . $comida . "'";
        $conn = conectarDB();
        $sql = "INSERT INTO comen (cli_id,alimen_id,fecha, cantidad, moment_comida) VALUES(" . $datos . ")";
        #Si ha ocurrido algun error devolvera el error sino retornara 1
        if (!$conn->query($sql)) {
            return "Ha ocurrido un error al insertar los datos:" . $conn->error;
        }
        $conn->close();
        return 1;
    }
    #Funcion para obtener las comidas del usuario segun la fecha
    function get_comidas($u_id, $fecha)
    {
        #Se crea la conexion y la consulta a la base de datos que hace el calculo segun la porcion ingerida por el usuario para cada nutriente
        $connect = conectarDB();
        $sql = $connect->prepare("SELECT id_comen,cli_id,alimen_id,nombre_alimen,fecha,cantidad,moment_comida,(cantidad*kcal)/porcion AS calc_kcal,(cantidad*grasas)/porcion AS calc_grasas,(cantidad*g_saturadas)/porcion AS calc_saturadas,(cantidad*carbohidratos)/porcion AS calc_carbos,(cantidad*azucar)/porcion AS calc_azucar,(cantidad*proteina)/porcion AS calc_proteina,(cantidad*sal)/porcion AS calc_sal FROM comen INNER JOIN alimentos ON alimen_id=alimentos.id_alimento WHERE cli_id=? AND fecha=?");
        #Se comprueba el tipo de datos
        $sql->bind_param("is", $u_id, $fecha);
        #Se ejecuta la consulta
        $sql->execute();
        #Se obtienen los resultados
        $resultado = $sql->get_result();
        #Se crea un array para cada comida
        $valores = [
            "desayuno" => null,
            "almuerzo" => null,
            "comida" => null,
            "merienda" => null,
            "cena" => null
        ];
        #Si el numero de resultados es 0 o menos, devolvera un 0
        if ($resultado->num_rows <= 0) {
            return 0;
        }
        #Recorre cada registro y lo añade segun la comida que sea al array valores
        while ($fila = $resultado->fetch_assoc()) {
            $momento_comidas = $fila['moment_comida'];
            $valores[$momento_comidas][] = $fila;
        }
        #Se cierran las conexiones y devuelve el array
        $sql->close();
        $connect->close();
        return $valores;
    }
    #Metodo que obtiene los datos de la comida
    function get_comida($comida)
    {
        $code = "";
        #Se pone el total de cada una de las comidas para calcularlo
        $total_kcal = 0;
        $total_carbos = 0;
        $total_grasas = 0;
        $total_saturadas = 0;
        $total_azucar = 0;
        $total_proteina = 0;
        $total_sal = 0;
        #Recorre cada comida del alimento y añade al total para calcular el total de nutrientes de la comida
        foreach ($comida as $alimento) {
            $total_kcal += $alimento['calc_kcal'];
            $total_carbos += $alimento['calc_carbos'];
            $total_grasas += $alimento['calc_grasas'];
            $total_saturadas += $alimento['calc_saturadas'];
            $total_azucar += $alimento['calc_azucar'];
            $total_proteina += $alimento['calc_proteina'];
            $total_sal += $alimento['calc_sal'];
            #Muestra datos sobre nuestra comida y la opcion de borrar y cambiar el alimento
            $code .= "<div class='content-comida'><p class='alimen'>" . $alimento['nombre_alimen'] . " (" . $alimento['cantidad'] . " gr o ml)</p>";
            $code .= "<div class='options'>";
            $code .= "<div class='option'><form action='./pages/options_comen.php' method='POST'><input type='hidden' name='id_comida' value=" . $alimento['id_comen'] . "><input type='image' src='./images/eliminar.png' name='borrar'/></form></div>";
            $code .= "<div class='option'><form action='' method='POST'><a href='#container2' onclick='changeAlimento(" . $alimento['alimen_id'] . ",event," . $alimento['id_comen'] . ")'><img  src='./images/editar.png'/></a></form></div>";
            $code .= "</div>";
            #Muestra los datos de los valores
            $code .= "<div class='valores-comida'>
            <p>" . round($alimento['calc_kcal'], 2) . "</p>
            <p>" . round($alimento['calc_carbos'], 2) . "</p>
            <p>" . round($alimento['calc_grasas'], 2) . "</p>
            <p>" . round($alimento['calc_saturadas'], 2) . "</p>
            <p>" . round($alimento['calc_azucar'], 2) . "</p>
            <p>" . round($alimento['calc_proteina'], 2) . "</p>
            <p>" . round($alimento['calc_sal'], 2) . "</p>
        </div></div>";
        }
        #Al final muestra el calculo total de cada nutriente en la comida
        $code .= "<div class='content-comida'><p><a href='#container2' onclick='addComida()' class='add_alimen'>Añadir alimento</a> | Total:</p>";
        $code .= "<div class='valores-comida'>
            <p>" . round($total_kcal, 2) . "</p>
            <p>" . round($total_carbos, 2) . "</p>
            <p>" . round($total_grasas, 2) . "</p>
            <p>" . round($total_saturadas, 2) . "</p>
            <p>" . round($total_azucar, 2) . "</p>
            <p>" . round($total_proteina, 2) . "</p>
            <p>" . round($total_sal, 2) . "</p>
        </div></div>";
        #Devuelve el codigo
        return $code;
    }
    #Metodo que devuelve el total diario de cada nutriente y las calorias que ha consumido el usuario
    function total_diario($u_id, $fecha, $data)
    {
        if ($data == 0) {
            return 0;
        }
        $connect = conectarDB();
        $sql = $connect->prepare("SELECT ROUND(SUM((cantidad*kcal)/porcion), 2) AS calc_kcal,
        ROUND(SUM((cantidad*grasas)/porcion), 2) AS calc_grasas,
        ROUND(SUM((cantidad*g_saturadas)/porcion), 2) AS calc_saturadas,
        ROUND(SUM((cantidad*carbohidratos)/porcion), 2) AS calc_carbos,
        ROUND(SUM((cantidad*azucar)/porcion), 2) AS calc_azucar,
        ROUND(SUM((cantidad*proteina)/porcion), 2) AS calc_proteina,
        ROUND(SUM((cantidad*sal)/porcion), 2) AS calc_sal
        FROM comen
        INNER JOIN alimentos ON alimen_id = alimentos.id_alimento
        WHERE cli_id = ? AND fecha =?;
        ");
        $sql->bind_param("is", $u_id, $fecha);
        $sql->execute();
        $resultado = $sql->get_result();
        $resumen = $resultado->fetch_all(MYSQLI_ASSOC);
        $sql->close();
        $connect->close();
        return $resumen;
    }
    #Metodo que elimina una comida del usuario
    private function deleteComida($id_comida)
    {
        $conn = conectarDB();
        $sql = "DELETE FROM comen WHERE id_comen=?";
        $data = $conn->prepare($sql);
        $data->bind_param('i', $id_comida);
        $data = $data->execute();
        if ($data == false) {
            return "<script>alert('Ha ocurrido un error en el momento de eliminar'); window.location.href='../index.php'</script>";
        }
        $conn->close();
        return "<script>alert('El alimento se ha eliminado correctamente de tu comida.'); window.location.href='../index.php'</script>";
    }
    #Metodo que comprueba si el usuario y la comida coinciden, en caso de que no muestra un error y en caso de que si procede a eliminar la comida
    function checkComida($u_id, $id_comida)
    {
        $conn = conectarDB();
        $sql = "SELECT id_comen,cli_id FROM comen WHERE id_comen=?";
        $data = $conn->prepare($sql);
        $data->bind_param('i', $id_comida);
        $data->execute();
        $result = $data->get_result();
        $result = $result->fetch_array();
        if ($result[1] != $u_id) {
            $data->close();
            $conn->close();
            return "<script>alert('El usuario y la comida no coinciden'); window.location.href='../index.php'</script>";
        }
        $data->close();
        $conn->close();
        $r = $this->deleteComida($id_comida);
        return $r;
    }
    #Metodo con el que se obtiene los datos de un alimento, en caso de que ocurra un error devuelve falso y sino devuelve un array
    function data_Alimento($id_alimento)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM alimentos WHERE id_alimento=?";
        $data = $conn->prepare($sql);
        $data->bind_param('i', $id_alimento);
        if ($data->execute() == false) {
            return false;
        }
        $result = $data->get_result();
        $result = $result->fetch_row();
        return $result;
    }
    #Metodo para cambiar la comida, con el parametro porcion y el id de la comida.
    function change_comida($id_comida, $porcion)
    {
        $conn = conectarDB();
        $sql = "UPDATE comen SET cantidad=? WHERE id_comen=?";
        $data = $conn->prepare($sql);
        $data->bind_param('ii', $porcion, $id_comida);
        if ($data->execute() == false) {
            return "<script>alert('Ha ocurrido un error al intentar actualizar la comida'); window.location.href='../index.php'</script>";
        } else {
            $data->close();
            $conn->close();
            return "<script>alert('La comida se ha actualizado correctamente'); window.location.href='../index.php'</script>";
        }
    }
    #Metodo que muesta la lista de los alimentos que no estan en los favoritos de un usuario
    function list_notfavorites($name, $marca, $idcli)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM alimentos WHERE nombre_alimen LIKE CONCAT('%', ?, '%') AND marca=? AND id_alimento NOT IN (SELECT id_alimefav FROM favoritos WHERE id_clifav=?) LIMIT 10";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $name, $marca, $idcli);
        if (!$stmt->execute()) {
            return 0;
        }
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            return 0;
        }
        $alimentos = array();
        while ($row = $result->fetch_assoc()) {
            $alimento = array(
                "id" => $row["id_alimento"],
                "nombre" => $row['nombre_alimen'],
                "marca" => $row['marca'],
                "porcion" => $row['porcion'],
                "kcal" => $row['kcal']
            );
            $alimentos[] = $alimento;
        }
        $stmt->close();
        $conn->close();
        return $alimentos;
    }
    #Metodo que muestra la lista de favoritos de un usuario
    function list_favorites($id_u)
    {
        $conn = conectarDB();
        $sql = "SELECT id_alimefav FROM favoritos WHERE id_clifav=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_u);
        if (!$stmt->execute()) {
            return 0;
        }
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            return "No tiene ningun alimento en favoritos.";
        }
        $stmt->close();
        $conn->close();
        $code = "";
        while ($row = $result->fetch_array()) {
            $data_alimento = $this->data_Alimento($row[0]);
            $code .= "<li><span>" . $data_alimento[1] . " (" . $data_alimento[2] . ")</span> <a href='#' onclick='deleteFavorites(" . $row[0] . ")'><img src='../images/estrella_luz.png'/></a></li>";
        }
        return $code;
    }
    #Metodo que añade un alimeto a la lista de favoritos de un usuario
    function add_favorite($id_u, $id_alimento)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM favoritos WHERE id_clifav=? AND id_alimefav=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id_u, $id_alimento);
        if (!$stmt->execute()) {
            return "Error en la ejecucion de la consulta: " . $stmt->error;
        }
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            return "<script>alert('El alimento ya estaba marcado como favorito.')</script>";
        }
        $stmt->close();
        $conn->close();
        $conn = conectarDB();
        $sql = "INSERT INTO favoritos VALUES(?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_u, $id_alimento);
        if (!$stmt->execute()) {
            return "Error al agregar a favoritos: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return 1;
    }
    #Metodo que borra un favorito de la lista de favoritos de un usuario
    function borrar_favorito($id_u, $id_alimen)
    {
        $conn = conectarDB();
        $sql = "DELETE FROM favoritos WHERE id_clifav=? AND id_alimefav=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_u, $id_alimen);
        if (!$stmt->execute()) {
            return "Ha ocurrido un error al quitar el alimento de favoritos, recarga la pagina";
        }
        return 1;
    }
    #Metodo que muestra a traves de la busqueda por marca y nombre alimentos que esten en la lista de favoritos de un usuario
    function buscar_favoritos($name, $marca, $id_u, $add = false)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM alimentos WHERE nombre_alimen LIKE CONCAT('%', ?, '%') AND marca=? AND id_alimento IN 
        (SELECT id_alimefav FROM favoritos WHERE id_clifav=?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $marca, $id_u);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            return 0;
        }
        $code = "";
        while ($row = $result->fetch_array()) {
            #El parametro add esta por defecto en false porque significa que no es para añadir el alimento a una comida
            #En caso de que sea verdadero devolvera un array con los datos para que pueda ser añadido a la comida
            if (!$add) {
                $code .= "<li><span>" . $row[1] . " (" . $row[2] . ")</span> <a href='#' onclick='deleteFavorites(" . $row[0] . ")'>
                <img src='../images/estrella_luz.png'/></a></li>";
            } else {
                $alimento = array(
                    "id" => $row[0],
                    "nombre" => $row[1],
                    "marca" => $row[2],
                    "porcion" => $row[3],
                    "kcal" => $row[4]
                );
                $r = json_encode($alimento);
                $text = $row[2] . ", " . $row[3] . " (gr o ml), " . $row[4] . "kcal";
                $code .= "<li onclick='muestraAlimentos($r)'><a href='#c1'>" . $row[1] . "</a><p>" . $text . "</p></li>";
            }
        }
        $stmt->close();
        $conn->close();
        return $code;
    }
    #Metodo que muestra el top 10 favoritos de los usuarios
    function top_ten_favorites()
    {
        $conn = conectarDB();
        $result = $conn->query("SELECT id_alimefav, COUNT(id_clifav) AS number FROM favoritos GROUP BY id_alimefav 
        ORDER BY number DESC LIMIT 10");
        $nombre_alimentos = [];
        $times_added = [];
        $code = "";
        $num = 1;
        while ($row = $result->fetch_assoc()) {
            $alimento = $this->data_Alimento($row['id_alimefav']);
            $nombre_alimentos[] = $alimento[1];
            $times_added[] = $row['number'];
            $code .= "<tr>";
            $code .= "<td>" . $num . "</td>";
            $code .= "<td>" . $alimento[1] . " (" . $alimento[2] . ")</td>";
            $code .= "<td>" . $row['number'] . "</td>";
            $code .= "</tr>";
            $num += 1;
        }
        return [$code, $nombre_alimentos, $times_added];
    }

    #Metodo que devuelve un array con los alimentos que el usuario no ha valorado
    function alimentos_novals($id_user, $alimen, $marca)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM alimentos WHERE id_alimento NOT IN(SELECT id_alimenval FROM valoralimen WHERE id_clival=?) AND nombre_alimen LIKE CONCAT('%', ?, '%') AND marca=? LIMIT 50";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $id_user, $alimen, $marca);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $data = array();
        while ($row = $result->fetch_array()) {
            $data[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $data;
    }

    #Metodo que devuelve los alimentos que el usuario ha valorado y tambien los devuelve segun los filtros
    function alimentos_valorados($id_user, $n_alimentos = '', $marca = '')
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM valoralimen INNER JOIN alimentos ON valoralimen.id_alimenval=alimentos.id_alimento WHERE id_clival=? AND nombre_alimen LIKE CONCAT('%', ?, '%') AND marca LIKE CONCAT('%', ?, '%')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $id_user, $n_alimentos, $marca);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $data = [];
        while ($row = $result->fetch_array()) {
            $data[] = $row;
        }
        return $data;
    }

    #Metodo para insertar la puntuacion del alimento por parte del usuario
    function insertar_valores($id_user, $id_alimento, $puntuacion)
    {
        $conn = conectarDB();
        $sql = "INSERT INTO valoralimen  VALUES(?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $id_user, $id_alimento, $puntuacion);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $stmt->close();
        $conn->close();
        return 1;
    }

    #Metodo para cambiar la puntuacion del alimento por parte del usuario
    function cambiar_valores($id_user, $id_alimento, $puntuacion)
    {
        $conn = conectarDB();
        $sql = "UPDATE valoralimen SET puntuacion=? WHERE id_clival=? AND id_alimenval=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $puntuacion, $id_user, $id_alimento);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $stmt->close();
        $conn->close();
        return 1;
    }

    #Metodo para eliminar una valoracion
    function eliminar_valor($id_user, $id_alimento)
    {
        $conn = conectarDB();
        $sql = "DELETE FROM valoralimen WHERE id_clival=? AND id_alimenval=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id_user, $id_alimento);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $stmt->close();
        $conn->close();
        return 1;
    }

    #Metodo que muestra el top 10 de valoraciones en una tabla
    function top_ten_valoracion()
    {
        $conn = conectarDB();
        $result = $conn->query("SELECT nombre_alimen, marca, valoracion FROM alimentos ORDER BY valoracion DESC, nombre_alimen ASC LIMIT 10;");
        $nombre_alimentos = [];
        $medias = [];
        $code = "";
        $num = 1;
        while ($row = $result->fetch_assoc()) {
            $nombre_alimentos[] = $row['nombre_alimen'];
            $medias[] = $row['valoracion'];
            $code .= "<tr>";
            $code .= "<td>" . $num . "</td>";
            $code .= "<td>" . $row['nombre_alimen'] . " (" . $row['marca'] . ")</td>";
            $code .= "<td>" . $row['valoracion'] . "</td>";
            $code .= "</tr>";
            $num += 1;
        }
        return [$code, $nombre_alimentos, $medias];
    }
}
?>