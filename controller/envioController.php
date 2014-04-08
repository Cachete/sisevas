<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/envio.php';

class EnvioController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'t.idtramite','align'=>'center','width'=>70),
                        2 => array('Name'=>'Descripcion','NameDB'=>'td.descripcion','width'=>250,'search'=>true),
                        3 => array('Name'=>'Maderba','NameDB'=>'t.codigo','width'=>120),
                        4 => array('Name'=>'Medida','NameDB'=>'t.fechainicio','align'=>'right','width'=>70),                        
                        5 => array('Name'=>'Precio Unitario','NameDB'=>'t.asunto','align'=>'right','width'=>100)
                     );

    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];

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
        $obj = new Envio();        
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
        $data['tipodoc'] = $this->Select(array('id'=>'idtipo_documento','name'=>'idtipo_documento','text_null'=>':: Seleccione ::','table'=>'vista_tipodocumento'));
        $view->setData($data);
        $view->setTemplate( '../view/envio/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new Envio();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $data['tipodoc'] = $this->Select(array('id'=>'idtipo_documento','name'=>'idtipo_documento','text_null'=>':: Seleccione ::','table'=>'vista_tipodocumento','code'=>$obj->idtipo_documento));
        $view->setData($data);
        $view->setTemplate( '../view/envio/_form.php' );
        echo $view->renderPartial();
    }

    public function save()
    {
        $obj = new Envio();
        $result = array();        
        if ($_POST['idtramite']=='') 
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
        $obj = new Envio();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function getList()
    {
        $obj = new Envio();
        $idlinea = (int)$_GET['idl'];
        $rows = $obj->getList($idlinea);
        print_r(json_encode($rows));
    }

    public function nuevos()
    {
        $obj = new Envio();
        print_r(json_encode($obj->nuevos()));
        
    }

    public function getStock()
    {
        $obj = new Envio();
        $idtramite = (int)$_GET['id'];
        $idalmacen = (int)$_GET['a'];
        $p = $obj->getStock($idtramite,$idalmacen);
        echo $p;
    }    
}
?>