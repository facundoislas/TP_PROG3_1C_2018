<?php
require_once 'Pedidos.php';
require_once 'IApiUsable.php';

class pedidosApi extends pedido implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
		 $id=$args['idPedido'];
		$pedido=pedido::traerPedidoID($id);
		
        if(!$pedido)
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No esta el pedido";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }else
        {
            $NuevaRespuesta = $response->withJson($pedido, 200); 
        }     
        return $NuevaRespuesta;
    }


     public function TraerTodos($request, $response, $args) {
      	$todosLospedidos=pedido::TraerTodosLosPedidos();
     	$newresponse = $response->withJson($todosLospedidos, 200);  
    	return $newresponse;
    }


      public function CargarUno($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        $nombre= $ArrayDeParametros['nombreCliente'];
		$idPedido= $ArrayDeParametros['idPedido'];
		$importe= 0;
		$tiempoPedido=$ArrayDeParametros['tiempoPedido'];
		
        
        $mipedido = new pedido();
        $mipedido->nombreCliente=$nombre;
		$mipedido->estado = "en preparacion";
		$mipedido->idPedido = $idPedido;
		$mipedido->importe = 0;
		$mipedido->tiempoPedido = $tiempoPedido;
        $mipedido->AgregarPedido();
       
        //$response->getBody()->write("se guardo el pedido");
        $objDelaRespuesta->respuesta="Se guardo el pedido.";   
        return $response->withJson($objDelaRespuesta, 200);
    }


      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$id=$ArrayDeParametros['id'];
     	$pedido= new pedido();
     	$pedido->id=$id;
     	$cantidadDeBorrados=$pedido->Borrarpedido();

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
	    $mipedido = new pedido();
	    $mipedido->idPedido=$ArrayDeParametros['idPedido'];
	    $mipedido->estado=$ArrayDeParametros['estado'];
	    

	   	$resultado =$mipedido->ModificarPedidoParametro();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
		return $response->withJson($objDelaRespuesta, 200);		
    }


}