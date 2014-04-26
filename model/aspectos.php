<?php
include_once("Main.php");
class aspectos extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT a.idaspecto,
                       a.descripcion,
                       c.descripcion,
                       case a.estado when 1 then 'ACTIVO' else 'INACTIVO' END
                FROM
                       evaluacion.aspectos as a inner join evaluacion.competencias as c 
                       on c.idcompetencia = a.idcompetencia ";
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }
    
    function edit($id)
    {   
        $sql="SELECT *  
              FROM  evaluacion.aspectos
              WHERE idaspecto = :id ";
        $stmt = $this->db->prepare($sql);        
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {       
        $fecha_reg = date('Y-m-d');
        $stmt = $this->db->prepare("INSERT INTO evaluacion.aspectos(
                                        descripcion, idcompetencia, fecha_reg, estado)
                                    VALUES (:p1, :p2, :p3, :p4);");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idcompetencia'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $fecha_reg , PDO::PARAM_STR);
        $stmt->bindParam(':p4', $_P['activo'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
    }
    
    function update($_P ) 
    {
         $stmt = $this->db->prepare("UPDATE evaluacion.aspectos
                                    SET descripcion=:p1, estado=:p2
                                    WHERE idaspecto = :p3;");         
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idaspecto'] , PDO::PARAM_INT);
        
        $p1 = $stmt->execute();        
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
    }

    function getAspectos($idc)
    {
        $stmt = $this->db->prepare("SELECT idaspecto, descripcion from evaluacion.aspectos 
                                    where estado = 1 and idcompetencia = :id");
        $stmt->bindParam(':id',$idc,PDO::PARAM_INT);
        $stmt->execute();
        $data = array();
        foreach ($stmt->fetchAll() as $r) 
        {            
            $data[] = array('id'=>$r['idaspecto'],'descripcion'=>$r['descripcion']);
        }        
        return $data;
    }
}
?>