<?php
include_once "AccesoDatos.php";
class pedido
{
    public $idPedido;
    public $nroMesa;
    public $cliente;
    public $foto;
    public $importe;
    public $fecha;
    public $detalleBar;
    public $tiempo_estimado_bar;
    public $tiempo_final_bar;
    public $estadoBar;
    public $detalleCer;
    public $tiempo_estimado_cer;
    public $tiempo_final_cer;
    public $estadoCer;
    public $idEmpladoCer;
    public $detalleCoc;
    public $tiempo_estimado_coc;
    public $tiempo_final_coc;
    public $estadoCoc;
    public $idEmpladoCoc;

    public function InsertarVehiculoParametros()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (nroMesa,cliente,foto,importe,fecha,detalleBar,estadoBar,detalleCer,estadoCer,detalleCoc,estadoCoc)values(:nroMesa,:cliente,:foto,:importe,:fecha,:detalleBar,:estadoBar,:detalleCer,:estadoCer,:detalleCoc,:estadoCoc)");
            $consulta->bindValue(':nroMesa',$this->nroMesa, PDO::PARAM_STR);
            $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
            $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
            $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
            $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $consulta->bindValue(':detalleBar',$this->detalleBar, PDO::PARAM_STR);
            $consulta->bindValue(':estadoBar', $this->estadoBar, PDO::PARAM_STR);
            $consulta->bindValue(':detalleCer', $this->detalleCer, PDO::PARAM_STR);
            $consulta->bindValue(':estadoCer',$this->estadoCer, PDO::PARAM_STR);
            $consulta->bindValue(':detalleCoc', $this->detalleCoc, PDO::PARAM_STR);
            $consulta->bindValue(':estadoCoc', $this->estadoCoc, PDO::PARAM_STR);
			$consulta->execute();	
			return $consulta->fetchAll(PDO::FETCH_CLASS, "pedido");	
    }
    public static function TraerTodoLosPedidos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from pedidos");
            $consulta->execute();	
            if($consulta->rowCount() == 0){
                return false;   
            }			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "pedido");		
    }

    public static function TraerTodoLosPedidosConEstadoMesa()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT p.`idPedido`, p.`nroMesa`, m.`estado`, p.`cliente`, p.`foto`, p.`importe`,p.`fecha`, p.`detalleBar`, p.`tiempo_estimado_bar`, p.`tiempo_final_bar`, p.`estadoBar`, p.`idEmpladoBar`, p.`detalleCer`, p.`tiempo_estimado_cer`, p.`tiempo_final_cer`, p.`estadoCer`, p.`idEmpladoCer`, p.`detalleCoc`, p.`tiempo_estimado_coc`,p.`tiempo_final_coc`, p.`estadoCoc`, p.`idEmpladoCoc` FROM `pedidos` AS p , `mesa` AS m WHERE p.`nroMesa`= m.`codigo`");
            $consulta->execute();	
            if($consulta->rowCount() == 0){
                return false;   
            }			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "pedido");		
    }

    public static function TraerPedidoIDConEstadoMesa($idPedido) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT p.`idPedido`, p.`nroMesa`, m.`estado`, p.`cliente`, p.`foto`, p.`importe`,p.`fecha`, p.`detalleBar`, p.`tiempo_estimado_bar`, p.`tiempo_final_bar`, p.`estadoBar`, p.`idEmpladoBar`, p.`detalleCer`, p.`tiempo_estimado_cer`, p.`tiempo_final_cer`, p.`estadoCer`, p.`idEmpladoCer`, p.`detalleCoc`, p.`tiempo_estimado_coc`,p.`tiempo_final_coc`, p.`estadoCoc`, p.`idEmpladoCoc` FROM `pedidos` AS p , `mesa` AS m WHERE p.`nroMesa`= m.`codigo`AND p.`idPedido`= '$idPedido'");
			$consulta->execute();
            $EmpAux= $consulta->fetchObject('pedido');
            if($consulta->rowCount() == 0){
                return false;   
            }
			return $EmpAux;		
    }
    public static function TraerPedidoID($idPedido) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from pedidos where idPedido = '$idPedido'");
			$consulta->execute();
            $EmpAux= $consulta->fetchObject('pedido');
            if($consulta->rowCount() == 0){
                return false;   
            }
			return $EmpAux;		
    }
    public function modificarBarID($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoBar=:estadoBar,tiempo_estimado_bar=:tiempo_estimado_bar,idEmpladoBar=:idEmpladoBar WHERE idPedido=$auxID");
            //$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':estadoBar', $this->estadoBar, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_estimado_bar', $this->tiempo_estimado_bar, PDO::PARAM_STR);
            $consulta->bindValue(':idEmpladoBar', $this->idEmpladoBar, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
    }

    public function modificarCocID($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoCoc=:estadoCoc,tiempo_estimado_coc=:tiempo_estimado_coc,idEmpladoCoc=:idEmpladoCoc WHERE idPedido=$auxID");
            //$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':estadoCoc', $this->estadoCoc, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_estimado_coc', $this->tiempo_estimado_coc, PDO::PARAM_STR);
            $consulta->bindValue(':idEmpladoCoc', $this->idEmpladoCoc, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
    }

    public function modificarCerID($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoCer=:estadoCer,tiempo_estimado_cer=:tiempo_estimado_cer,idEmpladoCer=:idEmpladoCer WHERE idPedido=$auxID");
            //$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':estadoCer', $this->estadoCer, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_estimado_cer', $this->tiempo_estimado_cer, PDO::PARAM_STR);
            $consulta->bindValue(':idEmpladoCer', $this->idEmpladoCer, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
    }

    public function finalizarCocinarID($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoCoc=:estadoCoc,tiempo_final_coc=:tiempo_final_coc WHERE idPedido=$auxID");
            $consulta->bindValue(':estadoCoc', $this->estadoCoc, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_final_coc', $this->tiempo_final_coc, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
    }
    public function finalizarBartenderID($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoBar=:estadoBar,tiempo_final_bar=:tiempo_final_bar WHERE idPedido=$auxID");
            $consulta->bindValue(':estadoBar', $this->estadoBar, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_final_bar', $this->tiempo_final_bar, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
    }
    public function finalizarCerveceriaID($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoCer=:estadoCer,tiempo_final_cer=:tiempo_final_cer WHERE idPedido=$auxID");
            $consulta->bindValue(':estadoCer', $this->estadoCer, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_final_cer', $this->tiempo_final_cer, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
    }

    public function cambiarEstadoPedidoGlobal($auxID)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoCer=:estadoCer,tiempo_final_cer=:tiempo_final_cer WHERE idPedido=$auxID");
            $consulta->bindValue(':estadoCer', $this->estadoCer, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo_final_cer', $this->tiempo_final_cer, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
    }

    public function cambiarTodosEstadoSector($auxID,$auxEstado)
    {
        $pedidoBuscado = pedido::TraerPedidoID($auxID);
        $auxEstadoCer = "Sin pedido";
        $auxEstadoCoc = "Sin pedido";
        $auxEstadoBar = "Sin pedido";
        if ($pedidoBuscado->estadoCer != null) {
            $auxEstadoCer = $auxEstado;
        }
        if ($pedidoBuscado->estadoCoc != null) {
            $auxEstadoCoc = $auxEstado;
        }
        if ($pedidoBuscado->estadoBar != null) {
            $auxEstadoBar = $auxEstado;
        }
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estadoCer=:estadoCer,estadoCoc=:estadoCoc,estadoBar=:estadoBar WHERE idPedido=$auxID");
            $consulta->bindValue(':estadoCer', $auxEstadoCer, PDO::PARAM_STR);
            $consulta->bindValue(':estadoCoc', $auxEstadoCoc, PDO::PARAM_STR);
            $consulta->bindValue(':estadoBar', $auxEstadoBar, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
    }
    public static function TraerCantidadOperacionesSectorFechas($tipo,$desde,$hasta)
    {
        $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        if ($desde !="" && $hasta == "") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT count(*) cant FROM pedidos WHERE $tipo = 'Finalizado' AND fecha >= '$desde' ");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }
        if ($desde ==""&& $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT count(*) cant FROM pedidos WHERE $tipo = 'Finalizado' AND fecha <= '$hasta' ");
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }
        if ($desde !="" && $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT COUNT(*) cant FROM pedidos WHERE $tipo = 'Finalizado' AND fecha BETWEEN '$desde' AND '$hasta'");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_INT);
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_INT);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }
        if ($desde =="" && $hasta =="") {
            $ahora= date("Y-m-d");
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT count(*) cant FROM pedidos WHERE $tipo = 'Finalizado' AND fecha <= '$ahora'");
            $consulta->bindValue(":hasta", $ahora, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }  
        $consulta->setFetchMode(PDO::FETCH_ASSOC);
        $consulta->execute();
        if($consulta->rowCount() == 0){
            return false;   
        }
        return $consulta->fetchAll();
    }
    public static function TraerMasVendidosSectorFechas($tipo,$desde,$hasta)
    {
        $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        if ($desde !="" && $hasta == "") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `$tipo`,COUNT(`$tipo`) cant FROM `pedidos` WHERE fecha >= '$desde' GROUP BY `$tipo` HAVING `$tipo` != 'nada' AND `$tipo` != 'Sin pedido' AND `$tipo` != 'Finalizado' ORDER BY cant DESC LIMIT 1");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }
        if ($desde ==""&& $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `$tipo`,COUNT(`$tipo`) cant FROM `pedidos` WHERE fecha <= '$hasta' GROUP BY `$tipo` HAVING `$tipo` != 'nada' AND `$tipo` != 'Sin pedido' AND `$tipo` != 'Finalizado' ORDER BY cant DESC LIMIT 1");
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }
        if ($desde !="" && $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `$tipo`,COUNT(`$tipo`) cant FROM `pedidos` WHERE fecha BETWEEN '$desde' AND '$hasta' GROUP BY `$tipo` HAVING `$tipo` != 'nada' AND `$tipo` != 'Sin pedido' AND `$tipo` != 'Finalizado' ORDER BY cant DESC LIMIT 1");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_INT);
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_INT);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }
        if ($desde =="" && $hasta =="") {
            $ahora= date("Y-m-d");
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `$tipo`,COUNT(`$tipo`) cant FROM `pedidos` WHERE fecha <= '$ahora' GROUP BY `$tipo` HAVING `$tipo` != 'nada' AND `$tipo` != 'Sin pedido' AND `$tipo` != 'Finalizado' ORDER BY cant DESC LIMIT 1");
            $consulta->bindValue(":hasta", $ahora, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        }  
        $consulta->setFetchMode(PDO::FETCH_ASSOC);
        $consulta->execute();
        if($consulta->rowCount() == 0){
            return false;   
        }
        return $consulta->fetchAll();
    }

    public static function TraerUsosMesasFechas($desde,$hasta)
    {
        $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        if ($desde !="" && $hasta == "") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,COUNT(`nroMesa`) cant FROM `pedidos` WHERE fecha >= '$desde' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);

        }
        if ($desde ==""&& $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,COUNT(`nroMesa`) cant FROM `pedidos` WHERE fecha <= '$hasta' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_STR);
        }
        if ($desde !="" && $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,COUNT(`nroMesa`) cant FROM `pedidos` WHERE fecha BETWEEN '$desde' AND '$hasta' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_INT);
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_INT);

        }
        if ($desde =="" && $hasta =="") {
            $ahora= date("Y-m-d");
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,COUNT(`nroMesa`) cant FROM `pedidos` WHERE fecha <= '$ahora' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":hasta", $ahora, PDO::PARAM_STR);

        }  
        $consulta->setFetchMode(PDO::FETCH_ASSOC);
        $consulta->execute();
        if($consulta->rowCount() == 0){
            return false;   
        }
        return $consulta->fetchAll();
    }

    public static function TraerFacturacionMesasFechas($desde,$hasta)
    {
        $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        if ($desde !="" && $hasta == "") {
                                                            //SELECT `nroMesa`,COUNT(`nroMesa`) cant FROM `pedidos` GROUP BY `nroMesa` ORDER BY cant DESC
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,SUM(`importe`) cant FROM `pedidos` WHERE fecha >= '$desde' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);

        }
        if ($desde ==""&& $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,SUM(`importe`) cant FROM `pedidos` WHERE fecha <= '$hasta' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_STR);
        }
        if ($desde !="" && $hasta !="") {
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,SUM(`importe`) cant FROM `pedidos` WHERE fecha BETWEEN '$desde' AND '$hasta' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":desde", $desde, PDO::PARAM_INT);
            $consulta->bindValue(":hasta", $hasta, PDO::PARAM_INT);

        }
        if ($desde =="" && $hasta =="") {
            $ahora= date("Y-m-d");
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT `nroMesa`,SUM(`importe`) cant FROM `pedidos` WHERE fecha <= '$ahora' GROUP BY `nroMesa` ORDER BY cant DESC");
            $consulta->bindValue(":hasta", $ahora, PDO::PARAM_STR);

        }  
        $consulta->setFetchMode(PDO::FETCH_ASSOC);
        $consulta->execute();
        if($consulta->rowCount() == 0){
            return false;   
        }
        return $consulta->fetchAll();
    }


}