<?php
include_once("Main.php");
class Proveedor extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            p.idproveedor,
            p.ruc,
            p.razonsocial,
            p.dni,
            p.replegal,
            p.telefono,            
            p.direccion,
            u.descripcion,
            case p.estado when 1 then 'ACTIVO' else 'INACTIVO' end,            
            p.email,
            p.obs,            
            p.contacto,
            p.idubigeo
            
            FROM
            public.proveedor AS p
            LEFT JOIN public.ubigeo AS u ON u.idubigeo = p.idubigeo ";

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT
            p.*,
            u.descripcion
            FROM
            proveedor AS p
            INNER JOIN ubigeo AS u ON u.idubigeo = p.idubigeo
            WHERE idproveedor = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        /*$stmt = $this->db->prepare("INSERT INTO proveedor(dni, razonsocial, 
                        replegal, telefono, direccion,contacto, email, estado,idubigeo,obs)
                values(:p1,:p2,:p3,:p5,:p6,:p7,:p8,:p9,:p10)");*/
        $stmt = $this->db->prepare("INSERT INTO proveedor(dni, razonsocial, 
                        replegal, telefono, direccion,contacto, email, estado,obs,ruc,idubigeo)
                values(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11)");
       
        $stmt->bindParam(':p1', $_P['dni'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['razonsocial'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['replegal'] , PDO::PARAM_STR);        
        $stmt->bindParam(':p4', $_P['telefono'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['direccion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p6', $_P['contacto'] , PDO::PARAM_STR);
        $stmt->bindParam(':p7', $_P['email'] , PDO::PARAM_STR);
        $stmt->bindParam(':p8', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p9', $_P['obs'] , PDO::PARAM_STR);
        $stmt->bindParam(':p10', $_P['ruc'] , PDO::PARAM_STR);
        $stmt->bindParam(':p11', $_P['iddistrito'] , PDO::PARAM_INT);
        //print_r($stmt);
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }
    function update($_P ) 
    {
        $sql = "UPDATE proveedor set 
                            dni=:p1,
                            razonsocial=:p2,
                            replegal=:p3,
                            telefono=:p4,
                            direccion=:p5,
                            contacto=:p6,
                            email=:p7,
                            estado=:p8,
                            ruc=:p9,
                            obs=:p10,
                            idubigeo= :p11
                    WHERE   idproveedor = :idproveedor ";
        $stmt = $this->db->prepare($sql);
                
        $stmt->bindParam(':p1', $_P['dni'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['razonsocial'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['replegal'] , PDO::PARAM_STR);        
        $stmt->bindParam(':p4', $_P['telefono'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['direccion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p6', $_P['contacto'] , PDO::PARAM_STR);
        $stmt->bindParam(':p7', $_P['email'] , PDO::PARAM_STR);
        $stmt->bindParam(':p8', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p9', $_P['ruc'] , PDO::PARAM_INT);
        $stmt->bindParam(':p10', $_P['obs'] , PDO::PARAM_STR);
        $stmt->bindParam(':p11', $_P['iddistrito'] , PDO::PARAM_INT);

        $stmt->bindParam(':idproveedor', $_P['idproveedor'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM proveedor WHERE idproveedor = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function get($query,$field)
    {
        $query = "%".$query."%";
        $statement = $this->db->prepare("SELECT idproveedor, 
                                                razonsocial,
                                                ruc
                                         FROM proveedor
                                         WHERE {$field} ilike :query and ruc <> ''
                                         limit 10");
        $statement->bindParam (":query", $query , PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
?>