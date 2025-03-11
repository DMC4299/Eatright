<?php
#Se incluye la conexion con la base de datos
require_once(__DIR__ . "/../config/connect.php");
#Creamos la clase User
class User
{
    #Usamos dos propiedades privadas que seran el nombre de usuario y la contraseña
    private $id_user;
    private $username;
    private $email;
    private $password;
    private $sexo;
    private $f_cumple;
    private $peso;
    private $altura;
    private $nombre;
    private $estado;
    #La funcion constructora tendra como parametros
    #El nombre de usuario y la contraseña que se asignaran a las propiedades privadas
    function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    #Metodo que muestra los datos del usuario
    function getUser()
    {
        #Devulve un array con las propiedades del usuario
        return [
            "id" => $this->id_user,
            "username" => $this->username,
            "state" => $this->estado
        ];
    }
    #Metodo que hace un check del login
    function checkLogin()
    {
        #Conectar con la base de datos
        $conn = conectarDB();
        #Consulta sql que saca el id, pass y intentos del usuario
        $sql = "SELECT id_cli,pass,intentos,estado FROM clientes WHERE (n_user=? OR email=?)";
        #Se prepara la consulta
        $resultado = mysqli_prepare($conn, $sql);
        #Se consulta los tipos de datos que ha introducido el usuario
        #Comprobando que sean los dos strings
        $comprobacion = mysqli_stmt_bind_param($resultado, "ss", $this->username, $this->username);
        #La comprobacion de que los tipos de datos son correctos
        $comprobacion = mysqli_stmt_execute($resultado);
        #Devuelve 0 si la comprobacion da falso
        if ($comprobacion == false) {
            return 0;
        }
        #Si no devuelve falso guardara los resultados en las variables correspondientes
        $comprobacion = mysqli_stmt_bind_result($resultado, $id_user, $pass, $intentos, $estado);
        #Si el numero de resultados es nulo significa que no hay ningun registro
        #Devolvera 0
        if (is_null(mysqli_stmt_fetch($resultado))) {

            return 0;
        }
        #Comprobacion del numero de intentos que devolvera 2 en caso de que sean 0
        if ($intentos == 0) {
            return 2;
        }
        #Si la contraseña introducida por el usuario no es igual llamara a una funcion que restara intentos
        #Devuelve el numero de intentos que le quedan
        if (!password_verify($this->password, $pass)) {
            $this->setAttempts($id_user, $intentos, false);
            return ["intentos" => $intentos - 1];
        }
        #Introduce el id del usuario en la propiedad y el estado
        $this->id_user = $id_user;
        $this->estado = $estado;
        #Introduce de nuevo 3 intentos
        $this->setAttempts($id_user, 3, true);
        #Cierra la conexion mysql
        mysqli_close($conn);
        #Si todo ha salido correctamente devulve 1
        return 1;
    }
    #Funcion privada que inserta intentos en la base de datos
    private function setAttempts($id, $intentos, $comp)
    {
        #Si la variable $comp es positiva introduce los intentos que ha metido el usuario
        #En caso de que no sea positiva resta 1 a los intentos y los inserta segun el id del usuario
        $intentos = $comp ? $intentos : $intentos - 1;
        $conn = conectarDB();
        $sql = "UPDATE clientes SET intentos=? WHERE id_cli=?";
        $resultado = mysqli_prepare($conn, $sql);
        $comprobacion = mysqli_stmt_bind_param($resultado, "ii", $intentos, $id);
        $comprobacion = mysqli_stmt_execute($resultado);
        mysqli_close($conn);
    }
    #*********Metodos para el registro y comprobaciones********
    #Metodo de registro que se le envia por parametros la informacion del usuario
    function registro($email, $pass_repetida, $sexo, $f_nacimiento, $peso, $altura, $n_completo)
    {
        #Comprueba si la contraseña cumple con los requisitos necesarios y sino es asi retorna un mensaje
        $comprobacion = $this->comprobacion_contraseña($pass_repetida);
        if ($comprobacion != 1) {
            return $comprobacion;
        }
        #Aqui se cifra la contraseña con un coste de 12S
        $cifrado_pass = password_hash($this->password, PASSWORD_DEFAULT, array("cost" => 12));
        #Aqui se comprueba si existe otro usuario con el mismo nombre y en caso de que sea asi envia un mensaje de error
        $c_usuario = $this->n_registros("SELECT * FROM clientes WHERE n_user='" . $this->username . "'");
        if ($c_usuario >= 1) {
            return "Elija otro nombre de usuario, el insertado ya existe.";
        }
        #Aqui se comprueba si existe un usuario con un email igual y en caso de que sea asi envia un mensaje de error
        $c_email = $this->n_registros("SELECT * FROM clientes WHERE email='" . $email . "'");
        if ($c_email >= 1) {
            return "El email ya existe, porfavor introduzca otro";
        }
        #Si todo sale correctamente se crea la conexion con la base de datos y se prepara la consulta sql
        $conn = conectarDB();
        $sql = "INSERT INTO clientes(n_user,email,pass,sexo,f_cumple,peso,altura,nombre_completo,estado,intentos) VALUES(?,?,?,?,?,?,?,?,'activo',3)";
        $result = mysqli_prepare($conn, $sql);
        #Aqui se comprueba que los parametros introducidos por el usuario tienen el tipo de dato correcto y se ejecuta la consulta
        $comprobacion = mysqli_stmt_bind_param($result, "sssssdds", $this->username, $email, $cifrado_pass, $sexo, $f_nacimiento, $peso, $altura, $n_completo);
        $comprobacion = mysqli_stmt_execute($result);
        #Se cierra la conexion y si todo ha salido bien devuelve 1
        mysqli_close($conn);
        return 1;
    }
    #Metodo que comprueba si una contraseña cumple con las medidas de seguridad necesarias
    #Se le introduce como parametro la pass repetida para comprobar si la actual contraseña es igual a la que se ha repetido
    #Luego comprueba si la contraseña cumple con los requisitos necesarios
    private function comprobacion_contraseña($pass_repetida)
    {
        $exp = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
        if ($this->password != $pass_repetida) {
            return "La contraseña repetida no es la misma";
        }
        if (!preg_match($exp, $this->password)) {
            return "La contraseña debe contener una mayúscula, un número y tener 8 dígitos";
        }
        return 1;
    }
    #Metodo que comprueba el numero de registros de una consulta
    private function n_registros($sql)
    {
        $conn = conectarDB();
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_num_rows($result);
        mysqli_close($conn);
        return $rows;
    }
}
?>