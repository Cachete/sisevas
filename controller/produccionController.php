<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/produccion.php';
require_once "../lib/Excel/reader.php";
class ProduccionController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'p.idproduccion','align'=>'center','width'=>50),
                        2 => array('Name'=>'Descripcion','NameDB'=>'p.descripcion','width'=>280,'search'=>true),
                        3 => array('Name'=>'Personal','NameDB'=>"pe.nombres || ' ' || pe.apellidos",'align'=>'left','width'=>180,'search'=>true),
                        4 => array('Name'=>'Almacen','NameDB'=>'a.descripcion','align'=>'left','width'=>100,'search'=>true),
                        5 => array('Name'=>'Fecha Reg.','NameDB'=>'p.fecha','align'=>'center','width'=>80),
                        6 => array('Name'=>'Fecha Inicio','NameDB'=>'p.fechai','align'=>'center','width'=>80),
                        7 => array('Name'=>'Fecha Fin','NameDB'=>'p.fechaf','align'=>'center','width'=>80),
                        8 => array('Name'=>'Estado','NameDB'=>'p.estado','align'=>'center','width'=>80),
                        9 => array('Name'=>'','NameDB'=>'','align'=>'center','width'=>20),
                        10 => array('Name'=>'','NameDB'=>'','align'=>'center','width'=>20)
                     );
    
    var $cols_list = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'dp.idproduccion_detalle','align'=>'center','width'=>40),
                        2 => array('Name'=>'Producto','NameDB'=>"ps.descripcion||' '||sps.descripcion",'width'=>200,'search'=>true),
                        3 => array('Name'=>'Cantidad','NameDB'=>"dp.cantidad",'align'=>'center','width'=>50),
                        4 => array('Name'=>'Stock','NameDB'=>'dp.stock','align'=>'center','width'=>50),                        
                        5 => array('Name'=>'Fecha Inicio','NameDB'=>'p.fechaini','align'=>'center','width'=>80,'datefmt'=>'d/m/Y'),
                        6 => array('Name'=>'Fecha Fin','NameDB'=>'p.fechafin','align'=>'center','width'=>80),
                        7 => array('Name'=>'Alamcen','NameDB'=>'a.descripcion','align'=>'center','width'=>100),
                        8 => array('Name'=>'Responsable','NameDB'=>"pp.nombres||' '||pp.apellidos",'align'=>'left','width'=>150)                        
                     );

    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['titulo'] = "Produccion de muebles";
        $data['script'] = "evt_index_produccion.js";        
        $data['actions'] = array(true,true,false,true,false);
        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }
    public function indexGrid() 
    {
        $obj = new Produccion();        
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

    public function lista()
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols_list);
        $data['colsModels'] = $this->getColsModel($this->cols_list);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols_list)));
        $data['controlador'] = $_GET['controller'];
        $data['titulo'] = "Produccion de Muebeles finalizadas";
        $data['script'] = "evt_index_produccion.js";
        $data['actions'] = array(false,false,false,false,false);
        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGridList.php');
        $view->setlayout('../template/list.php');
        $view->render();
    }
    public function indexGridList() 
    {
        $obj = new Produccion();        
        $page = (int)$_GET['page'];
        $limit = (int)$_GET['rows']; 
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $filtro = $this->getColNameDB($this->cols_list,(int)$_GET['f']);
        $query = $_GET['q'];
        if(!$sidx) $sidx = 1;
        if(!$limit) $limit = 10;
        if(!$page) $page = 1;
        echo json_encode($obj->indexGridList($page,$limit,$sidx,$sord,$filtro,$query,$this->getColsVal($this->cols_list)));
    }
    public function create() 
    {
        $data = array();
        $view = new View();
        $data['ProductoSemi'] = $this->Select(array('id'=>'idproductos_semi','name'=>'idproductos_semi','text_null'=>'Seleccione...','table'=>'produccion.vista_productosemi','width'=>'120px'));
        $rowsal = $this->getAlmacenes();
        $data['almacenma'] = $this->Select(array('id'=>'idalmacenma','name'=>'idalmacenma','text_null'=>'','table'=>$rowsal,'width'=>'120px'));                
        $data['almacenme'] = $this->Select(array('id'=>'idalmacenme','name'=>'idalmacenme','text_null'=>'','table'=>$rowsal,'width'=>'120px'));        
        $data['idmadera'] = $this->Select(array('id'=>'idmadera','name'=>'idmadera','text_null'=>'Seleccione...','table'=>'produccion.vista_madera','width'=>'220px'));
        $data['linea'] = $this->Select(array('id'=>'idlinea','name'=>'idlinea','text_null'=>'Elija Linea...','table'=>'produccion.vista_linea','width'=>'100px'));
        $data['idmelamina'] = $this->Select(array('id'=>'idmelamina','name'=>'idmelamina','text_null'=>'Seleccione...','table'=>'produccion.vista_melamina','width'=>'120px'));
        $view->setData($data);
        $view->setTemplate( '../view/produccion/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new Produccion();
        $data = array();
        $view = new View();
        //Comprobamos si podemos editar
        $estado = $this->getEstado("produccion.produccion","idproduccion",$_GET['id']);
        if($estado==1)
        {            
            $rows = $obj->edit($_GET['id']);
            $data['obj'] = $rows;
            $data['ProductoSemi'] = $this->Select(array('id'=>'idproductos_semi','name'=>'idproductos_semi','text_null'=>'Seleccione...','table'=>'produccion.vista_productosemi','width'=>'120px'));                
            $data['idmadera'] = $this->Select(array('id'=>'idmadera','name'=>'idmadera','text_null'=>'Seleccione...','table'=>'produccion.vista_madera','width'=>'220px'));
            $data['linea'] = $this->Select(array('id'=>'idlinea','name'=>'idlinea','text_null'=>'Elija Linea...','table'=>'produccion.vista_linea','width'=>'100px'));
            $data['idmelamina'] = $this->Select(array('id'=>'idmelamina','name'=>'idmelamina','text_null'=>'Seleccione...','table'=>'produccion.vista_melamina','width'=>'120px'));        
            $data['rowsd'] = $obj->getDetails($rows->idproduccion);
            $rowsal = $this->getAlmacenes();
            if(count($data['rowsd'])>0)        
                $data['almacenma'] = $this->Select(array('id'=>'idalmacenma','name'=>'idalmacenma','text_null'=>'','table'=>$rowsal,'width'=>'120px','code'=>$obj->idalmacen,'disabled'=>'disabled'));                        
            else 
                $data['almacenma'] = $this->Select(array('id'=>'idalmacenma','name'=>'idalmacenma','text_null'=>'','table'=>$rowsal,'width'=>'120px','code'=>$obj->idalmacen));                        
            $view->setData($data);
            $view->setTemplate( '../view/produccion/_form.php' );
            echo $view->renderPartial();
        }
        else
        {
            $view = new View();
            $data['msg'] = "<b>Esta produccion ya no es ediable. </b><br/><br/>
                  Si deseas ver los datos de esta produccion 
                  le recomendamos elegir la opcion 'VER' del menu de operaciones.<br/>
                  Si considera que este registro si deveria poder editarse, comuniquese con el administrador del sistema. ";
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            echo $view->renderPartial();
        }
    }
    public function view() 
    {
        $obj = new Produccion();
        $data = array();
        $view = new View();
        $rows = $obj->edit($_GET['id']);
        $data['obj'] = $rows;
        $data['ProductoSemi'] = $this->Select(array('id'=>'idproductos_semi','name'=>'idproductos_semi','text_null'=>'Seleccione...','table'=>'produccion.vista_productosemi','width'=>'120px','disabled'=>'disabled'));                
        $data['idmadera'] = $this->Select(array('id'=>'idmadera','name'=>'idmadera','text_null'=>'Seleccione...','table'=>'produccion.vista_madera','width'=>'220px','disabled'=>'disabled'));
        $data['linea'] = $this->Select(array('id'=>'idlinea','name'=>'idlinea','text_null'=>'Elija Linea...','table'=>'produccion.vista_linea','width'=>'100px','disabled'=>'disabled'));
        $data['idmelamina'] = $this->Select(array('id'=>'idmelamina','name'=>'idmelamina','text_null'=>'Seleccione...','table'=>'produccion.vista_melamina','width'=>'120px','disabled'=>'disabled'));        
        $data['rowsd'] = $obj->getDetails($rows->idproduccion);
        $rowsal = $this->getAlmacenes();
        if(count($data['rowsd'])>0)        
            $data['almacenma'] = $this->Select(array('id'=>'idalmacenma','name'=>'idalmacenma','text_null'=>'','table'=>$rowsal,'width'=>'120px','code'=>$obj->idalmacen,'disabled'=>'disabled'));                        
        else 
            $data['almacenma'] = $this->Select(array('id'=>'idalmacenma','name'=>'idalmacenma','text_null'=>'','table'=>$rowsal,'width'=>'120px','code'=>$obj->idalmacen));                        
        $view->setData($data);
        $view->setTemplate( '../view/produccion/_form.php' );
        echo $view->renderPartial();
    }
    public function save()
    {
        $obj = new Produccion();        
        if(isset($_POST['idproduccion']))
        {            
            if ($_POST['idproduccion']=='') 
            {
                $p = $obj->insert($_POST);
                if ($p[0]==1)
                    $result = array(1,'',$p[2]);
                else                 
                    $result = array(2,$p[1],'');            
            }
            else         
            {
                $estado = $this->getEstado("produccion.produccion","idproduccion",$_POST['idproduccion']);                                
                if($estado==1)
                    $p = $obj->update($_POST); 
                else
                    $restul = array(2,"Esta operacion no se puede realizar.");

                if ($p[0]==1)
                    $result = array(1,'',$p[2]);
                else                 
                    $result = array(2,$p[1],'');            
            }             

        }
        else
        {
            $result = array(2,"Esta operacion no se puede realizar.");
        }
        print_r(json_encode($result));
    }

    public function anular()
    {
        $obj = new Produccion();
        $result = array();        
        $p = $obj->delete($_POST['i']);
        if ($p[0]=="1") $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function end()
    {
        $obj = new Produccion();
        $result = array();        
        $p = $obj->end($_POST['i']);
        if ($p[0]=="1") $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function test()
    {
        // $a = json_decode($_GET['m']);        
        // echo $a->descripcion[0];

        $prod = array('item'=>3,'idps'=>array(1,2,3),'idsps'=>array(5,6,7),'estado'=>array(true,false,true));
        $_P['prod'] = $prod;
        $obj = new Produccion();
        $obj->InsertProduccion($_P);
    }

    //MOSTAR REPORTE
    function load_produccion()
    {
        $obj = new Produccion();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->ViewResultados($_GET);
        $view->setData($data);
        $view->setTemplate( '../view/produccion/_consulproduc.php' );
        echo $view->renderPartial();
    }

    //MOSTRAR DETALLE DE LOS REPORTES
    public function detalle()
    {
        $obj = new Produccion();
        $data = array();
        $view = new View();
        $data['rowsd'] = $obj->rptDetails($_GET['id']);
        $view->setData($data);
        $view->setTemplate( '../view/produccion/_rptDetalle.php' );
        $view->setLayout( '../template/list.php' );
        $view->render();
    }
    public function migrador()
    {
        $obj = new Produccion();
        $data = array();
        $view = new View();
        $data['almacend'] = $this->Select(array('id'=>'idalmacen','name'=>'idalmacen','text_null'=>'','table'=>'produccion.almacenes','width'=>'160px'));                
        $view->setData($data);
        $view->setTemplate( '../view/produccion/migrador.php' );
        $view->setLayout( '../template/list.php' );
        $view->render();
    }
    public function migrar()
    {
        $obj = new Produccion();
        $allow_url_override = 1;
        if(!$allow_url_override || !isset($file_to_include))
        {
            $file_to_include = $_FILES["archivo"]["tmp_name"];
        }
        if(!$allow_url_override || !isset($max_rows))
        {
            $max_rows = 0; //USE 0 for no max
        }
        if(!$allow_url_override || !isset($max_cols))
        {
            $max_cols = 5; //USE 0 for no max
        }
        if(!$allow_url_override || !isset($debug))
        {
            $debug = 0;  //1 for on 0 for off
        }
        if(!$allow_url_override || !isset($force_nobr))
        {
            $force_nobr = 1;  //Force the info in cells not to wrap unless stated explicitly (newline)
        }
        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('CP1251');
        $data->read($file_to_include);
        error_reporting(E_ALL ^ E_NOTICE);
        //die($_SESSION['idusuario']."SS");
        $_P['fechai'] = date('Y-m-d');
        $_P['fechaf'] = date('Y-m-d');        
        $_P['idpersonal'] = $_SESSION['idusuario'];

        $_P['idalmacen']  = $_POST['idalmacen'];
        $_P['idalmacend']  = $_POST['idalmacen'];
        $_P['idproducciontipo'] = 5;

        $cont = 0;
        $_P['prod'] = array();        
        $_P['prod']['idsps']=array();
        $_P['prod']['cantidad']=array();
        $_P['prod']['estado']=array();
        $c = 0;
        $_P['idreferencia'] = "";
        
        for($i=3;$i<=$data->sheets[0]['numRows'];$i++)
        {
            if($data->sheets[0]['cells'][$i][8]!="")
            {
                $c = $c+1;
                $_P['prod']['idalmacen'][] = $_POST['idalmacen'];
                $_P['prod']['idsps'][] = $data->sheets[0]['cells'][$i][8];
                $_P['prod']['cantidad'][] = $data->sheets[0]['cells'][$i][4];
                $_P['prod']['estado'][] = true;
            }
        }
        $_P['prod']['item'] = $c;
        //echo $c;
        $obj->InsertProduccion($_P);
        echo "Se han migrado ".$c." Registros ";
    }
}
?>