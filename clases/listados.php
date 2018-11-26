<?php
include_once "Pedidos.php";
include_once "mesa.php";
include_once "historico.php";
include_once "Empleado.php";

class listados
{
    public function traerTodosLoginEmpleados($request, $response, $args)
    {
        $todosLogin = historico::traerTodosLogin();
        for ($i=0; $i < count($todosLogin); $i++) 
        {
            $todosLogin[$i]->idEmpleado = empleado::TraerEmpleadoID($todosLogin[$i]->idEmpleado)->email;

        }
        //var_dump($todosLogin);
        return $response->withJson($todosLogin, 200);  
    }

}