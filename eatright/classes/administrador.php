<?php
require_once(__DIR__ . "/../config/connect.php");
require_once("user.php");
class Administrador extends User
{
    #Funcion que devuelve un array de los alimentos que se han buscado según los parametros de busqueda
    function alimentos($marca, $n_alimento)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM alimentos WHERE marca LIKE CONCAT('%',?,'%') AND nombre_alimen LIKE CONCAT('%',?,'%') LIMIT 50";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $marca, $n_alimento);
        if (!$stmt->execute()) {
            return 0;
        }
        $results = $stmt->get_result();
        if ($results->num_rows <= 0) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $data = [];
        while ($row = $results->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $data;
    }
    #Funcion que edita los datos de un alimento
    function editarAlimento($id_alimento, $n_alimento, $marca, $porcion, $kcal, $grasas, $g_saturadas, $carbos, $azucar, $proteina, $sal)
    {
        $conn = conectarDB();
        $sql = "UPDATE alimentos SET nombre_alimen=?, marca=?, porcion=?, kcal=?, grasas=?, g_saturadas=?, carbohidratos=?, azucar=?, proteina=?,sal=? WHERE id_alimento=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssddddddddi', $n_alimento, $marca, $porcion, $kcal, $grasas, $g_saturadas, $carbos, $azucar, $proteina, $sal, $id_alimento);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $stmt->close();
        $conn->close();
        return 1;
    }
    #Funcion para eliminar un alimento
    function eliminar_alimento($id_alimento)
    {
        $conn = conectarDB();
        $sql = "DELETE FROM alimentos WHERE id_alimento=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_alimento);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $stmt->close();
        $conn->close();
        return 1;
    }
    #Funcion para añadir un alimento
    function add_alimento($n_alimento, $marca, $porcion, $kcal, $grasas, $g_saturadas, $carbos, $azucar, $proteina, $sal)
    {
        $conn = conectarDB();
        $sql = 'INSERT INTO alimentos (nombre_alimen,marca,porcion,kcal,grasas,g_saturadas,carbohidratos,azucar,proteina,sal) VALUES(?,?,?,?,?,?,?,?,?,?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdddddddd', $n_alimento, $marca, $porcion, $kcal, $grasas, $g_saturadas, $carbos, $azucar, $proteina, $sal);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $stmt->close();
        $conn->close();
        return 1;
    }
    #Funcion que muestra los usuarios segun los parametros de busqueda introducidos
    function view_clientes($n_user, $email)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM clientes WHERE n_user LIKE CONCAT('%',?,'%') AND email LIKE CONCAT('%',?,'%') LIMIT 50";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $n_user, $email);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $results = $stmt->get_result();
        if ($results->num_rows <= 0) {
            $stmt->close();
            $conn->close();
            return 0;
        }
        $data = [];
        while ($row = $results->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $data;
    }
    #Funcion que comprueba si la contraseña cumple los requisitos necesarios
    function comprobacion_contraseña($pass, $pass_repetida)
    {
        $exp = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
        if ($pass != $pass_repetida) {
            return "<script>alert('Las contraseñas no coinciden.')</script>";
        }
        if (!preg_match($exp, $pass)) {
            return "<script>alert('La contraseña debe contener una mayúscula, un número y tener 8 dígitos')</script>";
        }
        return 1;
    }
    #Funcion que cambia la contraseña del usuario
    function change_password($id_u, $pass, $pass_repetida)
    {
        $comprueba_contraseña = $this->comprobacion_contraseña($pass, $pass_repetida);
        if ($comprueba_contraseña != 1) {
            return $comprueba_contraseña;
        }
        $cifrado_pass = password_hash($pass, PASSWORD_DEFAULT, array("cost" => 12));
        $conn = conectarDB();
        $sql = "UPDATE clientes SET pass=? WHERE id_cli=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $cifrado_pass, $id_u);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "<script>alert('Ha ocurrido un al intentar cambiar la contraseña')</script>";
        }
        return 1;
    }
    #Funcion que registra un usuario siendo administrador
    function registrar_usuario($n_user, $email, $pass, $pass_repetida, $sexo, $f_nacimiento, $peso, $altura, $n_completo)
    {
        #Llama a la funcion para comprobar la contraseña
        $comprueba_contraseña = $this->comprobacion_contraseña($pass, $pass_repetida);
        if ($comprueba_contraseña != 1) {
            return $comprueba_contraseña;
        }
        #Llama a la funcion para comprobar si existe o no un usuario con ese email o nombre de usuario
        $comprueba_usuario = $this->comprueba_usuario($n_user, $email);
        if ($comprueba_usuario != 1) {
            return $comprueba_usuario;
        }
        $cifrado_pass = password_hash($pass, PASSWORD_DEFAULT, array("cost" => 12));
        $conn = conectarDB();
        $sql = "INSERT INTO clientes(n_user,email,pass,sexo,f_cumple,peso,altura,nombre_completo,estado,intentos) VALUES(?,?,?,?,?,?,?,?,'activo',3)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssdds', $n_user, $email, $cifrado_pass, $sexo, $f_nacimiento, $peso, $altura, $n_completo);
        if (!$stmt->execute()) {
            return "<script>alert('Ha ocurrido un error al insertar los datos.')</script>";
        }
        return 1;
    }
    #Funcion que comprueba si un usuario ya tiene ese nombre de usuario o email
    function comprueba_usuario($n_user, $email)
    {
        $conn = conectarDB();
        $sql = "SELECT * FROM clientes WHERE n_user=? OR email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $n_user, $email);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "<script>alert('Ha ocurrido un error en la consulta.')</script>";
        }
        $results = $stmt->get_result();
        if ($results->num_rows > 0) {
            $stmt->close();
            $conn->close();
            return "<script>alert('El nombre de usuario o email ya existen, cambielos.')</script>";
        }
        $stmt->close();
        $conn->close();
        return 1;
    }
    #Funcion para eliminar un usuario
    function eliminar_usuario($id_u)
    {
        $conn = conectarDB();
        $sql = "DELETE FROM clientes WHERE id_cli=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_u);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "<script>alert('Ha ocurrido un error al eliminar el usuario.')</script>";
        }
        $stmt->close();
        $conn->close();
        return 1;
    }


    #Comprobacion de si existe un email o un nombre de usuario existe sin tener en cuenta el id del usuario para poder editarlo
    function comprobacion_edita_usuario($id_usuario, $n_user, $email)
    {
        $conn = conectarDB();
        $sql = "SELECT n_user,email FROM `clientes` WHERE id_cli!=? AND (n_user=? OR email=?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $id_usuario, $n_user, $email);

        if (!$stmt->execute()) {

            return "<script>alert('Ha ocurrido un error en la consulta de la comprobacion.')</script>";
        }
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            return "<script>alert('Existe ya un usuario con ese nombre o email.')</script>";
        }

        $stmt->close();
        $conn->close();
        return 1;
    }


    #Metodo para editar los datos de un usuario
    function editar_usuario($id_u, $n_user, $email, $sexo, $f_nacimiento, $peso, $altura, $n_completo, $estado, $intentos)
    {

        $comprobacion_usuario = $this->comprobacion_edita_usuario($id_u, $n_user, $email);


        if ($comprobacion_usuario != 1) {
            return $comprobacion_usuario;
        }
        $conn = conectarDB();
        $sql = "UPDATE clientes SET n_user=?,email=?,sexo=?,f_cumple=?,peso=?,altura=?,nombre_completo=?,estado=?,intentos=? WHERE id_cli=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssddssii', $n_user, $email, $sexo, $f_nacimiento, $peso, $altura, $n_completo, $estado, $intentos, $id_u);
        if (!$stmt->execute()) {
            return "<script>alert('Ha ocurrido un error al editar el usuario.')</script>";
        }
        return 1;
    }
}
?>