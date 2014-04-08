<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/competencias.php';

class competenciasController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'idcompetencias','width'=>10,'align'=>'center'),
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

        //(nuevo,editar,eliminar,ver)
        $data['actions'] = array(true,true,false,false);

        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_indexGrid.php');
        $view->setlayout('../template/layout.php');
        $view->render();
    }
    
    public function indexGrid() 
    {
        $obj = new competencias();        
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
        $view->setData($data);
        $view->setTemplate( '../view/competencias/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new competencias();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;
        $view->setData($data);
        $view->setTemplate( '../view/competencias/_form.php' );
        echo $view->renderPartial();
    }
    
    public function save()
    {
        $obj = new competencias();
        $result = array();        
        if ($_POST['idcompetencia']=='') 
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
        $obj = new competencias();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function get()
    {
        $obj = new competencias();
        $data = array();        
        $field = "p.nombres || ' ' || p.appat || ' ' || p.apmat";
        if($_GET['tipo']==1) $field = "p.nrodocumento";            
        $value = $obj->get($_GET["term"],$field);

        $result = array();
        foreach ($value as $key => $val) 
        {
              array_push($result, array(
                    "idpaciente"=>$val['idpaciente'], 
                    "dni"=>$val['nrodocumento'],
                    "nombres"=> strtoupper($val['nombres']),
                    "apepaterno"=> strtoupper($val['appat']),
                    "apematerno"=> strtoupper($val['apmat']),                            
                    "nompaciente"=> strtoupper($val['nompaciente']),
                    
                    "direccion"=>$val['direccion'],
                    "telefono"=>$val['telefono'],
                    "celular"=>$val['celular'],                                   
                        )
                    );
              if ( $key > 7 ) { break; }
        }
        print_r(json_encode($result));
    }

    public function getProf()
    {
        $obj = new competencias();
        $data = array();        
        $field = "c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno";
        if($_GET['tipo']==1) $field = "c.dni";
        $value = $obj->getProf($_GET["term"],$field);

        $result = array();
        foreach ($value as $key => $val) 
        {
              array_push($result, array(
                        "idcliente"=>$val['idcliente'], 
                        "dni"=>$val['dni'],
                        "idtipocliente"=> $val['idtipocliente'],
                        "nomcliente"=> strtoupper($val['nomcliente']),
                        "sexo"=>$val['sexo'],
                        "direccion"=>$val['direccion'],
                        "referencia"=>$val['referencia_ubic'],
                        "telefono"=>$val['telefono'],
                        "ocupacion"=>$val['ocupacion'],
                        "idestado_civil"=>$val['idestado_civil'],
                        "idgradinstruccion"=>$val['idgradinstruccion'],
                        "idtipovivienda"=>$val['idtipovivienda'],
                        "trabajo"=>$val['trabajo'],
                        "dirtrabajo"=>$val['dirtrabajo'],
                        "teltrab"=>$val['teltrab'],
                        "cargo"=>$val['cargo'],
                        "carga_familiar"=>$val['carga_familiar'],
                        "ingreso"=>$val['ingreso'],
                        "idconyugue"=>$val['idconyugue'],
                        "con_dni"=>$val['con_dni'],
                        "nomconyugue"=>$val['nomconyugue'],
                        "con_ocupacion"=>$val['con_ocupacion'],
                        "con_trabajo"=>$val['con_trabajo'],
                        "con_dirtrabajo"=>$val['con_dirtrabajo'],
                        "con_cargo"=>$val['con_cargo'],
                        "con_ingreso"=>$val['con_ingreso'],
                        "con_teltrab"=>$val['con_teltrab'],
                        "idproforma"=>$val['idproforma']                                         
                    )
                );
            if ( $key > 7 ) { break; }
        }
        print_r(json_encode($result));
    }

}
?>