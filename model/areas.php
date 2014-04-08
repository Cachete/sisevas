<?php
include_once("Main.php");
class Areas extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            a.idarea,
            a.descripcion,
            s.descripcion,
            case a.estado when 1 then 'ACTIVO' else 'INCANTIVO' end
            
            FROM
            public.area AS a
            LEFT JOIN public.sucursales AS s ON s.idsucursal = a.idsucursal ";    
            
            return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM area WHERE idarea = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function insert($_P ) {
        $stmt = $this->db->prepare("INSERT INTO area (descripcion,estado,idsucursal) 
                    VALUES(:p1,:p3,:p4)");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        //$stmt->bindParam(':p2', $_P['espesor'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['idsucursal'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);

    }

    function update($_P ) {
        $stmt = $this->db->prepare("UPDATE area 
                set descripcion = :p1,
                estado = :p3, 
                idsucursal = :p4
                WHERE idarea = :idarea");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        //$stmt->bindParam(':p2', $_P['espesor'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['idsucursal'] , PDO::PARAM_INT);

        $stmt->bindParam(':idarea', $_P['idarea'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM area WHERE idarea = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>