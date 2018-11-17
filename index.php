<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '/composer/vendor/autoload.php';
require_once '/Clases/AccesoDatos.php';
require_once '/Clases/empleadoApi.php';
require_once '/Clases/pedidoApi.php';
require_once '/Clases/mesaApi.php';
require_once '/Clases/loginApi.php';
//require_once '/Clases/AutentificadorJWT.php';
require_once '/Clases/MWparaCORS.php';
//require_once '/Clases/usuario.php';
require_once '/Clases/MWparaAutentificar.php';

//require_once '/clases/MWparaAutentificar.php';

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



$app->get('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("Bienvenido!!!");
  return $response;

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');
//(POST email y clave)
$app->post('/login', \loginApi::class . ':login')->add(\MWparaCORS::class . ':HabilitarCORSTodos');
$app->post('/datosToken[/]', \loginApi::class . ':datosToken')->add(\MWparaAutentificar::class . ':VerificarUser')->add(\MWparaCORS::class . ':HabilitarCORSTodos');
$app->post('/Encuesta[/]', \pedidoApi::class . ':encuesta')->add(\MWparaCORS::class . ':HabilitarCORSTodos');
$app->post('/finalizarEncuesta[/]', \pedidoApi::class . ':finalizarEncuesta')->add(\MWparaCORS::class . ':HabilitarCORSTodos');
$app->post('/todasEncuestas[/]', \pedidoApi::class . ':traerTodasEncuestas')->add(\MWparaCORS::class . ':HabilitarCORSTodos');


/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/personal', function () {
 
  $this->get('/', \empleadoApi::class . ':traerTodos');//->add(\MWparaCORS::class . ':HabilitarCORSTodos');
 
  $this->get('/{user}', \empleadoApi::class . ':traerUno');//->add(\MWparaCORS::class . ':HabilitarCORSTodos');

  $this->post('/alta', \empleadoApi::class . ':CargarUno');

  $this->delete('/', \empleadoApi::class . ':BorrarUno');//->add(\MWparaAutentificar::class . ':VerificarUsuario');

  $this->put('/suspender', \empleadoApi::class . ':Suspender');//->add(\MWparaAutentificar::class . ':VerificarUsuario');

  $this->put('/modificar', \empleadoApi::class . ':ModificarUno');//->add(\MWparaAutentificar::class . ':VerificarUsuario');

  $this->put('/activar', \empleadoApi::class . ':Activar');//->add(\MWparaAutentificar::class . ':VerificarUsuario');

  $this->get('/suspendidos/', \empleadoApi::class . ':TraerSuspendidos');//->add(\MWparaAutentificar::class . ':VerificarUsuario');

  $this->get('/activos/', \empleadoApi::class . ':TraerActivos');//->add(\MWparaAutentificar::class . ':VerificarUsuario');
 
     
});//->add(\MWparaCORS::class . ':HabilitarCORS8080');



$app->group('/pedido', function () {

  $this->post('/alta[/]', \pedidoApi::class . ':crearPedido');
  //$this->get('/', \pedidoApi::class . ':traerTodos');
  $this->get('/', \pedidoApi::class . ':traerTodosConEstadoMesa');
  $this->post('/traerUno[/]', \pedidoApi::class . ':traerUno');
  $this->post('/cancelar[/]', \pedidoApi::class . ':BorrarUno');
  $this->post('/modificar[/]', \pedidoApi::class . ':modificarUno');
  $this->post('/finalizar[/]', \pedidoApi::class . ':finalizarPedido');
  $this->post('/estadoGlobal[/]', \pedidoApi::class . ':cambiarEstadoPedido');
  $this->post('/operacionesSector[/]', \pedidoApi::class . ':operacionesSector');
  $this->post('/operacionesEmpleado[/]', \pedidoApi::class . ':operacionesEmpleado');
  $this->post('/masVendidos[/]', \pedidoApi::class . ':masPedidos');
  $this->post('/usoMesas[/]', \pedidoApi::class . ':usoMesas');
  $this->post('/facturacionMesas[/]', \pedidoApi::class . ':facturacionMesas');
  $this->post('/tiempoEstimado[/]', \pedidoApi::class . ':tiempoEstimado');

  $this->get('/verImagen/[{email}]', \foto::class . ':verImagen');

});

$app->group('/mesa', function () {

  $this->get('/', \mesaApi::class . ':traerTodos');
  $this->get('/disponibles/', \mesaApi::class . ':traerTodosDisponibles');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

  $app->group('/encuesta', function () {
	
	$this->get('/', \encuestaApi::class . ':TraerEncuestas');

	$this->post('/llenar', \encuestaApi::class . ':CargarEncuesta');
	   
  });
$app->run();