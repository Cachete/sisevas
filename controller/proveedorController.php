<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/proveedor.php';

class ProveedorController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'p.idproveedor','width'=>60),
                        2 => array('Name'=>'RUC','NameDB'=>'p.ruc','width'=>100,'search'=>true),
                        3 => array('Name'=>'Razon Social','NameDB'=>'p.razonsocial','width'=>150,'search'=>true),
                        4 => array('Name'=>'DNI','NameDB'=>'p.dni','align'=>'center','width'=>80),
                        5 => array('Name'=>'Replegal','NameDB'=>'p.replegal','width'=>120,'search'=>true),
                        6 => array('Name'=>'Telefono','NameDB'=>'p.telefono','width'=>70),
                        7 => array('Name'=>'Direccion','NameDB'=>'p.direccion','width'=>100),
                        8 => array('Name'=>'Ubigeo','NameDB'=>'u.descripcion','width'=>100),
                        9 => array('Name'=>'Estado','NameDB'=>'p.estado','align'=>'center','width'=>50)
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
        $obj = new Proveedor();        
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
        $data['Departamento'] = $this->Select(array('id'=>'Departamento','name'=>'Departamento','text_null'=>'Seleccione...','table'=>'vista_dep'));
        //$data['idcargo'] = $this->Select(array('id'=>'idcargo','name'=>'idcargo','text_null'=>'Seleccione...','table'=>'produccion.vista_cargo'));
        $view->setData($data);
        $view->setTemplate( '../view/proveedor/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new Proveedor();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $var = $obj->idubigeo;
        $IdDep = substr($var,0,2).'0000';        
        $Idpro = substr($IdUbigeo,0,4).'00';
        
        $data['Departamento'] = $this->Select(array('id'=>'Departamento','name'=>'Departamento','text_null'=>'Seleccione...','table'=>'vista_dep','code'=>$obj->IdDep));
        $data['idprovincia'] = $this->Select(array('id'=>'idprovincia','name'=>'idprovincia','text_null'=>'Seleccione...','table'=>'produccion.vista_cargo','code'=>$obj->Idpro));
        $view->setData($data);
        $view->setTemplate( '../view/proveedor/_form.php' );
        echo $view->renderPartial();
    }
    public function save()
    {
        $obj = new Proveedor();
        $result = array();        
        if ($_POST['idproveedor']=='') 
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
        $obj = new Proveedor();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
    public function get()
    {
        $obj = new proveedor();
        $data = array();        
        $field = "razonsocial";
        if($_GET['tipo']==1) $field = "ruc";
        $value = $obj->get($_GET["term"],$field);

        $result = array();
        foreach ($value as $key => $val) 
        {
              array_push($result, array(
                                        "idproveedor"=>$val['idproveedor'], 
                                        "ruc"=>$val['ruc'],
                                        "razonsocial"=> strtoupper($val['razonsocial'])
                                    )
                        );
              if ( $key > 7 ) { break; }
        }
        print_r(json_encode($result));
    }
}
?>