<?php

require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/index.php';
require_once '../model/sistema.php';

class IndexController extends Controller 
{    
    public function Index() 
    {
        $Index = new Index();        
        $data = array();
        $data['html'] = $Index->index();
        $view = new View();
        $view->setData( $data );
        $view->setTemplate( '../view/_index.php' );
        $view->setLayout( '../template/layout.php');
        $view->render();
    }
    
    public function Menu()
    {
        $objsistema = new Sistema();
        print_r(json_encode($objsistema->menu()));
    }
}
?>