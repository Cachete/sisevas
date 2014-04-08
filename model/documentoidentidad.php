<?php
include_once("Main.php");
class Documentoidentidad extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            t.iddocumento_identidad,
            t.descripcion,
            case t.estado WHEN 1 then 'ACTIVO' else 'INCANTIVO' end
            FROM
            documento_identidad AS t";
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM documento_identidad WHERE iddocumento_identidad = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT INTO documento_identidad(descripcion,estado)
                                    values(:p1,:p2) ");
               
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);    
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);        
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }

    function update($_P ) 
    {
        $sql = "UPDATE documento_identidad 
                set  descripcion=:p1,                    
                    estado=:p2
                                   
                WHERE iddocumento_identidad = :iddocumento_identidad";
        $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);            
            $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
            
            $stmt->bindParam(':iddocumento_identidad', $_P['iddocumento_identidad'] , PDO::PARAM_INT);


        $p1 = $stmt->execute();

        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM documento_identidad WHERE iddocumento_identidad = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>