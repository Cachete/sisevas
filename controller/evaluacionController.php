<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/personal.php';
require_once '../model/evaluacion.php';
class evaluacionController extends Controller 
{
    public function index()
    {
        $obj = new personal();
        $eva = new evaluacion();
        $data = array();
        $view = new View();
        $data['rows']  = $obj->edit($_GET['idp']);
        $data['competencias'] = $this->Select(array('name'=>'idcompetencia','id'=>'idcompetencia','table'=>'evaluacion.competencias','text_null'=>'Seleccione una Competencia'));
        $data['competencias_r'] = $eva->getCompetencias();
        $view->setData( $data );
        $view->setTemplate( '../view/evaluacion/_index.php');
        $view->setLayout('../template/evaluacion.php');
        $view->render();
    }
    public function getAspectos()
    {
        $obj = new evaluacion();
        $data = array();
        $view = new View();
        $data['rows']  = $obj->getAspectos($_GET);
        $view->setData( $data );
        $view->setTemplate( '../view/evaluacion/_resultados.php' );
        echo $view->renderPartial();
    }
    public function save()
    {
        $obj = new evaluacion();
        $_POST['v'] = stripslashes($_POST['v']);
        $v = json_decode($_POST['v']);
        //Verificar si es posible grabar los cambios de acuerdo a los periodos
        $resp = $obj->save($v,$_POST['idp']);
        print_r(json_encode($resp));
    }
}
?>