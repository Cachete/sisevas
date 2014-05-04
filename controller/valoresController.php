<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/valores.php';
class valoresController extends Controller 
{
    public function index()
    {
        $data = array();
        $view = new View();
        $data['competencias'] = $this->Select(array('id'=>'idcompetencia','name'=>'idcompetencia','table'=>'evaluacion.competencias'));
        $data['consultorios'] = $this->Select(array('id'=>'idconsultorio','name'=>'idconsultorio','table'=>'vista_consultorio'));
        $view->setData( $data );
        $view->setTemplate( '../view/valores/_index.php' );
        $view->setLayout( '../template/layout.php');
        $view->render();
    }    
    function save()
    {
        $obj = new valores();
        $_POST['items'] = stripslashes($_POST['items']);
        $items = json_decode($_POST['items']);
        //Verificar si es posible grabar los cambios de acuerdo a los periodos
        $resp = $obj->save($items,$_POST['idc'],$_POST['ida']);
        print_r(json_encode($resp));
    }
    
    function getValores()
    {
        $obj = new valores();
        $rows = $obj->getValores($_GET);
        print_r(json_encode($rows));
    }

    function vParametro()
    {
        $obj = new valores();
        $n = $obj->vParametro($_GET['idparametro']);
        echo $n;
    }
}
?>