<?php

require_once 'config.php';
require_once 'auth.php';
require_once 'crud.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set("America/Guatemala");

// Las operaciones CRUD solo se realizan si el token es válido
$metodo = $_SERVER['REQUEST_METHOD'];


switch ($metodo) {
	
	case 'OPTIONS':
		die();
		break;
			
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if( $id ){
            $arrDatos = obtenerUsuarioPorId($id);
            if( count($arrDatos)>0 ){
                http_response_code(200);
                $arrJsonSalida["codigoRespuesta"] = 1;
                $arrJsonSalida["descripcionRespuesta"] = "Datos consultados exitosamente.";
                $arrJsonSalida["datos"] = array($arrDatos);
                echo json_encode($arrJsonSalida);
            }else{
                http_response_code(500);
                $arrJsonSalida["codigoRespuesta"] = 0;
                $arrJsonSalida["descripcionRespuesta"] = "No se encontraron datos.";
                echo json_encode($arrJsonSalida);
            }   
        }else{
            $arrDatos = obtenerTodosLosUsuarios();
            if( count($arrDatos)>0 ){
                http_response_code(200);
                $arrJsonSalida["codigoRespuesta"] = 1;
                $arrJsonSalida["descripcionRespuesta"] = "Datos consultados exitosamente.";
                $arrJsonSalida["datos"] = $arrDatos;
                echo json_encode($arrJsonSalida);
            }else{
                http_response_code(500);
                $arrJsonSalida["codigoRespuesta"] = 0;
                $arrJsonSalida["descripcionRespuesta"] = "No existe informacion.";
                echo json_encode($arrJsonSalida);
            }
        }
        break;

    case 'POST':

        $boolError = false;
        $data = json_decode(file_get_contents('php://input'), true);

        if ( isset($data['nombre']) == false || $data['nombre'] =='' ){
            $boolError = true;
        }

        if ( isset($data['email']) == false || $data['email'] =='' ){
            $boolError = true;
        }

        if (isset($data['password']) == false || $data['password'] =='' ){
            $boolError = true;
        }

        $boolCorreo = esCorreo($data['email']);
        if ( !$boolCorreo ){
            $boolError = true;
        }

        if ( !$boolError ){
            $nombre = $data['nombre'];
            $email = $data['email'];
            $password = $data['password'];

            $boolExiste = validarExisteUsuario($email);
            if( !$boolExiste ){
                $arrRespuesta = agregarUsuario($nombre, $email, $password);
                if( $arrRespuesta["codigoRespuesta"] == '1' ){
                    http_response_code(200);
                    $arrJsonSalida["codigoRespuesta"] = 1;
                    $arrJsonSalida["descripcionRespuesta"] = "El registro se agrego exitosamente.";
                    echo json_encode($arrJsonSalida);
                }else{
                    http_response_code(200);
                    $arrJsonSalida["codigoRespuesta"] = 0;
                    $arrJsonSalida["descripcionRespuesta"] = "El registro no se puedo agregar.";
                    echo json_encode($arrJsonSalida);
                }
            }else{
                http_response_code(200);
                $arrJsonSalida["codigoRespuesta"] = 0;
                $arrJsonSalida["descripcionRespuesta"] = "El nombre del usuario ya fue registrado anteriormente.";
                echo json_encode($arrJsonSalida);
            }
        }else{
            http_response_code(200);
            $arrJsonSalida["codigoRespuesta"] = 0;
            $arrJsonSalida["descripcionRespuesta"] = "Revisar los campos del formulario.";
            echo json_encode($arrJsonSalida);
        }

        break;

    case 'PUT':

        $boolError = false;
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id']) == false || $data['id'] =='' ){
            $boolError = true;
        }

        if (isset($data['nombre']) == false || $data['nombre'] =='' ){
            $boolError = true;
        }

        if (isset($data['email']) == false || $data['email'] =='' ){
            $boolError = true;
        }

        if (isset($data['password']) == false || $data['password'] =='' ){
            $boolError = true;
        }

        $boolCorreo = esCorreo($data['email']);
        if ( !$boolCorreo ){
            $boolError = true;
        }

        if ( !$boolError ){
            $id = $data['id'];
            $nombre = $data['nombre'];
            $email = $data['email'];
            $password = $data['password'];
			
			$arrDatosUser = obtenerUsuarioPorId($id);
			//print "<pre>";
			//print_r($arrDatosUser);
			//print "</pre>";
			if( $arrDatosUser['email'] == $email ){
				$arrRespuesta = editarUsuario($id, $nombre, $email, $password);
				if( $arrRespuesta["codigoRespuesta"] == '1' ){
					http_response_code(200);
					$arrJsonSalida["codigoRespuesta"] = 1;
					$arrJsonSalida["descripcionRespuesta"] = "El registro se edito exitosamente.";
					echo json_encode($arrJsonSalida);
				}else{
					http_response_code(200);
					$arrJsonSalida["codigoRespuesta"] = 0;
					$arrJsonSalida["descripcionRespuesta"] = "El registro no se pudo editar.";
					echo json_encode($arrJsonSalida);
				}
			}else{
				$boolExiste = validarExisteUsuario($email);
				if( !$boolExiste ){
					$arrRespuesta = editarUsuario($id, $nombre, $email, $password);
					if( $arrRespuesta["codigoRespuesta"] == '1' ){
						http_response_code(200);
						$arrJsonSalida["codigoRespuesta"] = 1;
						$arrJsonSalida["descripcionRespuesta"] = "El registro se edito exitosamente.";
						echo json_encode($arrJsonSalida);
					}else{
						http_response_code(200);
						$arrJsonSalida["codigoRespuesta"] = 0;
						$arrJsonSalida["descripcionRespuesta"] = "El registro no se pudo editar.";
						echo json_encode($arrJsonSalida);
					}
				}else{
					http_response_code(200);
					$arrJsonSalida["codigoRespuesta"] = 0;
					$arrJsonSalida["descripcionRespuesta"] = "El usuario ya ha sido registrado anteriormente.";	
					echo json_encode($arrJsonSalida);
				}
			}
			
        }else{
            http_response_code(200);
            $arrJsonSalida["codigoRespuesta"] = 0;
            $arrJsonSalida["descripcionRespuesta"] = "Revisar los campos del formulario.";
            echo json_encode($arrJsonSalida);
        }

        break;

    case 'DELETE':

        $boolError = false;
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id']) == false || $data['id'] =='' ){
            $boolError = true;
        }

        if ( !$boolError ){
            $id = $data['id'];
            $arrRespuesta = eliminarUsuario($id);
            if( $arrRespuesta["codigoRespuesta"] == '1' ){
                http_response_code(200);
                $arrJsonSalida["codigoRespuesta"] = 1;
                $arrJsonSalida["descripcionRespuesta"] = "El registro se eliminado exitosamente.";
                echo json_encode($arrJsonSalida);
            }else{
                http_response_code(200);
                $arrJsonSalida["codigoRespuesta"] = 0;
                $arrJsonSalida["descripcionRespuesta"] = "El registro no se pudo eliminar.";
                echo json_encode($arrJsonSalida);
            }
        }else{
            http_response_code(200);
            $arrJsonSalida["codigoRespuesta"] = 0;
            $arrJsonSalida["descripcionRespuesta"] = "Error en json de entrada.";
            echo json_encode($arrJsonSalida);
        }
        
        break;
		
    default:
        http_response_code(405);
        $arrJsonSalida["codigoRespuesta"] = 0;
        $arrJsonSalida["descripcionRespuesta"] = "Metodo no permitido.";
        echo json_encode($arrJsonSalida);
}

?>