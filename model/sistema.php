<?php
/*session_start();*/
include_once("Main.php");
class Sistema extends Main
{
    function menu()
    {
        $stmt = $this->db->prepare("SELECT * from seguridad.view_menupadres where idperfil = :p1");
        $stmt->bindValue(':p1', $_SESSION['id_perfil'] , PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();
        $cont = 0; 
        $cont2 = 0;
        foreach ($items as $valor)
        {
            $stmt = $this->db->prepare("SELECT * from seguridad.view_menuhijos where idpadre=".$valor['idmodulo']." and idperfil=:p1");
            $stmt->bindValue(':p1', $_SESSION['id_perfil'] , PDO::PARAM_INT);
            $stmt->execute();
            $hijos = $stmt->fetchAll();
            $url = "";
            if(trim($valor['url'])=="") { $url = "#"; }
            else {
                    $url = $valor['url'];
                    if($valor['controlador']!="")
                    {
                        if($valor['accion']=="")
                        {
                            $url .= "?controller=".$valor['controlador'];
                        }
                        else 
                        {
                            $url .= "?controller=".$valor['controlador']."&action=".$valor['accion'];
                        }
                    }
                 }            
            $menu[$cont] = array(
                                'texto' => $valor['descripcion'],
                                'url' => $url,
                                'enlaces' => array()
                );
            $cont2 = 0;
            foreach($hijos as $h)
            {
                $urlm = "";
                if(trim($h['url'])=="") {$urlm = "#";}
                else {
                        $urlm = $h['url'];
                        if($h['controlador']!="")
                        {
                            if($h['accion']=="")
                            {
                                $urlm .= "?controller=".$h['controlador'];
                            }
                            else 
                            {
                                $urlm .= "?controller=".$h['controlador']."&action=".$h['accion'];
                            }
                        }
                     }
              $menu[$cont]['enlaces'][$cont2] = array('idmodulo'=>$h['idmodulo'],'texto' => $h['descripcion'],'url' => $urlm);
              $cont2 ++;
            }
            $cont ++;
        }
        return $menu;
    }    
}

/*$obj = new Sistema();
print_r($obj->menu());*/
?>