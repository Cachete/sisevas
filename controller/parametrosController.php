<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/parametros.php';
class parametrosController extends Controller 
{
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'idparametro','width'=>10,'align'=>'center'),
                        2 => array('Name'=>'Descripcion','NameDB'=>'descripcion','width'=>80,'search'=>true),                        
                        3 => array('Name'=>'Estado','NameDB'=>'estado','width'=>25,'align'=>'center')
                     );

    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['titulo'] = "Habilidad";
        
        $data['actions'] = array(true,true,false,false);
        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }
    
    public function indexGrid() 
    {
        $obj = new parametros();        
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
        $data['competencias'] = $this->Select(array('name'=>'idcompetencia','id'=>'idcompetencia','table'=>'evaluacion.competencias'));
        $view->setData($data);
        $view->setTemplate( '../view/parametros/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new parametros();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $data['competencias'] = $this->Select(array('name'=>'idcompetencia','id'=>'idcompetencia','table'=>'evaluacion.competencias','code'=>$obj->idcompetencia));
        $view->setData($data);
        $view->setTemplate( '../view/parametros/_form.php' );
        echo $view->renderPartial();
    }
    
    public function save()
    {
        $obj = new parametros();
        $result = array();        
        if ($_POST['idparametro']=='') 
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
        $obj = new parametros();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function get()
    {
        $obj = new parametros();
        $data = array();        
        $field = "descripcion";
        
        $value = $obj->get($_GET["term"],$field);
        $result = array();
        foreach ($value as $key => $val) 
        {
          array_push($result, array(
                                    "idparametro"=>$val['idparametro'],
                                    "descripcion"=> $val['descripcion']
                                )
                    );
          if ( $key > 15 ) { break; }
        }
        print_r(json_encode($result));
    }
}
?>