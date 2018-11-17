<?php
include_once "AccesoDatos.php";
class encuesta
{
    public $id;
    public $idPedido;
    public $nroMesa;
    public $estado_encuesta;
    public $puntos_mesa;
    public $puntos_restaurante;
    public $puntos_mozo;
    public $puntos_cocinero;
    public $comentario;


    public static function altaEncuesta($idPedidoAux,$nroMesaAux,$estadoAux)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into encuestas (idPedido,nroMesa,estado_encuesta)values(:idPedido,:nroMesa,:estado_encuesta)");
        $consulta->bindValue(':nroMesa',$nroMesaAux, PDO::PARAM_STR);
        $consulta->bindValue(':idPedido', $idPedidoAux, PDO::PARAM_INT);
        $consulta->bindValue(':estado_encuesta', $estadoAux, PDO::PARAM_STR);
        $consulta->execute();	
        return $consulta->fetchAll(PDO::FETCH_CLASS, "encuesta");	
    }

    public static function TraerEncuestaPendiente($idPedidoAux,$nroMesaAux) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT  * FROM `encuestas` WHERE `nroMesa`= '$nroMesaAux' AND `idPedido`= '$idPedidoAux' AND `estado_encuesta` = 'Pendiente'");
            $consulta->execute();
            $EmpAux= $consulta->fetchObject('encuesta');
            if($consulta->rowCount() == 0){
                return false;   
            }
            return $EmpAux;		
    }
    public static function TraerTodasEncuestaPendiente() 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT  * FROM `encuestas` WHERE `estado_encuesta` = 'Finalizada'");
            $consulta->execute();
            if($consulta->rowCount() == 0){
                return false;   
            }
            return $consulta->fetchAll(PDO::FETCH_CLASS, "encuesta");	
    }


    public function completarEncuesta($idPedidoAux,$nroMesaAux)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE encuestas set estado_encuesta=:estado_encuesta,puntos_mesa=:puntos_mesa,puntos_restaurante=:puntos_restaurante,puntos_mozo=:puntos_mozo,puntos_cocinero=:puntos_cocinero,comentario=:comentario WHERE `nroMesa`= '$nroMesaAux' AND `idPedido`= '$idPedidoAux'");
            //$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':estado_encuesta', $this->estado_encuesta, PDO::PARAM_STR);
            $consulta->bindValue(':puntos_mesa', $this->puntos_mesa, PDO::PARAM_INT);
            $consulta->bindValue(':puntos_restaurante', $this->puntos_restaurante, PDO::PARAM_INT);
            $consulta->bindValue(':puntos_mozo', $this->puntos_mozo, PDO::PARAM_INT);
            $consulta->bindValue(':puntos_cocinero', $this->puntos_cocinero, PDO::PARAM_INT);
            $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
    }
}