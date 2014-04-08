<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/ubigeo.php';

class UbigeoController extends Controller
{
    
    public function Provincia() 
    {
        $obj = new Ubigeo();
        $rows = $obj->Provincia($_GET['idd']);  
        print_r(json_encode($rows));
    }

    public function Distrito() 
    {
        $obj = new Ubigeo();
        $rows = $obj->Distrito($_GET['idd1']);                               
        print_r(json_encode($rows));
    }
   
   
}

?>