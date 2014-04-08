<?php
include_once("Main.php");
class Modulo extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT m.idmodulo,
                       m.descripcion,
                       mm.descripcion,
                       m.url,
                       m.controlador,
                       m.accion,
                       case m.estado when true then 'ACTIVO' else 'INCANTIVO' end,
                       m.orden
                from seguridad.modulo as m left outer join seguridad.modulo as mm on mm.idmodulo=m.idpadre";
                
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM seguridad.modulo WHERE idmodulo = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT into seguridad.modulo(idpadre,descripcion,url,estado,orden,controlador,accion)
                                    values(:p1,:p2,:p3,:p5,:p6,:p7,:p8)");
        if($_P['idpadre']==""){$_P['idpadre']=null;}        
        $stmt->bindParam(':p1', $_P['idpadre'] , PDO::PARAM_INT);
        $stmt->bindParam(':p2', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['url'] , PDO::PARAM_STR);        
        $stmt->bindParam(':p5', $_P['activo'] , PDO::PARAM_BOOL);
        $stmt->bindParam(':p6', $_P['orden'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['controlador'] , PDO::PARAM_STR);
        $stmt->bindParam(':p8', $_P['accion'] , PDO::PARAM_STR);
                
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        

        return array($p1 , $p2[2]);
        
    }
    function update($_P ) 
    {
        $sql = "UPDATE seguridad.modulo set  idpadre=:p1,
                                   descripcion=:p2,
                                   url=:p3,
                                   estado=:p5,
                                   orden=:p6,
                                   controlador=:p7,
                                   accion=:p8
                       where idmodulo = :idmodulo";
        $stmt = $this->db->prepare($sql);
        if($_P['idpadre']==""){$_P['idpadre']=null;}        
            $stmt->bindParam(':p1', $_P['idpadre'] , PDO::PARAM_INT);
            $stmt->bindParam(':p2', $_P['descripcion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p3', $_P['url'] , PDO::PARAM_STR);
            $stmt->bindParam(':p5', $_P['activo'] , PDO::PARAM_BOOL);
            $stmt->bindParam(':p6', $_P['orden'] , PDO::PARAM_INT);
            $stmt->bindParam(':idmodulo', $_P['idmodulo'] , PDO::PARAM_INT);
            $stmt->bindParam(':p7', $_P['controlador'] , PDO::PARAM_STR);
            $stmt->bindParam(':p8', $_P['accion'] , PDO::PARAM_STR);   
            
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM seguridad.modulo WHERE idmodulo = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>