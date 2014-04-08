<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/pagopersonal.php';

class PagoPersonalController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'pa.idpagos','align'=>'center','width'=>50),
                        2 => array('Name'=>'DNI Personal','NameDB'=>'p.dni','width'=>80,'search'=>true),
                        3 => array('Name'=>'Personal','NameDB'=>"p.nombres || ' ' || p.apellidos",'search'=>true,'width'=>150),
                        4 => array('Name'=>'Motivo','NameDB'=>'pa.motivo','search'=>true,'width'=>180),
                        5 => array('Name'=>'Monto Pago','NameDB'=>'pa.importe','width'=>80,'align'=>'right'),
                        6 => array('Name'=>'N° Recibo','NameDB'=>'pa.nrorecibo','width'=>70,'align'=>'center'),
                        7 => array('Name'=>'Fecha Canc.','NameDB'=>'pa.fechacancelacion','align'=>'center','width'=>70)

                     );
    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        //$data['script'] = "evt_index_ventas.js";
        //(nuevo,editar,eliminar,ver)
        $data['actions'] = array(true,true,false,true);


        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }
    
    public function indexGrid() 
    {
        $obj = new PagoPersonal();  
              
        $page = (int)$_GET['page'];
        $limit = (int)$_GET['rows']; 
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $filtro = $this->getColNameDB($this->cols,(int)$_GET['f']);
        $query = $_GET['q'];
        if(!$sidx) $sidx = 1;
        if(!$limit) $limit = 10;
        if(!$page) $page = 1;

        echo json_encode($obj->indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$this->getColsVal($this->cols)));
    }
    
    public function create() 
    {
        $data = array();
        $view = new View();
        $view->setData($data);
        $view->setTemplate( '../view/pagopersonal/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new PagoPersonal();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $view->setData($data);
        $view->setTemplate( '../view/pagopersonal/_form.php' );
        echo $view->renderPartial();
    }

    public function view() 
    {
        $obj = new PagoPersonal();
        $data = array();
        $view = new View();
        $rows = $obj->edit($_GET['id']);
        $data['obj'] = $rows;
        $view->setData($data);
        $view->setTemplate( '../view/pagopersonal/_form.php' );
        echo $view->renderPartial();
    }

    public function save()
    {
        $obj = new PagoPersonal();
        $result = array();
        if ($_POST['idpagos']=='')
            $p = $obj->insert($_POST);
        else
            $p = $obj->update($_POST);
        if ($p[0]=="1")
            $result = array(1,'');
        else
            $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function delete()
    {
        $obj = new Ventas();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function test()
    {
        $prod = array('item'=>0,'id'=>array(1,3,5));
        $prod = json_decode(json_encode($prod));
        echo $prod->item;
        //print_r($_GET['producto']);
        //$a = json_decode($_GET['producto']);        
        //echo $a->descripcion[0];
    }

    public function pagarcuota()
    {
        
        $obj = new Ventas();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->ViewCuotas($_GET['id']);
        $data['formapago2'] = $this->Select(array('id'=>'idformapago2','name'=>'idformapago2','text_null'=>'','table'=>'formapago','width'=>'120px'));
        $view->setData($data);
        $view->setTemplate( '../view/ventas/_pagocuota.php' );
        $view->setLayout( '../template/list.php' );
        $view->render();
    
    }

    //REPORTES
    public function load_ventas()
    {
        $obj = new Ventas();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->ViewResultado($_GET);
        $view->setData($data);
        $view->setTemplate( '../view/ventas/_consulventas.php' );
        echo $view->renderPartial();
    }

    //
    public function detallertp()
    {
        $obj = new Ventas();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->rptDetails($_GET['id']);
        $view->setData($data);
        $view->setTemplate( '../view/ventas/_rptDetalle.php' );
        $view->setLayout( '../template/list.php' );
        $view->render();
    }


}
 

?>