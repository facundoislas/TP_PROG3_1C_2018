<?php
class mesa
{
	public $id;
 	public $estado;
	public $idMesa;

	 public function AgregarMesa()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mesas (estado, idMesa)values('con cliente esperando', :idMesa )");
				$consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_STR);
				
				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }
	 public function Guardarmesa()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarMesaParametros();
	 		}else {
	 			$this->AgregarMesa();
	 		}

	 }


	 public static function TraerTodasLasMesas()
	 {
			 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			 $consulta =$objetoAccesoDato->RetornarConsulta("select id,estado,idMesa from mesas");
			 $consulta->execute();			
			 return $consulta->fetchAll(PDO::FETCH_CLASS, "mesa");		
	 }

	 
  	public function BorrarMesa()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from mesas 				
				WHERE idMesa=:idMesa");	
				$consulta->bindValue(':idMesa',$this->idMesa, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }

	  public function CambiarEstadoMesa()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update mesas 
				set estado=:estado
				WHERE idMesa=:idMesa");
			$consulta->bindValue(':idMesa',$this->idMesa, PDO::PARAM_INT);
			$consulta->bindValue(':estado',$this->estado, PDO::PARAM_INT);
		

			return $consulta->execute();
	 }

	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->nombre."  ".$this->idMesa."  ".$this->email;
	}


	public function traerMesaID($id)
	{
			
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,estado,idMesa where idMesa = '".$id."'");
			$consulta->execute();
			$mesaBuscado= $consulta->fetchObject('mesa');
			
			return $mesaBuscado;	
	}


	public function CerrarMesa()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("update mesas SET estado='Cerrada' WHERE idMesa=:idMesa");
		$consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
		$consulta->execute();		
	} 

}