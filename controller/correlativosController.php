<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/correlativos.php';

class CorrelativosController extends Controller 
{   
    var $cols = array(
                        
                1 => array('Name'=>'Codigo','NameDB'=>'c.idcorrelativo','width'=>80),
                2 => array('Name'=>'Sucursal','NameDB'=>'s.descripcion','width'=>170,'search'=>true),
                3 => array('Name'=>'Tipo documento','NameDB'=>'tpd.descripcion','width'=>170,'search'=>true),
                4 => array('Name'=>'Serie','NameDB'=>'c.serie','align'=>'center'),
                5 => array('Name'=>'Correlativo Máximo','NameDB'=>'c.numero','align'=>'center'),
                6 => array('Name'=>'Estado','NameDB'=>'c.estado','align'=>'center')
                        
            );

    public function index() 
    {        
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['titulo'] = "Instrumentos de Gestion RRHH";
        //(nuevo,editar,eliminar,ver)
        $data['actions'] = array(true,true,true,false);

        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }

    public function indexGrid() 
    {
        $obj = new Correlativos();        
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
        $data['Tipodocumento'] = $this->Select(array('id'=>'idtipo_documento','name'=>'idtipo_documento','text_null'=>'Seleccione...','table'=>'vista_tipodocumento'));
        $data['idsede'] = $this->Select(array('id'=>'idsede','name'=>'idsede','text_null'=>'Seleccione...','table'=>'seguridad.vista_sedes'));       
        $view->setData($data);
        $view->setTemplate( '../view/correlativos/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new Correlativos();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $data['Tipodocumento'] = $this->Select(array('id'=>'idtipo_documento','name'=>'idtipo_documento','text_null'=>'Seleccione...','table'=>'vista_tipodocumento','code'=>$obj->idtipodocumento));
        $data['idsede'] = $this->Select(array('id'=>'idsede','name'=>'idsede','table'=>'seguridad.vista_sedes','code'=>$obj->idsede));   
        $view->setData($data);
        $view->setTemplate( '../view/correlativos/_form.php' );
        echo $view->renderPartial();
    }

    public function save()
    {
        $obj = new Correlativos();
        $result = array();        
        if ($_POST['idcorrelativo']=='') 
            $p = $obj->insert($_POST);                        
        else         
            $p = $obj->update($_POST);                                
        if ($p[0])                
            $result = array(1,'');                
        else                 
            $result = array(2,$p[1]);
        print_r(json_encode($result));

    }

    public function delete()
    {
        $obj = new Correlativos();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
 
}

?>