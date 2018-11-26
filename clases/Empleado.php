<?php
//include_once "AccesoDatos.php";
require_once 'AccesoDatos.php';
class empleado
{
    public $id;
    public $email;
    public $clave;
    public $nombre;
    public $tipo;
    public $estado;
    //public $foto;
    //public $alta;
    //public $estado;

    
    public function InsertarEmpleadoParametros()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into empleado (email,clave,nombre,tipo,estado)values(:email,:clave,:nombre,:tipo,:estado)");
            $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            //$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
            //$consulta->bindValue(':alta', $this->alta, PDO::PARAM_STR);
            $consulta->execute();	
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
			//return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");	
    }

    public static function TraerTodoLosEmpleadosSuspendidos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from empleado where estado='suspendido'");
            $consulta->execute();
            if($consulta->rowCount() == 0){
                return false;   
            }		
			return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");		
    }
    public static function TraerTodoLosEmpleados()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from empleado");
            $consulta->execute();	
            if($consulta->rowCount() == 0){
                return false;   
            }			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");		
    }

    public static function TraerEmpleadoEmail($email) 
	{//email,clave,nombre,tipo,estado
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,email,clave,nombre,tipo,estado from empleado where email = '$email'");
			$consulta->execute();
            $EmpAux= $consulta->fetchObject('empleado');
            if($consulta->rowCount() == 0){
                return false;   
            }
			return $EmpAux;		
    }

    public static function TraerEmpleadoEmailClave($email,$clave){
		$objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT id,nombre,sexo,email,turno,perfil,foto,alta,estado FROM empleado WHERE email=:email AND clave=:clave");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
		$consulta->setFetchMode(PDO::FETCH_CLASS, "empleado");
		$consulta->execute();
        return $consulta->fetchObject('empleado');
    }

    public static function TraerEmpleadoEmailClave2($email,$clave){
		$objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT id,nombre,sexo,email,turno,perfil,foto,alta,estado FROM empleado WHERE email=:email AND clave=:clave");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
		$EmpAux= $consulta->fetchObject('empleado');
            if($consulta->rowCount() == 0){
                return false;   
            }
		return $EmpAux;	
	}
    
    public static function TraerEmpleadoID($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from empleado where id = '$id'");
			$consulta->execute();
            $EmpAux= $consulta->fetchObject('empleado');
            if($consulta->rowCount() == 0){
                return false;   
            }
			return $EmpAux;		
    }
    
    public static function TraerEmail($auxEmail) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select email from empleado where email = '$auxEmail'");
			$consulta->execute();
            $consulta->setFetchMode(PDO::FETCH_ASSOC);
            if($consulta->rowCount() == 0){
                return false;   
            }
            return $consulta->fetchAll();	
	}

   
    public static function BorrarEmpleadoID($id)
    {
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
       $consulta =$objetoAccesoDato->RetornarConsulta("
           delete 
           from empleado 				
           WHERE id=:id");	
       $consulta->bindValue(':id',$id, PDO::PARAM_INT);		
       $consulta->execute();
       return $consulta->rowCount();
    }

    public function ModificarEmpleadoID($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE empleado set nombre=:nombre,email=:email,clave=:clave,tipo=:tipo,estado=:estado WHERE id=$auxID");
            //$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
    }

    public static function SuspenderEmpleadoParametros($auxID,$auxEST)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE empleado set estado=:estado where id=$auxID");
        $consulta->bindValue(':estado', $auxEST, PDO::PARAM_STR);
        return $consulta->execute();

    }
    
    public static function operacionesUsuarioSalida($auxID){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("SELECT count(*) as Cantidad_Comandas FROM pedidos WHERE idEmpladoCer = $auxID OR idEmpladoCoc = $auxID OR idEmpladoBar = $auxID");
        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_ASSOC);
        return $consulta->fetch();
		/*
		if($consulta->rowCount() == 0){
			return false;   
		}
        return $consulta->fetchAll();
        */
    }
    



}
?>