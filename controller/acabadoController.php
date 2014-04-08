<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/acabado.php';
class acabadoController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'a.idacabado','align'=>'center','width'=>50,'search'=>true),
                        2 => array('Name'=>'Producto','NameDB'=>"ps.descripcion||' '||sps.descripcion",'search'=>true,'hide'=>true),
                        3 => array('Name'=>'Personal Responsable','NameDB'=>"pe.nombres || ' ' || pe.apellidos",'align'=>'left','width'=>180,'search'=>true),
                        4 => array('Name'=>'Cant.','NameDB'=>'a.cantidad','align'=>'center','width'=>50,'search'=>true),
                        5 => array('Name'=>'Fecha Reg.','NameDB'=>'a.fecha','align'=>'center','width'=>80),
                        6 => array('Name'=>'Fecha Inicio','NameDB'=>'a.fechai','align'=>'center','width'=>80),
                        7 => array('Name'=>'Fecha Fin','NameDB'=>'a.fechaf','align'=>'center','width'=>80),
                        8 => array('Name'=>'Almacen','NameDB'=>'al.descripcion','align'=>'center','width'=>80),
                        9 => array('Name'=>'Estado','NameDB'=>'a.estado','align'=>'center','width'=>80),
                        10 => array('Name'=>'','NameDB'=>'','align'=>'center','width'=>20),
                        11 => array('Name'=>'','NameDB'=>'','align'=>'center','width'=>20)
                     );

    public function index() 
    {
        $data = array();                               
        $data['colsNames'] = $this->getColsVal($this->cols);
        $data['colsModels'] = $this->getColsModel($this->cols);        
        $data['cmb_search'] = $this->Select(array('id'=>'fltr','name'=>'fltr','text_null'=>'','table'=>$this->getColsSearch($this->cols)));
        $data['controlador'] = $_GET['controller'];
        $data['titulo'] = "ACABADO DE MATERIALES";
        $data['script'] = "evt_index_acabado.js";        
        $data['actions'] = array(true,true,false,true,false);
        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }
    public function indexGrid() 
    {
        $obj = new acabado();        
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
        $data['idmaterial'] = $this->Select(array('id'=>'idmateriales','name'=>'idmateriales','text_null'=>'Seleccione el material...','table'=>'produccion.materiales','width'=>'220px'));        
        $view->setData($data);
        $view->setTemplate( '../view/acabado/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new acabado();
        $data = array();
        $view = new View();
        //Comprobamos si podemos editar
        $estado = $this->getEstado("produccion.acabado","idacabado",$_GET['id']);
        if($estado==1)
        {            
            $rows = $obj->edit($_GET['id']);
            $data['obj'] = $rows;
            $data['idmaterial'] = $this->Select(array('id'=>'idmateriales','name'=>'idmateriales','text_null'=>'Seleccione el material...','table'=>'produccion.materiales','width'=>'220px'));                    
            $view->setData($data);
            $view->setTemplate( '../view/acabado/_form.php' );
            echo $view->renderPartial();
        }
        else
        {
            $view = new View();
            $data['msg'] = "<b>Esta acabado ya no es ediable. </b><br/><br/>
                  Si deseas ver los datos de esta acabado 
                  le recomendamos elegir la opcion 'VER' del menu de operaciones.<br/>
                  Si considera que este registro si deveria poder editarse, comuniquese con el administrador del sistema. ";
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            echo $view->renderPartial();
        }
    }
    public function getDetails()
    {
        $obj = new acabado();        
        $rows = $obj->getDetails($_GET['id']);
        print_r(json_encode($rows));
    }
    public function view() 
    {
        $obj = new acabado();
        $data = array();
        $view = new View();
        
        $rows = $obj->edit($_GET['id']);
        $data['obj'] = $rows;
        $data['idmaterial'] = $this->Select(array('id'=>'idmateriales','name'=>'idmateriales','text_null'=>'Seleccione el material...','table'=>'produccion.materiales','width'=>'220px'));                    
        $view->setData($data);
        $view->setTemplate( '../view/acabado/_form.php' );
        echo $view->renderPartial();
        
    }
    public function save()
    {
        $obj = new acabado();                   
        if(isset($_POST['idacabado']))
        {            
            if ($_POST['idacabado']=='') 
            {
                $p = $obj->insert($_POST);
                if ($p[0]==1)
                    $result = array(1,'',$p[2]);
                else                 
                    $result = array(2,$p[1],'');            
            }
            else         
            {
                $estado = $this->getEstado("produccion.acabado","idacabado",$_POST['idacabado']);                                
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
        $obj = new acabado();
        $result = array();        
        $p = $obj->delete($_POST['i']);
        if ($p[0]=="1") $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function end()
    {
        $obj = new acabado();
        $result = array();        
        $p = $obj->end($_POST['i']);
        if ($p[0]=="1") $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function test()
    {
        $a = json_decode($_GET['m']);        
        echo $a->descripcion[0];
    }
}
?>