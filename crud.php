<?php 

require_once 'config.php';

function obtenerTodosLosUsuarios() {
    // Permite consultar todos los usuarios registrados
    $arrDatos = array();
    $sql = "SELECT id, nombre, email, password FROM usuarios ORDER BY nombre";
    $result = executeQuery($sql);
    if (!empty($result)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $arrDat["id"] = $row["id"];
			$arrDat["nombre"] = $row["nombre"];
            $arrDat["email"] = $row["email"];
            $arrDat["password"] = $row["password"];
			array_push($arrDatos, $arrDat);
        }
    }
    return $arrDatos;
}

function obtenerUsuarioPorId($id) {
    // Permite consultar los datos del usuario por medio de su ID
    $arrDatos = array();
    $sql = "SELECT id, nombre, email, password FROM usuarios WHERE id = $id";
    $result = executeQuery($sql);
    if (!empty($result)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $arrDatos["nombre"] = $row["nombre"];
            $arrDatos["email"] = $row["email"];
            $arrDatos["password"] = $row["password"];
        }
    }
    return $arrDatos;
}

function validarExisteUsuario($email){
    // Valida si el nombre de usuario ya fue ingresado anteriormente
    $boolExiste = false;
    $conteo = "";
    $sql = "SELECT COUNT(*) CONTEO FROM usuarios WHERE TRIM(email) = '$email'";
    $result = executeQuery($sql);
    if (!empty($result)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $conteo = $row["CONTEO"];
        }
    }

    $boolExiste = ( $conteo>0 )? true: false;
    return $boolExiste;
}

function agregarUsuario($nombre, $email, $password) {
    // Permite agregar usuarios al catalogo
    if( $nombre!='' && $email!='' && $password!='' ){
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";
        $result = executeQuery($sql);
        $arrJson["codigoRespuesta"] = ($result)? 1: 0;
    }else{
        $arrJson["codigoRespuesta"] = 0;
    }
    
    return $arrJson;
}

function editarUsuario($id, $nombre, $email, $password) {
    // Permite editar usuarios del catalogo
    $sql = "UPDATE usuarios 
               SET nombre = '$nombre',
                   email = '$email',
                   password = '$password'
             WHERE id = $id";
    $result = executeQuery($sql);
    $arrJson["codigoRespuesta"] = ($result)? 1: 0;
    return $arrJson;
}

function eliminarUsuario($id) {
    // Permite eliminar usuarios del catalogo
    $sql = "DELETE FROM usuarios WHERE id = $id";
    $result = executeQuery($sql);
    $arrJson["codigoRespuesta"] = ($result)? 1: 0;
    return $arrJson;
}

function esCorreo($correo) {
    // Usa la función filter_var con el filtro FILTER_VALIDATE_EMAIL para validar el formato del correo electrónico
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

?>