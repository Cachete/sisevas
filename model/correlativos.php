<?php
include_once("Main.php");
class Correlativos extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
       $sql = "SELECT
        c.idcorrelativo,
        s.descripcion,
        tpd.descripcion,
        c.serie,
        c.numero,
        case c.estado when 1 then 'ACTIVO' else 'INCANTIVO' end
        
        FROM
        correlativo AS c
        INNER JOIN seguridad.sedes AS s ON s.idsede = c.idsede
        INNER JOIN tipo_documento AS tpd ON tpd.idtipo_documento = c.idtipodocumento ";
        
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM correlativo WHERE idcorrelativo = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT INTO correlativo(idsede,idtipodocumento,
                            serie,numero,incremento,valormaximo,valorminimo,estado)
                values(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8) ");
             
        $stmt->bindParam(':p1', $_P['idsede'] , PDO::PARAM_INT);       
        $stmt->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['serie'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['numero'] , PDO::PARAM_INT);        
        $stmt->bindParam(':p5', $_P['incremento'] , PDO::PARAM_INT);       
        $stmt->bindParam(':p6', $_P['valormaximo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['valorminimo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p8', $_P['activo'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }

    function update($_P ) 
    {
        $sql = "UPDATE correlativo 
                SET 
                    idsede= :p1,
                    idtipodocumento=:p2,
                    serie=:p3,
                    numero=:p4,
                    incremento=:p5,
                    valormaximo=:p6,
                    valorminimo=:p7,
                    estado =:p8             
                WHERE idcorrelativo = :idcorrelativo ";

        $stmt = $this->db->prepare($sql);
            
        $stmt->bindParam(':p1', $_P['idsede'] , PDO::PARAM_INT);       
        $stmt->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['serie'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['numero'] , PDO::PARAM_INT);        
        $stmt->bindParam(':p5', $_P['incremento'] , PDO::PARAM_INT);       
        $stmt->bindParam(':p6', $_P['valormaximo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['valorminimo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p8', $_P['activo'] , PDO::PARAM_INT);

        $stmt->bindParam(':idcorrelativo', $_P['idcorrelativo'] , PDO::PARAM_INT);


        $p1 = $stmt->execute();

        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM correlativo WHERE idcorrelativo = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>