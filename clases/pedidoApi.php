<?php
include_once "Pedidos.php";
include_once "mesa.php";
include_once "encuesta.php";

class pedidoApi
{
    //​ Cuando​ ​ ingresa​ ​ el​ ​ vehículo​ ​ se​ ​ le​ ​ toma​ ​ la​ ​ patente,​ ​ color​ ​ y ​ ​ marca
    public function crearPedido($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $arrayConToken = $request->getHeader('token');
		$token=$arrayConToken[0];
		//$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJkMTE1NDk3MzcxMWIxMzU5ODVkYjVlNzA0NTI5Nzk0ODVlMjE0Yzg4IiwiZGF0YSI6eyJpZCI6MjMsIm5vbWJyZSI6InVzdWFyaW9Vbm8iLCJzZXhvIjoibWFzY3VsaW5vIiwiZW1haWwiOiJ1c2VyQHVzZXIuY29tIiwidHVybm8iOiJtYW5pYW5hIiwicGVyZmlsIjoidXNlciIsImZvdG8iOiJmb3Rvc0VtcGxlYWRvc1wvdXN1YXJpb1Vuby5wbmciLCJhbHRhIjoiMjAxNy0xMi0xOCAxNTo0NDozNCIsImVzdGFkbyI6ImFjdGl2byJ9LCJhcHAiOiJBUEkgUkVTVCBUUC1Fc3RhY2lvbmFtaWVudG8ifQ.Hl41g_LiwUdnL_l5eOSaxgSbEzBDoibnvXvPFq0rgT0";
		$datosToken = AutentificadorJWT::ObtenerData($token);

		if ($datosToken->estado =="suspendido") {
             return $response->withJson("Esta suspendido, pongase en contacto con el administrador",404);
		}
		else {
            $detalleBar = null;
            $detalleCer = null;
            $detalleCoc = null;
            if (!isset($ArrayDeParametros['nroMesa'])) {
                return $response->withJson("Mesa no puede esta vacio",404);   
            }
            $nroMesa= strtolower($ArrayDeParametros['nroMesa']);

            if (!isset($ArrayDeParametros['cliente'])) {
                return $response->withJson("Cliente no puede esta vacio",404);   
            }
            $cliente= strtolower($ArrayDeParametros['cliente']);
    
            if ($this->validarNombre($cliente) == false) {
                return $response->withJson("Error: cliente solo puede contener letas y numeros",404);
            }
            
            if (isset($ArrayDeParametros['detalleBar'])) {
                  $detalleBar= strtolower($ArrayDeParametros['detalleBar']);
            }
            

            if (isset($ArrayDeParametros['detalleCer'])) {
                $detalleCer= strtolower($ArrayDeParametros['detalleCer']);  
            }
           

            if (isset($ArrayDeParametros['detalleCoc'])) {
                $detalleCoc= strtolower($ArrayDeParametros['detalleCoc']);  
            }
            if($detalleBar== null && $detalleCoc == null && $detalleCer == null)
                return $response->withJson("No generaste ningun pedido en ningun sector", 404);

            if (!isset($ArrayDeParametros['importe'])) {
                return $response->withJson("importe no puede esta vacio",404);   
            }
            $importe= strtolower($ArrayDeParametros['importe']);

            $pedidoAux = new pedido();
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $pedidoAux->fecha = date("Y-m-d");
            $pedidoAux->nroMesa = $nroMesa;
            $pedidoAux->cliente = $cliente;
            $pedidoAux->importe = $importe;
            $pedidoAux->detalleBar = $detalleBar;
            if ($detalleBar != null) {
                $pedidoAux->estadoBar = "Pendiente";
            }
            
            $pedidoAux->detalleCer = $detalleCer;
            if ($detalleCer != null) {
                $pedidoAux->estadoCer = "Pendiente";
            }
            
            $pedidoAux->detalleCoc = $detalleCoc;
            if ($detalleCoc != null) {
                $pedidoAux->estadoCoc = "Pendiente";
            }
            

            

            
            $foto = $this->obtenerArchivo($cliente."_".$nroMesa."");
				
            if($foto != NULL)
            {
                $directorio = 'fotos/';
                move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
                if (filesize($foto)>500000) {
                    $pedidoAux->foto =foto::tamImagenGlobal($foto,$cliente,$directorio);
                    
                }
                else {
                    $pedidoAux->foto = $foto;
                }
                //ACA falta buscar mesas disponibles
                $pedidoAux->InsertarVehiculoParametros();
                mesa::ocuparMesa($nroMesa,"con cliente esperando pedido");
                //ACA falta cambiar estado a mesa (ocupada)
                return $response->withJson("El pedido con se genero correctamente",200);

            }
            else {
                return $response->withJson("Error al generar pedido",404);
            }
		}
   
    }

    public function validarNombre($cadena){ 
        $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 "; 
        for ($i=0; $i<strlen($cadena); $i++){ 
            if (strpos($permitidos, substr($cadena,$i,1))===false){ 
            //no es válido; 
            return false; 
            } 
        }  
        //si estoy aqui es que todos los caracteres son validos 
        return true; 
    }

    public function obtenerArchivo($nombre) 
	{
        if(!isset($_FILES['foto']))
        {
            throw new Exception('Error: No existe foto');
        }
        if ( 0 < $_FILES['foto']['error'] ) {
			return null;
		}
		else {
            $alta= date("YmdHis");
            $foto = $_FILES['foto']['name'];
			
            $extension= explode(".", $foto);
            $tipo = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if($tipo != "jpg" && $tipo != "jpeg" && $tipo != "png") {
                throw new Exception('Error: de formato, solo se acepta jpg jpeg png');
            }

            $nombreNuevo = 'fotos/'.$nombre."_".$alta.".".strtolower($extension[1]);
            return $nombreNuevo;
		}
    }

    public function traerTodos($request, $response, $args) 
	{
        $todosPedidos = pedido::TraerTodoLosPedidos();
        return $response->withJson($todosPedidos, 200);  

    }
    public function traerTodosConEstadoMesa($request, $response, $args) 
	{
        $todosPedidos = pedido::TraerTodoLosPedidosConEstadoMesa();
        return $response->withJson($todosPedidos, 200);  

    }

    public function traerUno($request, $response, $args) 
	{
        $ArrayDeParametros = $request->getParsedBody();
        $arrayConToken = $request->getHeader('token');
		$token=$arrayConToken[0];
		//$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJkMTE1NDk3MzcxMWIxMzU5ODVkYjVlNzA0NTI5Nzk0ODVlMjE0Yzg4IiwiZGF0YSI6eyJpZCI6MjMsIm5vbWJyZSI6InVzdWFyaW9Vbm8iLCJzZXhvIjoibWFzY3VsaW5vIiwiZW1haWwiOiJ1c2VyQHVzZXIuY29tIiwidHVybm8iOiJtYW5pYW5hIiwicGVyZmlsIjoidXNlciIsImZvdG8iOiJmb3Rvc0VtcGxlYWRvc1wvdXN1YXJpb1Vuby5wbmciLCJhbHRhIjoiMjAxNy0xMi0xOCAxNTo0NDozNCIsImVzdGFkbyI6ImFjdGl2byJ9LCJhcHAiOiJBUEkgUkVTVCBUUC1Fc3RhY2lvbmFtaWVudG8ifQ.Hl41g_LiwUdnL_l5eOSaxgSbEzBDoibnvXvPFq0rgT0";
		$datosToken = AutentificadorJWT::ObtenerData($token);

		if ($datosToken->estado =="suspendido") {
             return $response->withJson("Esta suspendido, pongase en contacto con el administrador",404);
		}
		else {
            if (empty($args)) {
                return $response->withJson("idPedido no puede esta vacio",404);   
            }
            $idPedido= $args['idPedido'];
            $todosPedidos = pedido::TraerPedidoIDConEstadoMesa($idPedido);
            return $response->withJson($todosPedidos, 200);  
        }


    }
    //en preparación
    public function modificarUno($request, $response, $args) 
    {
            $ArrayDeParametros = $request->getParsedBody();
            $arrayConToken = $request->getHeader('token');
            $token=$arrayConToken[0];
            $datosToken = AutentificadorJWT::ObtenerData($token);
    
            if ($datosToken->estado =="suspendido") {
                 return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
            }
            if (!isset($ArrayDeParametros['idPedido'])) {
                return $response->withJson('Error al modificar: Debe ingresar ID de pedido',404);
            }
            $idPedido= $ArrayDeParametros['idPedido'];
            $objDelaRespuesta= new stdclass();
            $pedModificar = pedido::TraerPedidoID($idPedido);

            if ($pedModificar != false) {

               
                
                if (isset($ArrayDeParametros['estadoBar'])&& isset($ArrayDeParametros['tiempo_estimado_bar'])) {

                    if($pedModificar->detalleBar!=null){
                    $estadoBar = strtolower($ArrayDeParametros['estadoBar']);
                    $tiempo_estimado_bar = $ArrayDeParametros['tiempo_estimado_bar'];

                    $pedModificar->estadoBar = $estadoBar;
                    $pedModificar->tiempo_estimado_bar = $tiempo_estimado_bar;
                    $pedModificar->idEmpladoBar = $datosToken->id;
                    if ($pedModificar->estadoBar== "" || !isset($pedModificar->estadoBar)) {
                        return $response->withJson('Error: estadoBar no puede esta vacio',404);
                    }
                    if ($pedModificar->tiempo_estimado_bar== "" || !isset($pedModificar->tiempo_estimado_bar)) {
                        return $response->withJson("Error: tiempo_estimado_bar no puede esta vacio",404);
                    }
                    $pedModificar->modificarBarID($idPedido);
                    $objDelaRespuesta->estadoBar =$estadoBar;
                    $objDelaRespuesta->msj = "se modifico el estado en el Bar del pedido numero ".$idPedido;
                    return $response->withJson($objDelaRespuesta->msj,200);
                }
                    else
                     $objDelaRespuesta->msj = "No hay pedido de Bar en el pedido ".$idPedido;
                
            }
                
                 
                if (isset($ArrayDeParametros['estadoCer']) && isset($ArrayDeParametros['tiempo_estimado_cer'])) {
                    if($pedModificar->detalleCer!=null){
                    $estadoCer = strtolower($ArrayDeParametros['estadoCer']);
                    $tiempo_estimado_cer = $ArrayDeParametros['tiempo_estimado_cer'];

                    $pedModificar->estadoCer = $estadoCer;
                    $pedModificar->tiempo_estimado_cer = $tiempo_estimado_cer;
                    $pedModificar->idEmpladoCer = $datosToken->id;
                    if ($pedModificar->estadoCer== "" || !isset($pedModificar->estadoCer)) {
                        return $response->withJson("Error: estadoCer no puede esta vacio",404);
                    }
                    if ($pedModificar->tiempo_estimado_cer== "" || !isset($pedModificar->tiempo_estimado_cer)) {
                        return $response->withJson("Error: tiempo_estimado_cer no puede esta vacio",404);
                    }
                    $pedModificar->modificarCerID($idPedido);
                    $objDelaRespuesta->estadoCer =$estadoCer;
                    $objDelaRespuesta->msj = "se modifico el estado de la cerveza del pedido numero ".$idPedido;
                    return $response->withJson($objDelaRespuesta->msj,200);
                }
                    else
                     $objDelaRespuesta->msj = "No hay pedido de cerveza en el pedido ".$idPedido;
                }
                
                
                
                
                if (isset($ArrayDeParametros['estadoCoc']) && isset($ArrayDeParametros['tiempo_estimado_coc'])) {
                    if($pedModificar->detalleCoc !=null){
                    $estadoCoc = strtolower($ArrayDeParametros['estadoCoc']);
                    $tiempo_estimado_coc = $ArrayDeParametros['tiempo_estimado_coc'];

                    $pedModificar->estadoCoc = $estadoCoc;
                    $pedModificar->tiempo_estimado_coc = $tiempo_estimado_coc;
                    $pedModificar->idEmpladoCoc = $datosToken->id;
                    if ($pedModificar->estadoCoc== "" || !isset($pedModificar->estadoCoc)) {
                        return $response->withJson('Error: estadoCoc no puede esta vacio',404);
                    }
                    if ($pedModificar->tiempo_estimado_coc== "" || !isset($pedModificar->tiempo_estimado_coc)) {
                        return $response->withJson("Error: tiempo_estimado_coc no puede esta vacio",404);
                    }
                    $pedModificar->modificarCocID($idPedido);
                    $objDelaRespuesta->estadoCoc =$estadoCoc;
                    $objDelaRespuesta->msj = "se modifico el estado en la cocina del pedido numero ".$idPedido;

                }
                    else
                     $objDelaRespuesta->msj = "No hay pedido para la cocina en el pedido ".$idPedido;  
            }
                         

            }
            else {
                return $response->withJson('Error no existe el numero de pedido',404);
            }
            return $response->withJson($objDelaRespuesta, 202);
            
    }
    
    public function finalizarPedido($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $arrayConToken = $request->getHeader('token');
        $token=$arrayConToken[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);

        if ($datosToken->estado =="suspendido") {
             return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
        }
        if (!isset($ArrayDeParametros['idPedido'])) {
            return $response->withJson('Error al finalizar: Debe ingresar ID de pedido',404);
        }
        $idPedido= $ArrayDeParametros['idPedido'];
        $objDelaRespuesta= new stdclass();
        $pedFinalizar = pedido::TraerPedidoID($idPedido);
        if (isset($ArrayDeParametros['sector'])) {
            $sector = $ArrayDeParametros['sector'];

            switch ($sector) {
                case 'cocina':

                    $tiempo_final_coc =$this->calculoTiempo($pedFinalizar->tiempo_estimado_coc);
            $estadoCoc = "listo para servir";
            $pedFinalizar->estadoCoc = $estadoCoc;
            $pedFinalizar->tiempo_final_coc = $tiempo_final_coc;
            //echo ($pedFinalizar->tiempo_final_coc);
            $pedFinalizar->finalizarCocinarID($idPedido);
            return $response->withJson('El pedido de cocina esta listo para servir',200);

                    # code...
                    break;
                case 'barra':
              
                $estadoBar = "listo para servir";
                    $tiempo_final_bar =$this->calculoTiempo($pedFinalizar->tiempo_estimado_bar);
            $estadoBar = "listo para servir";
            $pedFinalizar->estadoBar = $estadoBar;
            $pedFinalizar->tiempo_final_bar = $tiempo_final_bar;
            //echo ($pedFinalizar->tiempo_final_bar);
            $pedFinalizar->finalizarBartenderID($idPedido);
            return $response->withJson('El pedido de bartender esta listo para servir',200);


                    # code...
                    break;

                case 'cerveza':
                $estadoCer = "listo para servir";
                    $tiempo_final_cer =$this->calculoTiempo($pedFinalizar->tiempo_estimado_cer);
            $estadoCer = "listo para servir";
            $pedFinalizar->estadoCer = $estadoCer;
            $pedFinalizar->tiempo_final_cer = $tiempo_final_cer;
            //echo ($pedFinalizar->tiempo_final_cer);
            $pedFinalizar->finalizarCerveceriaID($idPedido);
            return $response->withJson('El pedido de cerveceria esta listo para servir',200);
                            # code...
                    break;
                default:

                    # code...
                    break;
            }
            
        }
        

        return $response->withJson('No se modifico ningun pedido',404);

    }
    
    public function calculoTiempo($tiempoPedido){
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        (int)$tiempoEstimado = $tiempoPedido;
        $ahora= (int)date("i");
        return $resultado = $tiempoEstimado - $ahora; 
    }
    public function tiempoEstimado($request, $response, $args){
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        $ArrayDeParametros = $request->getParsedBody();
        if (!isset($ArrayDeParametros['idPedido'])) {
            return $response->withJson('Error al finalizar: Debe ingresar ID de pedido',404);
        }
        $objDelaRespuesta= new stdclass();
        $idPedido= $ArrayDeParametros['idPedido'];
        $pedFinalizar = pedido::TraerPedidoID($idPedido);
        $ahora= (int)date("i");
        
        if ($pedFinalizar->tiempo_estimado_bar != NULL) {
            $objDelaRespuesta->tiempoBar = $pedFinalizar->tiempo_estimado_bar - $ahora;
        }
        else {
            $objDelaRespuesta->tiempoBar ="Sin pedido";
        }
        if ($pedFinalizar->tiempo_estimado_coc != NULL) {
            $objDelaRespuesta->tiempoCoc = $pedFinalizar->tiempo_estimado_coc - $ahora;
        }
        else {
            $objDelaRespuesta->tiempoCoc ="Sin pedido";
        }
        if ($pedFinalizar->tiempo_estimado_cer != NULL) {
            $objDelaRespuesta->tiempoCer = $pedFinalizar->tiempo_estimado_cer - $ahora;
        }
        else {
            $objDelaRespuesta->tiempoCer ="Sin pedido";
        }
        return $response->withJson($objDelaRespuesta,200);
    }

        //return $resultado = $tiempoEstimado - $ahora;


    public function cambiarEstadoPedido($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $arrayConToken = $request->getHeader('token');
        $token=$arrayConToken[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);

        if ($datosToken->estado =="suspendido") {
             return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
        }
        if (!isset($ArrayDeParametros['idPedido'])) {
            return $response->withJson('Error al finalizar: Debe ingresar ID de pedido',404);
        }
        $idPedido= $ArrayDeParametros['idPedido'];
        $objDelaRespuesta= new stdclass();
        $pedFinalizar = pedido::TraerPedidoID($idPedido);
        if (isset($ArrayDeParametros['estado'])) {
            $estado = $ArrayDeParametros['estado'];
            //$pedFinalizar->estado = $estado;
            //$pedFinalizar->cambiarEstadoPedidoGlobal($idPedido);
            mesa::ocuparMesa($pedFinalizar->nroMesa,$estado);
            if ($estado == "finalizado") {
                //cambiarTodosEstadoSector
                pedido::cambiarTodosEstadoSector($idPedido,"Finalizado");
                encuesta::altaEncuesta($idPedido,$pedFinalizar->nroMesa,"Pendiente");
            }
            return $response->withJson('Se cambia estado global del pedido a '.$estado,200);
        }
        return $response->withJson('No se modifico ningun pedido general',404);
    }

    //operacionesSector
    public function operacionesSector($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $arrayConToken = $request->getHeader('token');
        $token=$arrayConToken[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);

        if ($datosToken->estado =="suspendido") {
             return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
        }
        $objDelaRespuesta= new stdclass();


            $objDelaRespuesta->estadoBar= pedido::TraerCantidadOperacionesSector("estadoBar");
            $objDelaRespuesta->estadoCoc= pedido::TraerCantidadOperacionesSector("estadoCoc");
            $objDelaRespuesta->estadoCer= pedido::TraerCantidadOperacionesSector("estadoCer");
            return $response->withJson($objDelaRespuesta, 200); 
    }

        //operacionesSector
        public function masPedidos($request, $response, $args)
        {
            $ArrayDeParametros = $request->getParsedBody();
            $arrayConToken = $request->getHeader('token');
            $token=$arrayConToken[0];
            $datosToken = AutentificadorJWT::ObtenerData($token);
    
            if ($datosToken->estado =="suspendido") {
                 return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
            }
            $objDelaRespuesta= new stdclass();
    
                
                
                $objDelaRespuesta->detalleBar= pedido::TraerMasVendidosSector("detalleBar");                
                $objDelaRespuesta->detalleCoc= pedido::TraerMasVendidosSector("detalleCoc");          
                $objDelaRespuesta->detalleCer= pedido::TraerMasVendidosSector("detalleCer");
        
                return $response->withJson($objDelaRespuesta, 200); 
            
        }

        public function BorrarUno($request, $response, $args)
        {
            $ArrayDeParametros = $request->getParsedBody();
            $arrayConToken = $request->getHeader('token');
            $token=$arrayConToken[0];
            $datosToken = AutentificadorJWT::ObtenerData($token);
            
            if ($datosToken->estado =="suspendido") {
                 return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
            }
            if (!isset($ArrayDeParametros['idPedido'])) {
                return $response->withJson('Error al finalizar: Debe ingresar ID de pedido',404);
            }
            $idPedido= $ArrayDeParametros['idPedido'];
            $objDelaRespuesta= new stdclass();
            $pedFinalizar = pedido::TraerPedidoID($idPedido);
            if ($pedFinalizar) {
                mesa::ocuparMesa($pedFinalizar->nroMesa,"libre");
                pedido::cambiarTodosEstadoSector($idPedido,"Cancelado");
                return $response->withJson('El pedido fue cancelado',200);
            }
            return $response->withJson('No se cancelo el pedido',404);
        }

                //operacionesSector
        public function usoMesas($request, $response, $args)
        {
            $ArrayDeParametros = $request->getParsedBody();
            $arrayConToken = $request->getHeader('token');
            $token=$arrayConToken[0];
            $datosToken = AutentificadorJWT::ObtenerData($token);
    
            if ($datosToken->estado =="suspendido") {
                 return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
            }
            $objDelaRespuesta= new stdclass();
    
                $Mesas= pedido::TraerUsosMesas();
                return $response->withJson($Mesas, 200); 
            
        }

        public function facturacionMesas($request, $response, $args)
        {
            $ArrayDeParametros = $request->getParsedBody();
            $arrayConToken = $request->getHeader('token');
            $token=$arrayConToken[0];
            $datosToken = AutentificadorJWT::ObtenerData($token);
    
            if ($datosToken->estado =="suspendido") {
                 return $response->withJson('Esta suspendido, pongase en contacto con el administrador',404);
            }
            $objDelaRespuesta= new stdclass();
    
            if (isset($ArrayDeParametros['desde']) && isset($ArrayDeParametros['hasta'])) 
            {
                $desde= $ArrayDeParametros['desde'];
                $hasta= $ArrayDeParametros['hasta'];
    
                $Mesas= pedido::TraerFacturacionMesasFechas($desde,$hasta);
                return $response->withJson($Mesas, 200); 
                
    
            }
            if (isset($ArrayDeParametros['desde']) && !isset($ArrayDeParametros['hasta'])) {
                    $desde= $ArrayDeParametros['desde'];
    
                    $Mesas= pedido::TraerFacturacionMesasFechas($desde,"");
                    return $response->withJson($Mesas, 200); 
    
            }
            if (!isset($ArrayDeParametros['desde']) && isset($ArrayDeParametros['hasta'])) {
                    $hasta= $ArrayDeParametros['hasta'];
    
    
                    $Mesas= pedido::TraerFacturacionMesasFechas("",$hasta);
                    return $response->withJson($Mesas, 200); 
    
            }
            if (!isset($ArrayDeParametros['desde']) && !isset($ArrayDeParametros['hasta'])) {
                $Mesas= pedido::TraerFacturacionMesasFechas("","");
                return $response->withJson($Mesas, 200); 
            }
        }

        public function encuesta($request, $response, $args)
        {
            $ArrayDeParametros = $request->getParsedBody();
            if (!isset($ArrayDeParametros['idPedido'])) {
                return $response->withJson("idPedido no puede esta vacio",404);   
            }
            $idPedido= strtolower($ArrayDeParametros['idPedido']);
            if (!isset($ArrayDeParametros['nroMesa'])) {
                return $response->withJson("nroMesa no puede esta vacio",404);   
            }
            $nroMesa= strtolower($ArrayDeParametros['nroMesa']);
    
            $todosPedidos = encuesta::TraerEncuestaPendiente($idPedido,$nroMesa);
            return $response->withJson($todosPedidos, 200); 

        }
        public function finalizarEncuesta($request, $response, $args)
        {

            $ArrayDeParametros = $request->getParsedBody();
            if (!isset($ArrayDeParametros['idPedido'])) {
                return $response->withJson("idPedido no puede esta vacio",404);   
            }
            $idPedido= strtolower($ArrayDeParametros['idPedido']);

            if (!isset($ArrayDeParametros['nroMesa'])) {
                return $response->withJson("nroMesa no puede esta vacio",404);   
            }
            $nroMesa= strtolower($ArrayDeParametros['nroMesa']);

            if (!isset($ArrayDeParametros['puntos_mesa'])) {
                return $response->withJson("puntos_mesa no puede esta vacio",404);   
            }
            $puntos_mesa= strtolower($ArrayDeParametros['puntos_mesa']);

            if (!isset($ArrayDeParametros['puntos_restaurante'])) {
                return $response->withJson("puntos_restaurante no puede esta vacio",404);   
            }
            $puntos_restaurante= strtolower($ArrayDeParametros['puntos_restaurante']);

            if (!isset($ArrayDeParametros['puntos_mozo'])) {
                return $response->withJson("puntos_mozo no puede esta vacio",404);   
            }
            $puntos_mozo= strtolower($ArrayDeParametros['puntos_mozo']);

            if (!isset($ArrayDeParametros['puntos_cocinero'])) {
                return $response->withJson("puntos_cocinero no puede esta vacio",404);   
            }
            $puntos_cocinero= strtolower($ArrayDeParametros['puntos_cocinero']);

            if (!isset($ArrayDeParametros['comentario'])) {
                return $response->withJson("comentario no puede esta vacio",404);   
            }
            $comentario= strtolower($ArrayDeParametros['comentario']);
            $encuestaAux = new pedido();
            $encuestaAux = encuesta::TraerEncuestaPendiente($idPedido,$nroMesa);
            if ($encuestaAux->estado_encuesta == "Pendiente") {
                $encuestaAux->estado_encuesta = "Finalizada";
                $encuestaAux->puntos_mesa = $puntos_mesa;
                $encuestaAux->puntos_restaurante = $puntos_restaurante;
                $encuestaAux->puntos_mozo = $puntos_mozo;
                $encuestaAux->puntos_cocinero = $puntos_cocinero;
                $encuestaAux->comentario = $comentario;
                $encuestaAux->completarEncuesta($idPedido,$nroMesa);
                return $response->withJson("Gracias por responder la encuesta",200);
            }
            return $response->withJson("No se completo la encuesta",404);


        }
        public function traerTodasEncuestas($request, $response, $args) 
        {
            $todosencuestas = encuesta::TraerTodasEncuestaPendiente();
            return $response->withJson($todosencuestas, 200);  
    
        }
}