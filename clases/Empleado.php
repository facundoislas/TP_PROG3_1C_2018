<?php
class empleado
{
	public $id;
 	public $nombre;
  	public $apellido;
	public $puesto;
	public $sector;
	public $user;
	public $pass;
	public $perfil;
	public $estado;



  	public function BorrarEmpleado()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from personal 				
				WHERE user=:user");	
				$consulta->bindValue(':user',$this->user, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }
	 /*
	public function SuspenderEmpleado()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update personal 
				set estado='$this->estado'
				WHERE user='$this->user'");
			return $consulta->execute();

	 }*/
	
	 /*
	 public function InsertarEmpleado()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into personal (nombre,apellido,documento,categoria,sueldo)values('$this->nombre','$this->apellido','$this->documento','$this->categoria','$this->sueldo')");
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
				

	 }*/

	  public function SuspenderEmpleado()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update personal 
				set estado=:estado
				WHERE user =:user");
				
			$consulta->bindValue(':user',$this->user, PDO::PARAM_INT);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
	
			return $consulta->execute();
	 }

	 public function InsertarEmpleadoParametro()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into personal (nombre,apellido,sector,puesto,user,pass,estado,perfil)values(:nombre,:apellido,:sector,:puesto,:user,:pass,:estado,:perfil)");
				$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_INT);
				$consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
				$consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
				$consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
				$consulta->bindValue(':user', $this->user, PDO::PARAM_STR);
				$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
				$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
				$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }
	 public function GuardarEmpleado()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarEmpleado();
	 		}else {
	 			$this->InsertarEmpleadoParametros();
	 		}

	 }


  	public static function TraerTodosLosEmpleados()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre,apellido,sector,puesto,user,pass,estado,perfil from personal");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");		
	}

	public static function TraerUnEmpleadoUser($user) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre,apellido,sector,puesto,user,pass,estado,perfil from personal where user = '".$user."'");
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('empleado');
			
			return $usuarioBuscado;				

			
	}
	/*
	public static function TraerUnEmpleadoId($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre,apellido,sector,puesto,user,pass,estado,perfil  WHERE id= $id");
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('empleado');
      		return $usuarioBuscado;				

			
	}*/

	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->nombre."  ".$this->apellido."  ".$this->documento;
	}

	function ModificarEmpleado()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update personal 
				set estado=:estado,
				nombre=:nombre,
				apellido=:apellido,
				puesto=:puesto,
				sector=:sector,
				perfil=:perfil,
				pass=:pass
				WHERE user =:user");
				
			$consulta->bindValue(':user',$this->user, PDO::PARAM_INT);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
			$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_INT);
			$consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
			$consulta->bindValue(':puesto',$this->puesto, PDO::PARAM_INT);
			$consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
			$consulta->bindValue(':perfil',$this->perfil, PDO::PARAM_INT);
			$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
	
			return $consulta->execute();
	 
	}


	public function ActivarEmpleado()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update personal 
				set estado=:estado
				WHERE user =:user");
				
			$consulta->bindValue(':user',$this->user, PDO::PARAM_INT);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
	
			return $consulta->execute();
	 }


	public static function Suspendidos() 
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select user, estado from personal where estado = 'suspendido'");
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");	

			
	}

	public static function Activos() 
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select user, estado from personal where estado = 'activo'");
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");	

			
	}
}