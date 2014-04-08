<?php
include_once("Main.php");
class Ocupacion extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            m.idocupacion,
            m.descripcion,
            case m.estado WHEN 1 then 'ACTIVO' else 'INCANTIVO' end
            FROM
            ocupacion AS m ";
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ocupacion WHERE idocupacion = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT INTO ocupacion(descripcion,estado)
                                    values(:p1,:p2) ");
               
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);    
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);        
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }

    function update($_P ) 
    {
        $sql = "UPDATE ocupacion 
                set  descripcion=:p1,                    
                    estado=:p2
                                   
                WHERE idocupacion = :idocupacion";
        $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);            
            $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
            
            $stmt->bindParam(':idocupacion', $_P['idocupacion'] , PDO::PARAM_INT);


        $p1 = $stmt->execute();

        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM ocupacion WHERE idocupacion = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>