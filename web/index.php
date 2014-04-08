<?php
session_start();
date_default_timezone_set("America/Lima"); 
class ventad
{
    var $item;
    var $iditinerario;
    var $itinerario;    
    var $precio;
    var $cantidad;
    var $estado;    
    
    function __construct() {
        $this->item=0;
    }
    function add($iditinerario,$itinerario,$precio,$cantidad)
    {      
        if(!$this->search($iditinerario))
        {
            $this->iditinerario[$this->item] = $iditinerario;
            $this->itinerario[$this->item] = $itinerario;        
            $this->precio[$this->item] = $precio;        
            $this->cantidad[$this->item] = $cantidad;        
            $this->estado[$this->item] = true;
            $this->item += 1;
            return true;
        }
        else 
        {
            return false;
        }
    }
    function getTotal()
    {
        $total = 0;
        for($i=0;$i<$this->item;$i++)
        {
            if($this->estado[$i] == true)
            {
                $total += $this->cantidad[$i]*$this->precio[$i];
            }
        }
        return $total;
    }
    function search($iditinerario)
    {
        $flag = false;
        // for($i=0;$i<$this->item;$i++)
        // {
        //     if($this->iditinerario[$i] == $iditinerario && $this->estado[$i] == true)
        //     {
        //         $flag = true;                      
        //     }
        // }
        return $flag;
    }
    function quit($item)
    {
        $this->estado[$item] = false;
    }    
    function count_items()
    {
        $c = 0;
        for($i=0;$i<$this->item;$i++)
        {
            if($this->estado[$i])
            {
                $c += 1;
            }
        }
        return $c;
    }
}
if(!isset($_SESSION['ventad']))
{
    $_SESSION['ventad'] = new ventad();
}

class envios
{
    var $item;
    var $descripcion;        
    var $precio;
    var $cantidad;
    var $estado;
    var $precioc;
    
    function __construct() 
    {
        $this->item=0;
    }
    function add($descripcion,$precio,$cantidad,$precioc)
    {              
        $this->descripcion[$this->item] = $descripcion;            
        $this->precio[$this->item] = $precio;
        $this->precioc[$this->item] = $precioc;
        $this->cantidad[$this->item] = $cantidad;        
        $this->estado[$this->item] = true;
        $this->item += 1;
        return true;       
    }
    function quit($item)
    {
        $this->estado[$item] = false;
    }    
    function count_items()
    {
        $c = 0;
        for($i=0;$i<$this->item;$i++)
        {
            if($this->estado[$i])
            {
                $c += 1;
            }
        }
        return $c;
    }
}
if(!isset($_SESSION['envios']))
{
    $_SESSION['envios'] = new envios();
}

class conceptos {
    var $item;
    var $idconcepto_movimiento;
    var $concepto;
    var $monto;
    var $cantidad;    
    var $estado;    
    
    function __construct() {
        $this->item=0;
    }
    function add($idconcepto_movimiento,$concepto,$monto,$cantidad)
    {    
        
        $this->idconcepto_movimiento[$this->item] = $idconcepto_movimiento;
        $this->concepto[$this->item] = $concepto;
        $this->monto[$this->item] = $monto;
        $this->cantidad[$this->item] = $cantidad;           
        $this->estado[$this->item] = true;
        $this->item += 1;
        return true;
    }
    function quit($item)
    {
        $this->estado[$item] = false;
    }    
    function count_items()
    {
        $c = 0;
        for($i=0;$i<$this->item;$i++)
        {
            if($this->estado[$i])
            {
                $c += 1;
            }
        }
        return $c;
    }
}
if(!isset($_SESSION['conceptos']))
{
    $_SESSION['conceptos'] = new conceptos();
}
require_once '../lib/FrontController.php';
FrontController::Main();
?>