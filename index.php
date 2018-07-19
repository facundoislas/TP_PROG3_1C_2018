<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '/composer/vendor/autoload.php';
require_once '/Clases/AccesoDatos.php';
require_once '/Clases/empleadoApi.php';
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

  $this->delete('/borrar', \empleadoApi::class . ':BorrarUno');//->add(\MWparaAutentificar::class . ':VerificarUsuario');

  $this->put('/suspender', \empleadoApi::class . ':ModificarUno');//->add(\MWparaAutentificar::class . ':VerificarUsuario');
     
});//->add(\MWparaCORS::class . ':HabilitarCORS8080');



$app->run();