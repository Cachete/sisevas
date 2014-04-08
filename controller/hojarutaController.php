<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/hojaruta.php';

class HojaRutaController extends Controller
{
    var $cols = array(
                    1 => array('Name'=>'Codigo','NameDB'=>'h.idhojarutas','align'=>'center','width'=>'50'),
                    2 => array('Name'=>'Zona','NameDB'=>"'z.descripcion || ' - ' || u.descripcion' ",'width'=>'120','align'=>'left'),
                    3 => array('Name'=>'Ruta','NameDB'=>'r.descripcion','search'=>true),
                    4 => array('Name'=>'Personal','NameDB'=>" 'p.nombres' || ' ' || 'p.apellidos' ",'search'=>true),                    
                    5 => array('Name'=>'Fecha','NameDB'=>'h.fechareg','width'=>'50','align'=>'center')
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
        $obj = new HojaRuta();        
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
        $data['Distrito'] = $this->Select(array('id'=>'idubigeo','name'=>'idubigeo','text_null'=>'Seleccione...','table'=>'vista_distrito'));
        $data['Rutas'] = $this->Select(array('id'=>'idrutas','name'=>'idrutas','text_null'=>'Seleccione...','table'=>'vista_rutas'));       
        $view->setData($data);
        $view->setTemplate( '../view/hojaruta/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() {
        $obj = new HojaRuta();
        $data = array();
        $view = new View();
        $rows = $obj->edit($_GET['id']);
        $data['obj'] = $rows;
        $data['Distrito'] = $this->Select(array('id'=>'idubigeo','name'=>'idubigeo','text_null'=>'Seleccione...','table'=>'vista_distrito','code'=>$rows->idubigeo));
        $data['idzona'] = $this->Select(array('id'=>'idzona','name'=>'idzona','text_null'=>'Seleccione...','table'=>'vista_zona','code'=>$rows->idzona));
        $data['Rutas'] = $this->Select(array('id'=>'idrutas','name'=>'idrutas','text_null'=>'Seleccione...','table'=>'vista_rutas','code'=>$rows->idrutas));       
        $data['rowsd'] = $obj->getDetails($rows->idhojarutas);
        $view->setData($data);
        $view->setTemplate( '../view/hojaruta/_form.php' );
        echo $view->renderPartial();
        
    }

    public function save()
    {
        $obj = new HojaRuta();
        $result = array();        
        if ($_POST['idhojarutas']=='')
            
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
        $obj = new HojaRuta();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }
   
    //Para el las consultas es decir reportes
    public function load_hojarutas()
    {
        $obj = new HojaRuta();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->ViewResultado($_GET);
        $view->setData($data);
        $view->setTemplate( '../view/hojaruta/_consulhojaruta.php' );
        echo $view->renderPartial();
    }

    //MOSTRAR DETALLE DE LOS REPORTES
    public function detalle()
    {
        $obj = new HojaRuta();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->rptDetails($_GET['id']);
        $view->setData($data);
        $view->setTemplate( '../view/hojaruta/_rptDetalle.php' );
        $view->setLayout( '../template/list.php' );
        $view->render();
    }

}

?>