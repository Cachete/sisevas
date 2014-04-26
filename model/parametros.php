<?php
include_once("Main.php");
class parametros extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT idparametro,
                       descripcion,                       
                       case estado when 1 then 'ACTIVO' else 'INACTIVO' END
                FROM
                       evaluacion.parametros";
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }
    
    function edit($id)
    {   
        $sql="SELECT *  
              FROM  evaluacion.parametros
              WHERE idparametro = :id ";
        $stmt = $this->db->prepare($sql);        
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {       
        $fecha_reg = date('Y-m-d');
        $stmt = $this->db->prepare("INSERT INTO evaluacion.parametros(
                                        descripcion,fecha_reg, estado)
                                    VALUES (:p1, :p2, :p3);");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);        
        $stmt->bindParam(':p2', $fecha_reg , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
    }
    
    function update($_P ) 
    {
         $stmt = $this->db->prepare("UPDATE evaluacion.parametros
                                    SET descripcion=:p1, estado=:p2
                                    WHERE idparametro = :p3;");         
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idparametro'] , PDO::PARAM_INT);        
        $p1 = $stmt->execute();        
        $p2 = $stmt->errorInfo();        
        return array($p1 , $p2[2]);
    }

    function get($query,$field)
    {
        $query = "%".$query."%";
        $statement = $this->db->prepare("SELECT idparametro, 
                                                descripcion
                                         FROM evaluacion.parametros
                                         WHERE {$field} ilike :query and estado = 1
                                         limit 10");
        $statement->bindParam (":query", $query , PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
?>