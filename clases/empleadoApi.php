<?php
require_once 'Empleado.php';
require_once 'IApiUsable.php';

class empleadoApi extends empleado implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
         $user=$args['user'];
         //echo($user);
        $empleado=empleado::TraerUnEmpleadoUser($user);
        
        if(!$empleado)
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No esta el empleado";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }else
        {
            $NuevaRespuesta = $response->withJson($empleado, 200); 
        }     
        return $NuevaRespuesta;
    }


     public function TraerTodos($request, $response, $args) {
      	$todosLosEmpleados=empleado::TraerTodosLosEmpleados();
     	$newresponse = $response->withJson($todosLosEmpleados, 200);  
    	return $newresponse;
    }


      public function CargarUno($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        $nombre= $ArrayDeParametros['nombre'];
        $apellido= $ArrayDeParametros['apellido'];
        $user= $ArrayDeParametros['user'];
        $puesto= $ArrayDeParametros['puesto'];
        $sector= $ArrayDeParametros['sector'];
        $perfil= $ArrayDeParametros['perfil'];
        $pass= $ArrayDeParametros['pass'];
        $estado= $ArrayDeParametros['estado'];
        
        
        $miempleado = new empleado();
        $miempleado->nombre=$nombre;
        $miempleado->apellido=$apellido;
        $miempleado->user=$user;
        $miempleado->puesto=$puesto;
        $miempleado->sector=$sector;
        $miempleado->perfil=$perfil;
        $miempleado->estado=$estado;
        $miempleado->pass=$pass;
        $miempleado->InsertarEmpleadoParametro();
       
        //$response->getBody()->write("se guardo el empleado");
        $objDelaRespuesta->respuesta="Se guardo el empleado.";   
        return $response->withJson($objDelaRespuesta, 200);
    }


      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$user=$ArrayDeParametros['user'];
     	$empleado= new empleado();
     	$empleado->user=$user;
     	$cantidadDeBorrados=$empleado->BorrarEmpleado();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="algo borro!!!";
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     
     public function ModificarUno($request, $response, $args) {
     	//$response->getBody()->write("<h1>Modificar  uno</h1>");
     	$ArrayDeParametros = $request->getParsedBody();
	    $miempleado = new empleado();
        $miempleado->user=$ArrayDeParametros['user'];
        $miempleado->estado = $ArrayDeParametros['estado'];
        
	   	$resultado =$miempleado->SuspenderEmpleado();
	   	$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
        return $response->withJson($objDelaRespuesta, 200);	
        
        	
    }


}