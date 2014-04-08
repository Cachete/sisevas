<?php
include_once("Main.php");
class Consultorio extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
       $sql = "SELECT
            a.idconsultorio,
            s.descripcion,
            a.descripcion,
            e.descripcion,
            case a.estado when 1 then 'ACTIVO' else 'INCANTIVO' end

            FROM
            consultorio AS a
            INNER JOIN seguridad.sedes AS s ON s.idsede = a.idsede
            INNER JOIN especialidad AS e ON e.idespecialidad = a.idespecialidad ";

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM consultorio WHERE idconsultorio = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT INTO consultorio(descripcion,idsede,idespecialidad,estado)
                    values(:p1,:p2,:p3,:p4) ");
             
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idsede'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['idespecialidad'] , PDO::PARAM_STR);
        $stmt->bindParam(':p4', $_P['activo'] , PDO::PARAM_INT);
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }

    function update($_P ) 
    {
        $sql = "UPDATE consultorio 
                set descripcion=:p1,
                    idsede=:p2,
                    idespecialidad=:p3,
                    estado =:p4
                                
                WHERE idconsultorio = :idconsultorio ";
        $stmt = $this->db->prepare($sql);
            
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idsede'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['idespecialidad'] , PDO::PARAM_STR);
        $stmt->bindParam(':p4', $_P['activo'] , PDO::PARAM_INT);

        $stmt->bindParam(':idconsultorio', $_P['idconsultorio'] , PDO::PARAM_INT);


        $p1 = $stmt->execute();

        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM consultorio WHERE idconsultorio = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>