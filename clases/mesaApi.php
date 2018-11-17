<?php
include_once "mesa.php";

class mesaApi
{

    public function traerTodos($request, $response, $args) 
	{
        $todasMesas = mesa::TraerTodas();
        return $response->withJson($todasMesas, 200);  

    }
    public function traerTodosDisponibles($request, $response, $args) 
	{
        $todasMesas = mesa::TraerTodasDispobibles();
        return $response->withJson($todasMesas, 200);  

    }


}