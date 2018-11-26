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
        
        if(isset( $ArrayDeParametros['email'])&& isset( $ArrayDeParametros['clave']) )
        {
            $email = $ArrayDeParametros['email'];
            $clave = $ArrayDeParametros['clave'];
            //return $response->withJson(loginApi::is_valid_email($email),404);
             if (loginApi::is_valid_email($email)==true) {
                $empAux = empleado::TraerEmpleadoEmail($email);
             }
             else{
                 return $response->withJson("no es email",404);
             }
            
            
            $empAux = empleado::TraerEmpleadoEmail($email);
            // $claveValida = password_verify($clave, $empAux->clave);
            //$claveValida = password_verify($clave, $empAux->clave);
            
            if ($empAux) {
                if ($clave == $empAux->clave) {
                    $usuarioBuscado = empleado::TraerEmpleadoEmail($email);

                    
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
                            'email'=> $usuarioBuscado->email,
                            'nombre'=> $usuarioBuscado->nombre,
                            'tipo'=> $usuarioBuscado->tipo,
                            'estado'=> $usuarioBuscado->estado));


                        $datos= AutentificadorJWT::ObtenerData($token);
                        //$objRespuesta->Token = $token;
                        //$objRespuesta->Datos =$datos;


                        date_default_timezone_set("America/Argentina/Buenos_Aires");
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
                    return $response->withJson("Error en email o clave",404);
                    //$newResponse = $response->withJson( $retorno ,409); 
                }
        }
        else
        {
            return $response->withJson("Falta email y clave",404);
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
    public static function is_valid_email($str)
    {
      return (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
    }

}