<?php
require_once '../model/Main.php';
class ControllerException extends Exception{}
class Controller  {
    
    protected $registros;
    protected $columnas;
    protected $busqueda;
    /*
     *  $operaciones: Variable que contiene las operaciones basicas
     *  Cada operacion cuenta con un array de accesos. Ejemplo:
     *  Nuevo => array(valor1,valor2) -> 
     *  Valor1 y Valor2 deben ser boleanos.
     *      Valor1 -> Parametro que indica si el usuario tiene el permiso y acceso a esa operacion.
     *      Valor2 -> Parametro que indica si esa operacion estara visible dentro de la grilla.
     */
    protected $operaciones = array('nuevo'=>array(true,true),
                                   'editar'=>array(true,true),
                                   'eliminar'=>array(true,true),
                                   'ver'=>array(true,false),
                                   'seleccionar'=>array(true,false)
                                  );        
    public function  __call($name, $arguments)
    {
        throw new ControllerException("Error! El método {$name}  no está definido.");
    }
    public function more_options($name_controller)
    {
        $obj = new Main();
        $data = array();
        $data['rows'] = $obj->more_options($name_controller);
        $view = new View();
        $view->setData( $data );
        $view->setTemplate( '../view/_more_options.php' );
        return $view->renderPartial();
    }
    public function getAlmacenes()
    {
        $obj = new Main(); 
        return $obj->getAlmacenes($_SESSION['idsucursal']);
    }
    public function Select($p) 
    {
        $obj = new Main();        
        $data = array();
        $obj->filtros = $p['filtros'];
        $obj->table =  $p['table'];
        if(is_array($p['table']))
        {
            $data['rows'] = $p['table'];                           
        }
        else 
        {
            $data['rows'] = $obj->getList();    
        }
        $data['name'] = $p['name'];
        $data['id'] = $p['id'];
        $data['code'] = $p['code'];
        $data['text_null'] = '....';
        $data['width'] = "160px";
        if(isset($data['width']))
        {
            $data['width'] = $p['width'];
        }
        if(isset($p['text_null']))
        {
            $data['text_null'] = $p['text_null'];
        }
        $data['disabled'] = $p['disabled'];   
        
        $view = new View();
        $view->setData( $data );
        $view->setTemplate( '../view/_Select.php' );
        return $view->renderPartial();
    }
    public function getEstado($tabla,$campo,$id)
    {
        $obj = new Main();
        $estado = $obj->getEstado($tabla,$campo,$id);
        return $estado;
    }
    public function Select_ajax($p) {
        $obj = new Main();
        
        $obj->filtros = $p['filtros'];
        $obj->table =  $p['table'];
        $data = array();
        $data['rows'] = $obj->getList();  
        $data['name'] = $p['name'];
        $data['id'] = $p['id'];
        $data['code'] = $p['code'];
        $data['disabled'] = $p['disabled'];
        $data['text_null'] = '....';
        if(isset($p['text_null'])&&$p['text_null']!='')
        {
            $data['text_null'] = $p['text_null'];
        }
        $view = new View();
        $view->setData( $data );
        $view->setTemplate( '../view/_Select_ajax.php' );
        return $view->renderPartial();
    }
    
    public function Pagination( $p ) 
    {
        $data = array();
        $data['rows'] = $p['rows'];
        $data['query'] = $p['query'];
        $data['url'] = $p['url'];
        $data['trows'] = $p['trows'];
        $obj = new Main();
        $data['nrows'] = $obj->rows;
        $view = new View();
        $view->setData( $data );
        $view->setTemplate( '../view/_Pagination.php' );
        return $view->renderPartial();
    }
    public function Combo_Search($options)
    {
        $data = array();
        $data['options'] = $options;
        $view = new View();
        $view->setData($data);
        $view->setTemplate('../view/_Combo_Search.php');
        return $view->renderPartial();
    }

    public function grilla($name,$pag)
    {
        $obj = new Main();        
        $data = array();
        $op = $this->operaciones;
        $data['nr'] = $obj->getnr();
        $data['name'] = $name;
        $data['cols'] = $this->columnas;
        $data['rows'] = $this->registros;              
        
        $data['edit'] = $op['editar'];
        $data['view'] = $op['eliminar'];
        $data['select'] = $op['seleccionar'][1];
        
        $data['option_op'] = $this->opciones_op_adz($pag,$name,$op['nuevo'],$op['editar'],$op['eliminar'],$op['seleccionar'],$op['ver']);
        $data['combo_search'] = $this->Combo_Search($this->busqueda);
        
        $view = new View();
        $view->setData($data);
        $view->setTemplate( '../view/_grilla.php' );
        $view->setLayout( '../template/layout.php' );
        return $view->renderPartial();       
    }
    
    public function opciones_op_adz($pag,$name,$new,$edit,$delete,$seleccionar=false,$view=false)
    {
       $data = array();
       $data['new'] = $new;
       $data['edit'] = $edit;
       $data['delete'] = $delete;
       $data['view'] = $view;
       $data['pag'] = $pag;
       $data['name'] = $name;
       $data['select'] = $seleccionar;
       
       $view = new View();
       $view->setData($data);
       $view->setTemplate( '../view/_option_op_adz.php' );
       $view->setLayout( '../template/layout.php' );
       return $view->renderPartial();
    }
   
     public function asignarAccion($name,$valor)
    {
        $this->operaciones[$name][1] = $valor;        
    }

    public function getColsIndex($array)
    {
        $a = array();
        foreach ($array as $i => $v) {
            $a[$i]=$v['NameDB'];
        }
        return $a;
    }

    public function getColsVal($array)
    {
        $a = array();
        foreach($array as $i => $v)
        {
            $a[] = "<b>".strtoupper($v['Name'])."</b>";

        }
        return $a;
    }

    public function getColsModel($array)
    {
        $a = array();
        $c = 0;
        foreach($array as $i => $v)
        {
            $a[$c]['index'] = $i;

            if(isset($v['Name']))
                $a[$c]['name'] = $v['Name'];

            if(isset($v['width']))
                $a[$c]['width'] = $v['width'];

            if(isset($v['align']))
                $a[$c]['align'] = $v['align'];

            if(isset($v['hide']))
                $a[$c]['hidedlg'] = $v['hide'];

            if(isset($v['datefmt']))
                $a[$c]['datefmt'] = $v['datefmt'];
            

            $c +=1;

            // $a[] = array('name'=>$v['Name'],
            //             'index'=>$i,
            //             'width'=>$v['width'],
            //             'align'=>$v['align'],
            //             'hidedlg'=>$v['hide']);            
        }
        return $a;
    }   

    public function getColsSearch($array)
    {
        $a = array();        
        foreach($array as $i => $v)
        {
            if(isset($v['search'])&&$v['search']==true)
            {
                $a[] = array($i,$v['Name']);
            }            
        }
        return $a;
    }

    public function getColNameDB($array,$index)
    {
        $name_db = "";
        foreach($array as $i => $v)
        {
            if($i==$index)
            {
                $name_db = $v['NameDB'];
            }            
        }
        return $name_db;   
    }
}
?>