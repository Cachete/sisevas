<?php
include_once("Main.php");
class Permisos extends Main
{
    function index($query , $p ) {    }
    function Modulos($p)
    {
        $stmt = $this->db->prepare("SELECT pe.descripcion,m.descripcion,m.idmodulo
                                    from seguridad.permiso as p right outer join seguridad.perfil as pe on p.idperfil = pe.idperfil
                                    right outer join seguridad.modulo as m on p.idmodulo = m.idmodulo and pe.idperfil = :p1
                                    where m.idpadre is null and m.estado = true");
        $stmt->bindValue(':p1', $p , PDO::PARAM_INT);
        $stmt->execute();        
        $items = $stmt->fetchAll();
        $cont = 0; 
        $cont2 = 0;
        foreach ($items as $valor)
        {
            $menu[$cont] = array(
                'perfil' => $valor[0],
                'descripcion' => $valor[1],
                'idmodulo' => $valor[2],
                'hijos' =>$this->getMenus($p,$valor[2])
            );
            $cont ++;
        }
        return $menu;
    }
    public function getMenus($idperfil,$idpadre)
    {
        $stmt = $this->db->prepare("SELECT pe.descripcion,m.descripcion,m.idmodulo
                                        from seguridad.permiso as p right outer join seguridad.perfil as pe on p.idperfil = pe.idperfil
                                        right outer join seguridad.modulo as m on p.idmodulo = m.idmodulo and pe.idperfil = :p1
                                        where  m.idpadre=:p2 and m.estado = true");
        $stmt->bindValue(':p1', $idperfil , PDO::PARAM_INT);
        $stmt->bindValue(':p2', $idpadre , PDO::PARAM_INT);
        $stmt->execute();
        $hijos = $stmt->fetchAll();
        $cont2 = 0;
        foreach($hijos as $h)
        {
          $menu[] = array( 'perfil' => $h[0],
                            'descripcion' => $h[1],
                            'idmodulo' => $h[2],
                            'hijos' => $this->getMenus($idperfil,$h[2])
                          );
        }
        return $menu;
    }
    public function Save($p)
    {
        $stmt = $this->db->prepare("DELETE FROM seguridad.permiso where idperfil = :p1");
        $stmt2 = $this->db->prepare("INSERT INTO seguridad.permiso VALUES(:p2,:p3,true,true,true,true,true)");        
        try{
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db->beginTransaction();
                    $stmt->bindValue(':p1',$p['idperfil'] , PDO::PARAM_INT);
                    $idperfil = $p['idperfil'];
                    $stmt->execute();
                    foreach( $p as $key => $val)
                    {
                        if($key!="controller" && $key!="action" && $key!="idperfil")
                        {
                            $stmt2->bindValue(':p2',$val);
                            $stmt2->bindValue(':p3',$idperfil);
                            $stmt2->execute();
                        }
                    }
                $this->db->commit();
                return array('res'=>"1",'msg'=>'Sus Cambios fueron guardados Correctamente!');
            }
            catch(PDOException $e) {
                $this->db->rollBack();
                return array('res'=>"2",'msg'=>'Error : '.$e->getMessage() . $str);
            }

    }
}
?>