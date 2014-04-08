<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/verificacion.php';

class VerificacionController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'s.idsolicitud','align'=>'center','width'=>60),
                        2 => array('Name'=>'DNI','NameDB'=>'c.dni','width'=>80,'search'=>true),
                        3 => array('Name'=>'Cliente','NameDB'=>"'c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno'",'align'=>'left','width'=>180),
                        4 => array('Name'=>'Fecha Solicitud','NameDB'=>'s.fechasolicitud','align'=>'center','width'=>100),
                        5 => array('Name'=>'Sucursal','NameDB'=>'su.descripcion','width'=>120),
                        6 => array('Name'=>'Estado','NameDB'=>'s.estado','align'=>'center','width'=>100),
                        7 => array('Name'=>'&nbsp','NameDB'=>'-','align'=>'center','width'=>30),
                        //8 => array('Name'=>'&nbsp','NameDB'=>'-','align'=>'center','width'=>30)
                        
                     );

    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['script'] = "evt_index_verificacion.js";
        $data['titulo'] = "Solicitud";
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
        $obj = new Verificacion();        
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
        $data['tivovivienda'] = $this->Select(array('id'=>'idtipovivienda','name'=>'idtipovivienda','text_null'=>'Seleccione...','table'=>'facturacion.vista_tipovivienda','width'=>'120px'));
        $data['NivelEducacion'] = $this->Select(array('id'=>'idgradinstruccion','name'=>'idgradinstruccion','text_null'=>'Seleccione...','table'=>'vista_grado'));
        $data['EstadoCivil'] = $this->Select(array('id'=>'idestado_civil','name'=>'idestado_civil','text_null'=>'Seleccione...','table'=>'vista_estadocivil'));
        $data['tipopago'] = $this->Select(array('id'=>'idtipopago','name'=>'idtipopago','text_null'=>'Seleccione...','table'=>'produccion.vista_tipopago'));       
        $data['Financiamiento'] = $this->Select(array('id'=>'idfinanciamiento','name'=>'idfinanciamiento','text_null'=>'Seleccione...','table'=>'facturacion.vista_financiamiento'));
        
        $view->setData($data);
        $view->setTemplate( '../view/verificacion/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $estado = $this->getEstado("facturacion.solicitud","idsolicitud",$_GET['id']);
        if($estado==0)
        {
            $obj = new Verificacion();
            $data = array();
            $view = new View();
            $rows = $obj->edit($_GET['id']);
            $data['obj'] = $rows;
            $data['tivovivienda'] = $this->Select(array('id'=>'idtipovivienda','name'=>'idtipovivienda','text_null'=>'Seleccione...','table'=>'facturacion.vista_tipovivienda','code'=>$rows->idtipovivienda));
            $data['NivelEducacion'] = $this->Select(array('id'=>'idgradinstruccion','name'=>'idgradinstruccion','text_null'=>'Seleccione...','table'=>'vista_grado','code'=>$rows->idgradinstruccion));
            $data['EstadoCivil'] = $this->Select(array('id'=>'idestado_civil','name'=>'idestado_civil','text_null'=>'Seleccione...','table'=>'vista_estadocivil','code'=>$rows->idestado_civil));
            $data['tipopago'] = $this->Select(array('id'=>'idtipopago','name'=>'idtipopago','text_null'=>'Seleccione...','table'=>'produccion.vista_tipopago'));       
            $data['Financiamiento'] = $this->Select(array('id'=>'idfinanciamiento','name'=>'idfinanciamiento','text_null'=>'Seleccione...','table'=>'facturacion.vista_financiamiento'));
            $data['rowsd'] = $obj->getDetails($rows->idsolicitud);
            $view->setData($data);
            $view->setTemplate( '../view/verificacion/_form.php' );
            echo $view->renderPartial();
        }else
            {
                $view = new View();
                $data['msg'] = "<b>A esta solicitud ya no se puede realizar ninguna accion. </b><br/><br/>
                      Si deseas ver los datos de esta solicitud 
                      le recomendamos elegir la opcion 'VER' del menu de operaciones.<br/>
                      Si considera que este registro si deveria poder editarse, comuniquese con el administrador del sistema. ";
                $view->setData($data);
                $view->setTemplate( '../view/_error_app.php' );
                echo $view->renderPartial();
            }
        
    }

    public function view() 
    {
        $obj = new Verificacion();
        $data = array();
        $view = new View();
        $rows = $obj->edit($_GET['id']);
        $data['obj'] = $rows;
        $data['tivovivienda'] = $this->Select(array('id'=>'idtipovivienda','name'=>'idtipovivienda','text_null'=>'Seleccione...','table'=>'facturacion.vista_tipovivienda','code'=>$rows->idtipovivienda,'disabled'=>'disabled'));
        $data['NivelEducacion'] = $this->Select(array('id'=>'idgradinstruccion','name'=>'idgradinstruccion','text_null'=>'Seleccione...','table'=>'vista_grado','code'=>$rows->idgradinstruccion,'disabled'=>'disabled'));
        $data['EstadoCivil'] = $this->Select(array('id'=>'idestado_civil','name'=>'idestado_civil','text_null'=>'Seleccione...','table'=>'vista_estadocivil','code'=>$rows->idestado_civil,'disabled'=>'disabled'));
        $data['tipopago'] = $this->Select(array('id'=>'idtipopago','name'=>'idtipopago','text_null'=>'Seleccione...','table'=>'produccion.vista_tipopago'));       
        $data['Financiamiento'] = $this->Select(array('id'=>'idfinanciamiento','name'=>'idfinanciamiento','text_null'=>'Seleccione...','table'=>'facturacion.vista_financiamiento'));
        $data['rowsd'] = $obj->getDetails($rows->idsolicitud);
        $view->setData($data);
        $view->setTemplate( '../view/verificacion/_form.php' );
        echo $view->renderPartial();
    }

    public function save()
    {
        $obj = new Verificacion();
        $result = array();        
        if ($_POST['idsolicitud']=='') 
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
        $obj = new Verificacion();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function anular()
    {
        $obj = new Verificacion();
        $result = array();        
        $p = $obj->anularver($_POST['i']);
        if ($p[0]=="1") $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

}
?>