<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/pacientes.php';

class PacientesController extends Controller 
{   
    var $cols = array(
                        1 => array('Name'=>'Codigo','NameDB'=>'c.idcliente','width'=>40),
                        2 => array('Name'=>'DNI','NameDB'=>'p.dni','width'=>60,'search'=>true),
                        3 => array('Name'=>'Nombres y Apellidos','NameDB'=>"'p.nombres || ' ' || p.apematerno || ' ' || p.apepaterno'",'width'=>150,'search'=>true),
                        4 => array('Name'=>'Direccion','NameDB'=>'p.direccion','width'=>130),
                        5 => array('Name'=>'Telefono','NameDB'=>'p.telefono','width'=>70),
                        6 => array('Name'=>'Centro Trabajo','NameDB'=>'p.centtrabajo','width'=>90),
                        7 => array('Name'=>'Ocupacion','NameDB'=>'o.descripcion','width'=>100),
                        8 => array('Name'=>'Ubigeo','NameDB'=>'u.descripcion','align'=>'left','width'=>100)
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
        $obj = new Pacientes();        
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
        $data['TpDoc'] = $this->Select(array('id'=>'iddocumento_identidad','name'=>'iddocumento_identidad','text_null'=>'Seleccione...','table'=>'vista_documento_identidad'));
        $data['gruposanguineo'] = $this->Select(array('id'=>'idgrupo_sanguineo','name'=>'idgrupo_sanguineo','text_null'=>'Seleccione...','table'=>'vista_gruposanguineo'));
        $data['NivelEducacion'] = $this->Select(array('id'=>'idgradinstruccion','name'=>'idgradinstruccion','text_null'=>'Seleccione...','table'=>'vista_grado'));
        $data['EstadoCivil'] = $this->Select(array('id'=>'idestado_civil','name'=>'idestado_civil','text_null'=>'Seleccione...','table'=>'vista_estadocivil'));
        $view->setData($data);
        $view->setTemplate( '../view/pacientes/_form.php' );
        echo $view->renderPartial();
    }

    public function edit() 
    {
        $obj = new Pacientes();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;        
        $data['Departamento'] = $this->Select(array('id'=>'Departamento','name'=>'Departamento','text_null'=>'Seleccione...','table'=>'vista_dep','code'=>$obj->IdDep));
        $data['idprovincia'] = $this->Select(array('id'=>'idprovincia','name'=>'idprovincia','text_null'=>'Seleccione...','table'=>'produccion.vista_cargo','code'=>$obj->Idpro));
        $data['TipoVivienda'] = $this->Select(array('id'=>'idtipovivienda','name'=>'idtipovivienda','text_null'=>'Seleccione...','table'=>'facturacion.vista_tipovivienda','code'=>$obj->idtipovivienda));
        $data['TipoCliente'] = $this->Select(array('id'=>'idtipocliente','name'=>'idtipocliente','text_null'=>'Seleccione...','table'=>'vista_tipocliente','code'=>$obj->idtipocliente));
        $data['NivelEducacion'] = $this->Select(array('id'=>'idgradinstruccion','name'=>'idgradinstruccion','text_null'=>'Seleccione...','table'=>'vista_grado','code'=>$obj->idgradinstruccion));
        $data['EstadoCivil'] = $this->Select(array('id'=>'idestado_civil','name'=>'idestado_civil','text_null'=>'Seleccione...','table'=>'vista_estadocivil','code'=>$obj->idestado_civil));
        $view->setData($data);
        $view->setTemplate( '../view/pacientes/_form.php' );
        echo $view->renderPartial();
    }
    
    public function save()
    {
        $obj = new Pacientes();
        $result = array();        
        if ($_POST['idcliente']=='') 
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
        $obj = new Pacientes();
        $result = array();        
        $p = $obj->delete($_GET['id']);
        if ($p[0]) $result = array(1,$p[1]);
        else $result = array(2,$p[1]);
        print_r(json_encode($result));
    }

    public function get()
    {
        $obj = new Pacientes();
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
        $obj = new Pacientes();
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