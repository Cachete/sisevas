<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/permisos.php';
class PermisosController extends Controller {

    public function Index()
    {
        $data = array();
        $view = new View();
        $data['perfiles'] = $this->Select(array('id'=>'idperfil','name'=>'idperfil','table'=>'seguridad.perfil'));
        $view->setData( $data );
        $view->setTemplate( '../view/permisos/_permisos.php' );
        $view->setLayout( '../template/layout.php');
        $view->render();
    }

    public function Modulos()
    {
        $data = array();
        $objpermisos = new Permisos();
        $data['mod'] = $objpermisos->Modulos($_GET['idperfil']);
        $data['idperfil'] = $_GET['idperfil'];
        $view = new View();
        $view->setData($data);        
        $view->setTemplate( '../view/permisos/_modulos.php' );
        echo $view->renderPartial();
    }

    function Save()
    {
        $obj = new Permisos();
        print_r(json_encode($obj->Save($_GET)));
    }
}

?>