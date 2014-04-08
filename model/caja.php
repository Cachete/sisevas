<?php
include_once("Main.php");
class Caja extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            c.idcaja,
            c.nombre,
            c.descripcion,
            case c.estado when 1 then 'ACTIVO' else 'INCANTIVO' end          
            
            FROM
            facturacion.caja AS c ";    
            
            return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM facturacion.caja WHERE idcaja = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT
            d.idcajaxpersonal,
            d.idpersonal,
            p.dni,
            p.nombres || ' ' || p.apellidos AS personal

            FROM
            facturacion.cajaxpersonal AS d
            INNER JOIN facturacion.caja AS c ON c.idcaja = d.idcaja
            INNER JOIN personal AS p ON p.idpersonal = d.idpersonal

            WHERE d.idcaja = :id    
            ORDER BY d.idpersonal ");

        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function insert($_P ) {

        $sql="INSERT INTO facturacion.caja (nombre,descripcion, estado) 
                    VALUES(:p1,:p2,:p3)" ;

        $stmt = $this->db->prepare($sql);
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt->bindParam(':p1', $_P['nombre'] , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $_P['descripcion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);
            $stmt->execute();
            $id =  $this->IdlastInsert('facturacion.caja','idcaja');
            $row = $stmt->fetchAll();

            $stmt2  = $this->db->prepare("INSERT INTO facturacion.cajaxpersonal(
                            idpersonal, idcaja)
                        VALUES ( :p1, :p2) ");

                foreach($_P['idpersonal'] as $i => $idpersonal)
                {
                    $stmt2->bindParam(':p1',$idpersonal,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$id,PDO::PARAM_INT);                    
                   
                    $stmt2->execute();                

                }

            $this->db->commit();            
            return array('1','Bien!',$id);

        }
            catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 
    }

    function update($_P ) {

        $idcaja= $_P['idcaja'];
        
         $del="DELETE FROM facturacion.cajaxpersonal
                    WHERE idcaja='$idcaja' ";
                    
            $res = $this->db->prepare($del);
            $res->execute();
            
        $sql="UPDATE facturacion.caja 
                set nombre = :p1, 
                descripcion= :p2, 
                estado = :p3              
                
                WHERE idcaja = :idcaja";
        $stmt = $this->db->prepare($sql);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt->bindParam(':p1', $_P['nombre'] , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $_P['descripcion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);
           
            $stmt->bindParam(':idcaja', $idcaja , PDO::PARAM_INT);
            $stmt->execute();
            
            $stmt2  = $this->db->prepare("INSERT INTO facturacion.cajaxpersonal(
                            idpersonal, idcaja)
                        VALUES ( :p1, :p2) ");

                foreach($_P['idpersonal'] as $i => $idpersonal)
                {
                    $stmt2->bindParam(':p1',$idpersonal,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$idcaja,PDO::PARAM_INT);                    
                   
                    $stmt2->execute();                

                }

            $this->db->commit();            
            return array('1','Bien!',$idcaja);

        }
        catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 
        
    }
    
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM facturacion.caja WHERE idcaja = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>