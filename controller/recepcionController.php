<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/recepcion.php';

class RecepcionController extends Controller
{
    var $cols = array(
            1 => array('Name'=>'Codigo','NameDB'=>'t.idtramite','align'=>'center','width'=>30),
            2 => array('Name'=>'Tipo Documento','NameDB'=>'td.descripcion','width'=>60,'search'=>true),
            3 => array('Name'=>'Codigo','NameDB'=>'t.codigo','width'=>50),
            4 => array('Name'=>'Fecha Envio','NameDB'=>'t.fechainicio','align'=>'center','width'=>40),
            5 => array('Name'=>'Remitente','NameDB'=>"p.nombres ||' '||p.apellidos",'align'=>'left','width'=>100),
            6 => array('Name'=>'Fecha Recep.','NameDB'=>'t.fechafin','align'=>'center','width'=>40), 
            7 => array('Name'=>'Hora Recep.','NameDB'=>'t.horafin','align'=>'center','width'=>40), 
            8 => array('Name'=>'','NameDB'=>'','align'=>'center','width'=>15),
            9 => array('Name'=>'','NameDB'=>'','align'=>'center','width'=>15),
            10=> array('Name'=>'','NameDB'=>'','align'=>'center','width'=>35),
            11=> array('Name'=>'','NameDB'=>'','align'=>'center','width'=>20)
        );

    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['script'] = "evt_index_recepcion.js";
        //(nuevo,editar,eliminar,ver,anular,imprimir)
        $data['actions'] = array(false,true,false,true,false,false);

        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }

    public function indexGrid() 
    {
        $obj = new Recepcion();        
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
        $view->setTemplate( '../view/recepcion/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() {
        $obj = new Recepcion();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);   
        $data['obj'] = $obj;
        $data['personal'] = $this->Select(array('id'=>'idpersonal','name'=>'idpersonal','text_null'=>'Seleccione...','table'=>'vista_personal','code'=>$obj->idperdestinatario));        
        $data['remitente'] = $this->Select(array('id'=>'idperremitente','name'=>'idperremitente','text_null'=>'Seleccione...','table'=>'vista_remitente','code'=>$obj->usuarioreg));        
        $data['cierre'] = $this->Select(array('id'=>'idcierre','name'=>'idcierre','text_null'=>'Seleccione...','table'=>'vista_cierre','code'=>$obj->idcierre));  
        $data['tipodoc'] = $this->Select(array('id'=>'idtipo_documento','name'=>'idtipo_documento','text_null'=>':: Seleccione ::','table'=>'vista_tipodocumento','code'=>$obj->idtipo_documento));
        $data['tipoproblema'] = $this->Select(array('id'=>'idtipo_problema','name'=>'idtipo_problema','text_null'=>'Seleccione...','table'=>'vista_tipoproblema','code'=>$obj->idtipo_problema));        
        
        $tp= $obj->idtipo_problema;
        if ($tp == 1) {
            $data['idareai'] = $this->Select(array('id'=>'idareai','name'=>'idareai','text_null'=>'Seleccione...','table'=>'vista_consultorio','code'=>$obj->idareai));
        }else
            {
                $data['idareai'] = $this->Select(array('id'=>'idareai','name'=>'idareai','text_null'=>'Seleccione...','table'=>'vista_personal','code'=>$obj->idpersonalresp));
        
            }
        
        $view->setData($data);
        $view->setTemplate( '../view/recepcion/_devform.php' );
        echo $view->renderPartial();
    }

    public function view() 
    {
        $obj = new Recepcion();
        $data = array();
        $view = new View();
        $rows = $obj->edit($_GET['id']);
        $data['obj'] = $rows;
        $data['tipopago'] = $this->Select(array('id'=>'idtipopago','name'=>'idtipopago','text_null'=>'Seleccione...','table'=>'produccion.vista_tipopago','disabled'=>'disabled'));       
        $data['Financiamiento'] = $this->Select(array('id'=>'idfinanciamiento','name'=>'idfinanciamiento','text_null'=>'Seleccione...','table'=>'facturacion.vista_financiamiento'));       
        $data['Sucursal'] = $this->Select(array('id'=>'idsucursal','name'=>'idsucursal','text_null'=>'Seleccione...','table'=>'vista_sucursal','code'=>$rows->idsucursal,'disabled'=>'disabled'));
        $data['rowsd'] = $obj->getDetails($rows->idproforma);
        $view->setData($data);
        $view->setTemplate( '../view/recepcion/_form.php' );
        echo $view->renderPartial();
    }

    public function save()
    {
        $obj = new Recepcion();
        $result = array();        
        if ($_POST['idtramite']=='')
            
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
        $obj = new Recepcion();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
    
   
    //imprmir reporte
    public function print_rpt()
    {
        $obj = new Recepcion();
        $data = array();
        $view = new View();
        $data['rows'] = $obj->ViewResultado($_GET);
        $view->setData($data);
        $view->setTemplate( '../view/proformas/_pdfrpt.php' );
        $view->setLayout( '../template/empty.php' );
        echo $view->renderPartial();
    }

    public function recibir()
    {
        $obj = new Recepcion();
        $result = array();        
        $p = $obj->recibirdoc($_POST['i']);
        if ($p[0]=="1") 
            $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
    
    //Imprimir 
    public function printer_mem()
    {
        $obj = new Recepcion();
        $data = array();
        $view = new View();
        $ro = $obj->printDoc($_GET['id']);
        $data['cab'] = $ro[0];
        //$data['detalle'] = $ro[1];
        $view->setData($data);
        $view->setTemplate( '../view/recepcion/_mempdf.php' );
        $view->setLayout( '../template/empty.php' );
        echo $view->renderPartial();
    }
    
    //Imprimir 
    public function printer_ot()
    {
        $obj = new Recepcion();
        $data = array();
        $view = new View();
        $ro = $obj->printDoc2($_GET['id']);
        $data['cab'] = $ro[0];
        $data['rowsc'] = $ro[1];
        $view->setData($data);
        $view->setTemplate( '../view/recepcion/_otrospdf.php' );
        $view->setLayout( '../template/empty.php' );
        echo $view->renderPartial();
    }
    
    public function derivar()
    { 
        $obj = new Recepcion();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $data['tipodoc'] = $this->Select(array('id'=>'idtipo_documento','name'=>'idtipo_documento','text_null'=>':: Seleccione ::','table'=>'vista_tipodocumento','code'=>$obj->idtipo_documento));
        $data['remitente'] = $this->Select(array('id'=>'idperremitente','name'=>'idperremitente','text_null'=>'Seleccione...','table'=>'vista_remitente','code'=>$obj->idpersonalresp));        
        $data['remitentes'] = $this->Select(array('id'=>'idperremitente','name'=>'idperremitente','text_null'=>'Seleccione...','table'=>'vista_paciente','code'=>$obj->idpaciente)); 
        $data['personal'] = $this->Select(array('id'=>'idpersonal','name'=>'idpersonal','text_null'=>'Seleccione...','table'=>'vista_personal'));        
        
        $view->setData($data);
        $view->setTemplate( '../view/recepcion/_derivar.php' );
        $view->setLayout( '../template/Layout.php' );
        echo $view->renderPartial();          
      
    } 
    
    public function SaveDerivar()
    {
        $obj = new Recepcion();
        $result = array();        
        //if ($_POST['idtramite']=='')
            
        $p = $obj->InsertDerivar($_POST);                        
        //else         
            //$p = $obj->update($_POST);                                
        /*
        if ($p[0]==1)                
            $result = array(1,'',$p[2]);                
        else                 
            $result = array(2,$p[1],'');
        print_r(json_encode($result));
        */
        if ($p[0]=="1") 
            $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
    
}

?>