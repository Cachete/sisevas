<?php
include_once("Main.php");
class Sucursal extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = " SELECT
            s.idsucursal,
            s.descripcion,
            se.descripcion,
            case s.estado when 1 then 'ACTIVO' else 'INACTIVO' end
            FROM
            public.sucursales AS s
            INNER JOIN seguridad.sedes AS se ON se.idsede = s.idsede ";
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM sucursales WHERE idsucursal = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT INTO sucursales(descripcion,estado,idsede)
                                    values(:p1,:p2,:p3) ");
               
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);    
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);        
        $stmt->bindParam(':p3', $_P['idsede'] , PDO::PARAM_INT); 
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }

    function update($_P ) 
    {
        $sql = "UPDATE sucursales 
                SET  descripcion=:p1,                    
                    estado=:p2,
                    idsede= :p3

                WHERE idsucursal = :idsucursal";
        $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);            
            $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['idsede'] , PDO::PARAM_INT);

            $stmt->bindParam(':idsucursal', $_P['idsucursal'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();

        return array($p1 , $p2[2]);

    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM sucursales WHERE idsucursal = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>