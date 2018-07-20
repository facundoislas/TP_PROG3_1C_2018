<?php
class pedido
{
	public $id;
 	public $nombreCliente;
	  public $importe;
	  public $estado;
	  public $idPedido;
	  public $tiempoPedido;

/*
public function Insertarpedido()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (nombre,importe)values('$this->nombre','$this->importe')");
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
				

	 }
*/

	 public function AgregarPedido()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (nombreCliente,importe, estado, idPedido, tiempoPedido)values(:nombreCliente, :importe , :estado, :idPedido, :tiempoPedido )");
				$consulta->bindValue(':nombreCliente',$this->nombreCliente, PDO::PARAM_INT);
				$consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_STR);
				$consulta->bindValue(':tiempoPedido',$this->tiempoPedido, PDO::PARAM_INT);
				$consulta->bindValue(':estado',$this->estado, PDO::PARAM_INT);
				$consulta->bindValue(':importe',$this->importe, PDO::PARAM_INT);
				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }
	 public function Guardarpedido()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarpedidoParametros();
	 		}else {
	 			$this->AgregarPedido();
	 		}

	 }


	 public static function TraerTodosLosPedidos()
	 {
			 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			 $consulta =$objetoAccesoDato->RetornarConsulta("select id,nombreCliente,importe,estado,tiempoPedido,idPedido from pedidos");
			 $consulta->execute();			
			 return $consulta->fetchAll(PDO::FETCH_CLASS, "pedido");		
	 }

	 
  	public function Borrarpedido()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from pedidos 				
				WHERE idPedido=:idPedido");	
				$consulta->bindValue(':idPedido',$this->idPedido, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }
/*
	public function ModificarPedido()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update pedidos 
				set estrado='$this->nombre',
				importe='$this->importe'
				WHERE id='$this->id'");
			return $consulta->execute();

	 }
	*/
  
	 

	  public function ModificarPedidoParametro()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update pedidos 
				set estado=:estado
				WHERE idPedido=:idPedido");
			$consulta->bindValue(':idPedido',$this->idPedido, PDO::PARAM_INT);
			$consulta->bindValue(':estado',$this->estado, PDO::PARAM_INT);
		

			return $consulta->execute();
	 }

	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->nombre."  ".$this->importe."  ".$this->email;
	}


	public function traerPedidoID($id)
	{
			
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombreCliente,importe,estado,tiempoPedido,idPedido from pedidos where idPedido = '".$id."'");
			$consulta->execute();
			$pedidoBuscado= $consulta->fetchObject('pedido');
			
			return $pedidoBuscado;	
	}

}