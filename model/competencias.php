<?php
include_once("Main.php");
class competencias extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
                       idcompetencia,
                       descripcion,
                       case estado when 1 then 'ACTIVO' else 'INACTIVO' END
                        FROM
                        evaluacion.competencias ";   

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }
    
    function edit($id)
    {   
        $sql="SELECT *  
              FROM  evaluacion.competencias
            WHERE idcompetencia = :id ";
        $stmt = $this->db->prepare($sql);        
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {       
        $stmt = $this->db->prepare("INSERT INTO evaluacion.competencias(
                                        descripcion, estado)
                                    VALUES (:p1, :p2);");

        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
    }
    
    function update($_P ) 
    {
         $stmt = $this->db->prepare("UPDATE evaluacion.competencias
                                    SET descripcion=:p1, estado=:p2
                                    WHERE idcompetencia = :p3;");
         
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idcompetencia'] , PDO::PARAM_INT);
        
        $p1 = $stmt->execute();        
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        
    }

    function get($query,$field)
    {
        $query = "%".$query."%";
        $sql="SELECT
            p.idpaciente,
            p.nrodocumento,
            p.nombres,
            p.appat,
            p.apmat,
            p.nombres || ' ' || p.appat || ' ' || p.apmat AS nompaciente,
            p.direccion,
            p.telefono,
            p.celular
            FROM
            paciente AS p

            WHERE {$field} ilike :query and p.nrodocumento <> ''
            limit 10";
            //echo $sql;
        $statement = $this->db->prepare($sql);

        $statement->bindParam (":query", $query , PDO::PARAM_STR);
        $statement->execute();
        //print_r($statement);
        return $statement->fetchAll();
    }
}

?>