<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'vendor/autoload.php';
require_once 'clases/AccesoDatos.php';
require_once 'clases/empleadoApi.php';
require_once 'clases/pedidoApi.php';
require_once 'clases/listados.php';
require_once 'clases/loginApi.php';
require_once 'clases/mesaApi.php';
require_once 'clases/MWparaCORS.php';
require_once 'clases/MWparaAutentificar.php';
require_once 'clases/excel.php';
require_once 'clases/pdf.php';
require_once 'clases/foto.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/*
¡La primera línea es la más importante! A su vez en el modo de 
desarrollo para obtener información sobre los errores
 (sin él, Slim por lo menos registrar los errores por lo que si está utilizando
  el construido en PHP webserver, entonces usted verá en la salida de la consola 
  que es útil).

  La segunda línea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera más predecible.
*/

$app = new \Slim\App(["settings" => $config]);
$app->add(function($request, $response, $next){
  $response = $next($request, $response);

  return $response
              ->withHeader('Access-Control-Allow-Origin', '*')
              ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
              ->withHeader('Access-Control-Allow-Methods', 'GET, POST');
});


$app->get('/', function (Request $request, Response $response) {    
  $response->getBody()->write("Bienvenido al sistema de Gestion de Comandas!!!");
  return $response;

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->post('/login', \loginApi::class . ':login')->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->group('/empleado', function () {

     
     $this->post('/alta', \empleadoApi::class . ':CargarUno');
     $this->get('/', \empleadoApi::class . ':traerTodos');
     $this->get('/traerUno/{email}', \empleadoApi::class . ':traerUno');
     $this->get('/suspendidos', \empleadoApi::class . ':traerTodosSuspendidos');
     $this->post('/borrar', \empleadoApi::class . ':BorrarUno');
     $this->post('/modificar', \empleadoApi::class . ':modificarUno');
     $this->post('/suspender', \empleadoApi::class . ':suspenderUno');
     $this->post('/activar', \empleadoApi::class . ':activarUno');

     //$this->post('/cantidadOperaciones/[{email}]', \empleadoApi::class . ':operacionesEmpleado');

     $this->post('/historicoLogin/[{email}]', \empleadoApi::class . ':loginEmpleado');

 })->add(\MWparaAutentificar::class . ':VerificarAdmin')->add(\MWparaCORS::class . ':HabilitarCORSTodos');

 $app->group('/pedido', function () {

  $this->post('/alta', \pedidoApi::class . ':crearPedido')->add(\MWparaAutentificar::class . ':VerificarSocioMozo');
  $this->get('/', \pedidoApi::class . ':traerTodosConEstadoMesa');
  $this->get('/traerUno/{idPedido}', \pedidoApi::class . ':traerUno');
  $this->post('/cancelar', \pedidoApi::class . ':BorrarUno');
  $this->post('/modificar', \pedidoApi::class . ':modificarUno');
  $this->post('/listo_servir', \pedidoApi::class . ':finalizarPedido');
  $this->post('/finalizar', \pedidoApi::class . ':cambiarEstadoPedido')->add(\MWparaAutentificar::class . ':VerificarSocioMozo');
  $this->get('/operacionesSector', \pedidoApi::class . ':operacionesSector');
  $this->get('/operacionesEmpleado/{email}', \empleadoApi::class . ':operacionesEmpleado');
  $this->post('/masVendidos', \pedidoApi::class . ':masPedidos');
  $this->post('/tiempoEstimado', \pedidoApi::class . ':tiempoEstimado');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->group('/mesa', function () {

  $this->get('/', \mesaApi::class . ':traerTodos');
  $this->get('/disponibles', \mesaApi::class . ':traerTodosDisponibles');
    $this->get('/usoMesas', \pedidoApi::class . ':usoMesas');
  $this->post('/facturacionMesas', \pedidoApi::class . ':facturacionMesas');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');


$app->group('/encuesta', function (){

	$this->post('/', \pedidoApi::class . ':encuesta');
	$this->post('/finalizarEncuesta', \pedidoApi::class . ':finalizarEncuesta');
	$this->get('/todasEncuestas', \pedidoApi::class . ':traerTodasEncuestas');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->group('/listados', function () {

  $this->get('/empleados/login', \listados::class . ':traerTodosLoginEmpleados');
  $this->get('/exportExcel/[{id}]', \excel::class . ':loginUsurioExcel');

})->add(\MWparaAutentificar::class . ':VerificarAdmin')->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->run();