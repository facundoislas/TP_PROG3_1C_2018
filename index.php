<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '/composer/vendor/autoload.php';
require_once '/Clases/AccesoDatos.php';
require_once '/Clases/empleadoApi.php';
require_once '/Clases/pedidoApi.php';
require_once '/Clases/mesasApi.php';
//require_once '/Clases/AutentificadorJWT.php';
//require_once '/Clases/MWparaCORS.php';
//require_once '/Clases/usuario.php';
//require_once '/Clases/MWparaAutentificar.php';

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



$app->post('/login/', function (Request $request, Response $response) {

	$token="";
	$ArrayDeParametros = $request->getParsedBody();

	$usuario=$ArrayDeParametros['usuario'];
	$clave=$ArrayDeParametros['clave'];

	$usuario = usuario::TraerUnUsuario($usuario, $clave);
	
	if($usuario != null)
	{
		$datos=array('usuario'=>$usuario->user,'perfil'=>$usuario->perfil, 'clave'=>$usuario->pass);
		$token=AutentificadorJWT::CrearToken($datos);
		$retorno=array('datos'=>$datos, 'token'=>$token);
		$newResponse = $response->withJson($retorno,200);

	}
	else
	{
		$retorno=array('error'=> "Usuario no Valido");
		$newResponse = $response->withJson($retorno,401);
	}
	return $newResponse;

});

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



$app->group('/pedidos', function () {
	
	$this->get('/', \pedidosApi::class . ':traerTodos');

	$this->get('/{idPedido}', \pedidosApi::class . ':traerUno');

	$this->get('/{estado}', \pedidosApi::class . ':traerEstado');
  
	$this->post('/agregar', \pedidosApi::class . ':CargarUno');
  
	$this->delete('/borrar', \pedidosApi::class . ':BorrarUno');
  
	$this->put('/modificar', \pedidosApi::class . ':ModificarUno');
	   
  });

  $app->group('/mesas', function () {
	
	$this->get('/', \mesasApi::class . ':traerTodos');

	$this->get('/{idMesa}', \mesasApi::class . ':traerUno');
  
	$this->post('/agregar', \mesasApi::class . ':CargarUno');
  
	$this->delete('/borrar', \mesasApi::class . ':BorrarUno');
  
	$this->put('/cambiarEstado', \mesasApi::class . ':ModificarUno');

	$this->put('/cerrar', \mesasApi::class . ':Cerrar');
	   
  });

$app->run();