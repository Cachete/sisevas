<?php
class FrontControllerException extends Exception {}
class FrontController 
{
    public static function Main() 
    {
        $controllerDir = "../controller/";    
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if(isset($_GET['controller']))            
                    $controller = $_GET['controller'];
                if(isset($_GET['action']))
                   $action = $_GET['action'];            
                break;
            case 'POST':
                if(isset($_POST['controller']))            
                    $controller = $_POST['controller'];            
                if(isset($_POST['action']))            
                    $action = $_POST['action'];            
                break;
            default:
                break;
        }        
        if(!self::urlReferer()) die;
        if( empty( $controller ))
            $controller = "index";    
        else 
            $controller = mb_strtolower($controller);
        if( empty( $action ) )     
            $action = "index";    
        if (!isset($_SESSION['user']) && empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'  )     
            header('Location: login.php');    
        if( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' &&!isset ($_SESSION['user'])   ) 
        {
             header('NOT_AUTHORIZED: 499');
             die();
        }    
        $controllerFile = $controllerDir . $controller . "Controller.php";
        if( !file_exists( $controllerFile )) 
            header('location:../web/notfound.php?f='.$controller."Controller.php");    
        else
           require_once $controllerFile;    
        $controllerClass = $controller . "Controller";

        if( !class_exists( $controllerClass,false) ) 
            throw new FrontControllerException( "El controlador fue cargado pero no se encontro la clase" );

        $controllerInst = new $controllerClass();
        if( !is_callable( array( $controllerInst, $action ) ) ) 
            throw new FrontControllerException( "El controlador no tiene definida la accion $action" );        
        else 
            $controllerInst->$action();         
    }
    public static function urlReferer()
    {
        // $urlRef_ = $_SERVER['HTTP_REFERER'];        
        // $urlRef = explode("http://", $urlRef_);
        // $c = count($urlRef);
        // if($c>1)
        // { 
        //     $urlRef = explode("/", $urlRef[1]);
        //     if(strtolower($urlRef[0])=="localhost") return true;
        //         else return false;
        // }
        // else 
        // {            
        //     if($urlRef_=="") return true;
        //         else return false;
        // }
        return true;
    }
}
?>