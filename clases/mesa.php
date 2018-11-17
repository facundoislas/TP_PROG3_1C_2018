<?php
include_once "AccesoDatos.php";
class mesa
{
    public $id;
    public $codigo;
    public $estado;
    
    public function InsertarEmpleadoParametros()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mesa (codigo,estado)values(:codigo,:estado)");
            $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();	
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    public static function TraerTodas()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from mesa");
            $consulta->execute();	
            if($consulta->rowCount() == 0){
                return false;   
            }			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "mesa");		
    }

    public static function TraerTodasDispobibles()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from mesa where estado = 'libre'");
            $consulta->execute();	
            if($consulta->rowCount() == 0){
                return false;   
            }			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "mesa");		
    }

    public static function ocuparMesa($auxID,$auxEST)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE mesa set estado=:estado where codigo='$auxID'");
        $consulta->bindValue(':estado', $auxEST, PDO::PARAM_STR);
        return $consulta->execute();

    }
}