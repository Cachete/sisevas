<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/config.php';
class configController extends Controller
{   
     
    public function edit() {
        $obj = new config();
        $data = array();
        $view = new View();
        $obj = $obj->edit($_GET['id']);
        $data['obj'] = $obj;      
        $view->setData($data);
        $view->setTemplate( '../view/config/_form.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
    public function get()
    {
        $obj = new config();
        $obj = $obj->edit(1);
        print_r(json_encode(array('descripcion'=>$obj->descripcion,'logo'=>$obj->logo)));        
    }
    
    
   public function save()
   {
        $obj = new config();
        if ($_POST['idconfig']=='') {
            $p = $obj->insert($_POST);
            if ($p[0]){
                header('Location: index.php');
            } else {
            $data = array();
            $view = new View();
            $data['msg'] = $p[1];
            $data['url'] =  'index.php?controller=config';
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            $view->setlayout( '../template/layout.php' );
            $view->render();
            }
        } else {
            $p = $obj->update($_POST);
            if ($p[0]){
                header('Location: index.php');
            } else {
            $data = array();
            $view = new View();
            $data['msg'] = $p[1];
            $data['url'] =  'index.php?controller=config';
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            $view->setlayout( '../template/layout.php' );
            $view->render();
            }
        }
    }
   
   
   
}
?>