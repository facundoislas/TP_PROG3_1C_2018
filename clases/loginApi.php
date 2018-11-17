<?php
require_once 'Empleado.php';
require_once 'AutentificadorJWT.php';
require_once 'historico.php';


class loginApi
{

    public function login($request, $response, $args) 
    {
        $token="";
        $ArrayDeParametros = $request->getParsedBody();
        
        if(isset( $ArrayDeParametros['user'])&& isset( $ArrayDeParametros['pass']) )
        {
            $user = $ArrayDeParametros['user'];
            $pass = $ArrayDeParametros['pass'];
          
            //return $response->withJson(loginApi::is_valid_user($user),404);
            
            $empAux = empleado::TraerUnEmpleadoUser($user);
            // $passValida = password_verify($pass, $empAux->pass);
            //$passValida = password_verify($pass, $empAux->pass);

         
            if ($empAux) {
                if ($pass == $empAux->pass) {
                    $usuarioBuscado = empleado::TraerUnEmpleadoUser($user);
                } else {
                    return $response->withJson("La contraseña no es válida",404);
                }
            }
            else {
                 $usuarioBuscado = false;
                 $empAux =false;
                 return $response->withJson("El usuario no es válido",404);
            }
            //var_dump($usuarioBuscado);
            $objRespuesta = new stdClass();
            //$objRespuesta->Datos= null;
            $objRespuesta->msj = null;
            $objRespuesta->Token = null;
               
                if($usuarioBuscado)
                {
                    if ($usuarioBuscado->estado != "suspendido") 
                    {

                    
                        $token= AutentificadorJWT::CrearToken(array(
                            'id'=> $usuarioBuscado->id,
                            'user'=> $usuarioBuscado->user,
                            'nombre'=> $usuarioBuscado->nombre,
                            'puesto'=> $usuarioBuscado->puesto,
                            'estado'=> $usuarioBuscado->estado));

                        $datos= AutentificadorJWT::ObtenerData($token);
                        //$objRespuesta->Token = $token;
                        //$objRespuesta->Datos =$datos;
                        date_default_timezone_set('America/Argentina/Buenos_Aires');
                        $f= date("Y-m-d");
                        $h= date("H:i:s");
                        
                        historico::registrarLogin($usuarioBuscado->id,$f,$h);
                        $objRespuesta->msj ="Bienvenido ".$datos->nombre;
                        $objRespuesta->Token = $token;
                        return $response->withJson($objRespuesta ,200);
                    }
                    else {
                        return $response->withJson("Usuario Suspendido",404);
                    }
                }
                else
                {
                    return $response->withJson("Error en user o pass",404);
                    //$newResponse = $response->withJson( $retorno ,409); 
                }
        }
        else
        {
            return $response->withJson("Falta user y pass",404);
        }
    }

    public function datosToken($request, $response, $args) {
        $arrayConToken = $request->getHeader('token');
        $token=$arrayConToken[0];
        try{
			$datosToken = AutentificadorJWT::ObtenerData($token);
		}
		catch(Exception $e){
			return $response->withJson($e->getMessage(), 511);
		}
		return $response->withJson( $datosToken ,200);

    }

}