<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/subproductosemi.php';

class SubProductoSemiController extends Controller
{
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'p.idsubproductos_semi','align'=>'center','width'=>40),
                        2 => array('Name'=>'Productos','NameDB'=>'ps.descripcion','width'=>70,'search'=>true),
                        3 => array('Name'=>'Descripcion','NameDB'=>'p.descripcion','width'=>220,'search'=>true),
                        4 => array('Name'=>'Precio','NameDB'=>'p.precio','align'=>'rigth','width'=>70),                        
                        5 => array('Name'=>'Estado','NameDB'=>'p.estado','width'=>'30','align'=>'center','color'=>'#FFFFFF')
                     );
    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['titulo']="Catalogo de Productos";
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
        $obj = new SubProductoSemi();        
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
        $data['productos_semi'] = $this->Select(array('id'=>'idproductos_semi','name'=>'idproductos_semi','text_null'=>'Seleccione...','table'=>'produccion.vista_productosemi'));
        $data['UnidadMedida']= $this->Select(array('id'=>'idunidad_medida','name'=>'idunidad_medida','text_null'=>'.: Seleccione :.','table'=>'vista_unidadmedida'));
        $view->setData($data);
        $view->setTemplate( '../view/subproductosemi/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() {
        $obj = new SubProductoSemi();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $data['productos_semi'] = $this->Select(array('id'=>'idproductos_semi','name'=>'idproductos_semi','text_null'=>'Seleccione...','table'=>'produccion.vista_productosemi','code'=>$obj->idproductos_semi));
        $data['UnidadMedida']= $this->Select(array('id'=>'idunidad_medida','name'=>'idunidad_medida','text_null'=>'.: Seleccione :.','table'=>'vista_unidadmedida','code'=>$obj->idunidad_medida));
        $view->setData($data);
        $view->setTemplate( '../view/subproductosemi/_form.php' );
        echo $view->renderPartial();
        
    }

    public function save()
    {
        $obj = new SubProductoSemi();
        $result = array();        
        if ($_POST['idsubproductos_semi']=='') 
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
        $obj = new SubProductoSemi();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function getList()
    {
        $obj = new SubProductoSemi();
        $idproductos_semi = (int)$_GET['idl'];
        $rows = $obj->getList($idproductos_semi);
        print_r(json_encode($rows));
    }
    
    public function get()
    {
        $obj = new SubProductoSemi();
        $data = array();        
        $field = "ps.descripcion || ' ' || sps.descripcion";
        if($_GET['tipo']==1) $field = "idsubproductos_semi";
        $value = $obj->get($_GET["term"],$field);

        $result = array();
        foreach ($value as $key => $val) 
        {
            array_push($result, array(
                        "idsubproductos_semi"=>$val['idsubproductos_semi'],                                         
                        "producto"=> strtoupper(rtrim($val['producto'])),
                        "precio"=>$val['precio']
                    )
                );
            if ( $key > 7 ) { break; }
        }
        print_r(json_encode($result));
    }
    
    public function getstock()
    {
        $obj = new SubProductoSemi();        
        $data = $obj->stock($_GET['a'],$_GET['i']);
        print_r(json_encode($data));
        
    }

    //Para las consultas es decir reportes
    public function load_stockprod()
    {
        $obj = new SubProductoSemi();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->ViewResultado($_GET['idalm']);
        //$data['idalmacen']= $_GET['idalm'];
        $view->setData($data);
        $view->setTemplate( '../view/subproductosemi/_consulstock.php' );
        echo $view->renderPartial();
    }



}

?>