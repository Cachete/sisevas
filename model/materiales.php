<?php
include_once("Main.php");
class Materiales extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
       $sql = "SELECT
        m.idmateriales,
        m.descripcion,
        t.descripcion,
        m.estado
        FROM
        produccion.materiales AS m
        INNER JOIN tipo_unidad AS t ON t.idtipo_unidad = m.idtipo_unidad ";

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM produccion.materiales WHERE idmateriales = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT INTO produccion.materiales(descripcion,stock,idunidad_medida,estado)
                                    values(:p1,:p2,:p3,:p4) ");
            
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);       
        $stmt->bindParam(':p2', $_P['stock'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idunidad_medida'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['activo'] , PDO::PARAM_INT);        
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }

    function update($_P ) 
    {
        $sql = "UPDATE produccion.materiales 
                set descripcion=:p1,
                    stock=:p2,
                    idunidad_medida=:p3,
                    estado =:p4             
                WHERE idmateriales = :idmateriales ";
        $stmt = $this->db->prepare($sql);
            
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);       
        $stmt->bindParam(':p2', $_P['stock'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idunidad_medida'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['activo'] , PDO::PARAM_INT); 

            $stmt->bindParam(':idmateriales', $_P['idmateriales'] , PDO::PARAM_INT);


        $p1 = $stmt->execute();

        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM produccion.materiales WHERE idmateriales = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }


}
?>