<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
//require_once '../model/evaluacion.php';
class evaluacionController extends Controller 
{
    public function index()
    {
        $data = array();
        $view = new View();        
        $view->setData( $data );
        $view->setTemplate( '../view/evaluacion/_index.php' );
        $view->setLayout( '../template/evaluacion.php');
        $view->render();
    }    
    function save()
    {
        
    }    
    
}
?>