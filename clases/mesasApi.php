<?php
require_once 'mesa.php';
require_once 'IApiUsable.php';

class mesasApi extends mesa implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
		 $id=$args['idMesa'];
		$mesa=mesa::traerMesaID($id);
		
        if(!$mesa)
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No esta la mesa";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }else
        {
            $NuevaRespuesta = $response->withJson($mesa, 200); 
        }     
        return $NuevaRespuesta;
    }


     public function TraerTodos($request, $response, $args) {
      	$todosLosmesas=mesa::TraerTodasLasMesas();
     	$newresponse = $response->withJson($todosLosmesas, 200);  
    	return $newresponse;
    }


      	public function CargarUno($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
		$idMesa= $ArrayDeParametros['idMesa'];
		
        
        $mimesa = new mesa();
		$mimesa->idMesa = $idMesa;
        $mimesa->AgregarMesa();
       
        //$response->getBody()->write("se guardo el mesa");
        $objDelaRespuesta->respuesta="Se guardo la mesa.";   
        return $response->withJson($objDelaRespuesta, 200);
    }


      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$id=$ArrayDeParametros['idMesa'];
     	$mesa= new mesa();
     	$mesa->idMesa=$id;
     	$cantidadDeBorrados=$mesa->BorrarMesa();

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
	    //var_dump($ArrayDeParametros);    	
	    $mimesa = new mesa();
	    $mimesa->idMesa=$ArrayDeParametros['idMesa'];
	    $mimesa->estado=$ArrayDeParametros['estado'];
	    

	   	$resultado =$mimesa->CambiarEstadoMesa();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
		return $response->withJson($objDelaRespuesta, 200);		
    }

	public function Cerrar($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();
	    //var_dump($ArrayDeParametros);    	
	    $mimesa = new mesa();
	    $mimesa->idMesa=$ArrayDeParametros['idMesa'];
	    $mimesa->estado=$ArrayDeParametros['estado'];
	    

	   	$resultado =$mimesa->CerrarMesa();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="Cerrar";
		return $response->withJson($objDelaRespuesta, 200);	
    }


}