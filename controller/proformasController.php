<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/proformas.php';

class ProformasController extends Controller
{
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'p.idproforma','align'=>'center','width'=>'40'),
                        2 => array('Name'=>'Cliente','NameDB'=>'c.nombres','search'=>true),
                        3 => array('Name'=>'Sucursal','NameDB'=>'s.descripcion','search'=>true), 
                        4 => array('Name'=>'Fecha','NameDB'=>'p.fecha','width'=>'50','align'=>'center'),                       
                        5 => array('Name'=>'Estado','NameDB'=>'p.estado','width'=>'60','align'=>'center'),
                        6 => array('Name'=>'&nbsp','NameDB'=>'-','align'=>'center','width'=>30),
                        7 => array('Name'=>'&nbsp','NameDB'=>'-','align'=>'center','width'=>30)
                     );

    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['script'] = "evt_index_proformas.js";
        //(nuevo,editar,eliminar,ver,anular,imprimir)
        $data['actions'] = array(true,true,false,true,false,true);

        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }

    public function indexGrid() 
    {
        $obj = new Proformas();        
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
        $IdSuc=$_SESSION['idsucursal'];                
        $data['idsucursal'] = $IdSuc;
        $data['tipopago'] = $this->Select(array('id'=>'idtipopago','name'=>'idtipopago','text_null'=>'Seleccione...','table'=>'produccion.vista_tipopago'));       
        $data['Financiamiento'] = $this->Select(array('id'=>'idfinanciamiento','name'=>'idfinanciamiento','text_null'=>'Seleccione...','table'=>'facturacion.vista_financiamiento'));
        $data['Sucursal'] = $this->Select(array('id'=>'idsucursal','name'=>'idsucursal','text_null'=>'Seleccione...','table'=>'vista_sucursal','code'=>$IdSuc));       


        $view->setData($data);
        $view->setTemplate( '../view/proformas/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() {
        //Comprobamos si podemos editar
        $estado = $this->getEstado("facturacion.proforma","idproforma",$_GET['id']);
        if($estado==0)
        {
            $obj = new Proformas();
            $data = array();
            $view = new View();
            $rows = $obj->edit($_GET['id']);
            $data['obj'] = $rows;
            $data['tipopago'] = $this->Select(array('id'=>'idtipopago','name'=>'idtipopago','text_null'=>'Seleccione...','table'=>'produccion.vista_tipopago'));       
            $data['Financiamiento'] = $this->Select(array('id'=>'idfinanciamiento','name'=>'idfinanciamiento','text_null'=>'Seleccione...','table'=>'facturacion.vista_financiamiento'));       
            $data['Sucursal'] = $this->Select(array('id'=>'idsucursal','name'=>'idsucursal','text_null'=>'Seleccione...','table'=>'vista_sucursal','code'=>$rows->idsucursal));
            $data['rowsd'] = $obj->getDetails($rows->idproforma);
            $view->setData($data);
            $view->setTemplate( '../view/proformas/_form.php' );
            echo $view->renderPartial();
        }else
            {
                $view = new View();
                $data['msg'] = "<b>A esta factura ya no se puede realizar ninguna accion. </b><br/><br/>
                      Si deseas ver los datos de esta fartura 
                      le recomendamos elegir la opcion 'VER' del menu de operaciones.<br/>
                      Si considera que este registro si deveria poder editarse, comuniquese con el administrador del sistema. ";
                $view->setData($data);
                $view->setTemplate( '../view/_error_app.php' );
                echo $view->renderPartial();
            }

    }

    public function view() 
    {
        $obj = new Proformas();
        $data = array();
        $view = new View();
        $rows = $obj->edit($_GET['id']);
        $data['obj'] = $rows;
        $data['tipopago'] = $this->Select(array('id'=>'idtipopago','name'=>'idtipopago','text_null'=>'Seleccione...','table'=>'produccion.vista_tipopago','disabled'=>'disabled'));       
        $data['Financiamiento'] = $this->Select(array('id'=>'idfinanciamiento','name'=>'idfinanciamiento','text_null'=>'Seleccione...','table'=>'facturacion.vista_financiamiento'));       
        $data['Sucursal'] = $this->Select(array('id'=>'idsucursal','name'=>'idsucursal','text_null'=>'Seleccione...','table'=>'vista_sucursal','code'=>$rows->idsucursal,'disabled'=>'disabled'));
        $data['rowsd'] = $obj->getDetails($rows->idproforma);
        $view->setData($data);
        $view->setTemplate( '../view/proformas/_form.php' );
        echo $view->renderPartial();
    }

    public function save()
    {
        $obj = new Proformas();
        $result = array();        
        if ($_POST['idproforma']=='')
            
        $p = $obj->insert($_POST);                        
        else         
            $p = $obj->update($_POST);                                
        if ($p[0]==1)                
            $result = array(1,'',$p[2]);                
        else                 
            $result = array(2,$p[1],'');
        print_r(json_encode($result));

    }
    
    public function delete()
    {
        $obj = new Proformas();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
    
    //Cargaar detalle para la solicitud
    public function load_productos()
    {
        $obj = new Proformas();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->ViewDetalle($_GET['idproforma']);
        $view->setData($data);
        $view->setTemplate( '../view/proformas/_detalle.php' );
        echo $view->renderPartial();
    }
    
    //Para el las consultas es decir reportes
    public function load_proformas()
    {
        $obj = new Proformas();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->ViewResultado($_GET);
        $view->setData($data);
        $view->setTemplate( '../view/proformas/_consulproforma.php' );
        echo $view->renderPartial();
    }
    
    //imprmir reporte
    public function print_rpt()
    {
        $obj = new Proformas();
        $data = array();
        $view = new View();
        $data['rows'] = $obj->ViewResultado($_GET);
        $view->setData($data);
        $view->setTemplate( '../view/proformas/_pdfrpt.php' );
        $view->setLayout( '../template/empty.php' );
        echo $view->renderPartial();
    }

    public function anular()
    {
        $obj = new Proformas();
        $result = array();        
        $p = $obj->anularpro($_POST['i']);
        if ($p[0]=="1") $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
    
    //Imprimir proforma
    public function printer()
    {
        $obj = new Proformas();
        $data = array();
        $view = new View();
        $ro = $obj->printPro($_GET);
        $data['cabecera'] = $ro[0];
        $data['detalle'] = $ro[1];
        $view->setData($data);
        $view->setTemplate( '../view/proformas/_pdf.php' );
        $view->setLayout( '../template/empty.php' );
        echo $view->renderPartial();
    }
    
}

?>