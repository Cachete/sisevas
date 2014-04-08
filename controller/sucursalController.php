<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/sucursal.php';

class SucursalController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'s.idsucursal','align'=>'center','width'=>50),
                        2 => array('Name'=>'Descripcion','NameDB'=>'s.descripcion','width'=>250,'search'=>true),
                        3 => array('Name'=>'Sede','NameDB'=>'se.descripcion','width'=>250,'search'=>true),
                        4 => array('Name'=>'Estado','NameDB'=>'s.estado','width'=>90,'align'=>center)
                       
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
        $obj = new Sucursal();        
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
        $data['idsede'] = $this->Select(array('id'=>'idsede','name'=>'idsede','text_null'=>'Seleccione...','table'=>'seguridad.vista_sedes'));              
        $view->setData($data);
        $view->setTemplate( '../view/sucursal/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new Sucursal();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $data['idsede'] = $this->Select(array('id'=>'idsede','name'=>'idsede','text_null'=>'Seleccione...','table'=>'seguridad.vista_sedes','code'=>$obj->idsede));               
        $view->setData($data);
        $view->setTemplate( '../view/sucursal/_form.php' );
        echo $view->renderPartial();
    }

    public function save()
    {
        $obj = new Sucursal();
        $result = array();        
        if ($_POST['idsucursal']=='') 
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
        $obj = new Sucursal();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
    
    }
 

?>