<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/maderba.php';

class MaderbaController extends Controller
{
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'s.idmaderba','align'=>'center','width'=>70),
                        2 => array('Name'=>'Descripcion','NameDB'=>'s.descripcion','search'=>true),
                        3 => array('Name'=>'Espesor','NameDB'=>'s.espesor','search'=>true),
                        4 => array('Name'=>'Linea','NameDB'=>'l.descripcion','search'=>true),
                        5 => array('Name'=>'Estado','NameDB'=>'s.estado','width'=>'30','align'=>'center','color'=>'#FFFFFF')
                     );
    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];

        $data['actions'] = array(true,true,true,false);

        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }

    public function indexGrid() 
    {
        $obj = new Maderba();        
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
        $data['idlinea'] = $this->Select(array('id'=>'idlinea','name'=>'idlinea','text_null'=>'Seleccione...','table'=>'produccion.vista_linea'));       
        $view->setData($data);
        $view->setTemplate( '../view/maderba/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() {
        $obj = new Maderba();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj; 
        $data['idlinea'] = $this->Select(array('id'=>'idlinea','name'=>'idlinea','table'=>'produccion.vista_linea','code'=>$obj->idlinea));   
        $view->setData($data);
        $view->setTemplate( '../view/maderba/_form.php' );
        echo $view->renderPartial();
        
    }

    public function save()
    {
        $obj = new Maderba();
        $result = array();        
        if ($_POST['idmaderba']=='') 
            $p = $obj->insert($_POST);                        
        else         
            $p = $obj->update($_POST);                                
        if ($p[0])                
            $result = array(1,'',$p[2],$p[3]);                
        else                 
            $result = array(2,$p[1],'','');
        print_r(json_encode($result));

    }
    public function delete()
    {
        $obj = new Maderba();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
   
    public function getList()
    {
        $obj = new Maderba();
        $idmaderba = (int)$_GET['idmad'];
        $rows = $obj->getList($idmaderba);
        print_r(json_encode($rows));
    }

    
}

?>