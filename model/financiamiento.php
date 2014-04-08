<?php
include_once("Main.php");
class Financiamiento extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            idfinanciamiento,
            descripcion,
            case estado when 1 then 'ACTIVO' else 'INCANTIVO' end          
            
            FROM
            facturacion.financiamiento  ";    
            
            return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM facturacion.financiamiento WHERE idfinanciamiento = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT   meses,  factor 
            FROM  facturacion.financiamientofactor
            WHERE idfinanciamiento = :id    
            ORDER BY meses ");

        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function insert($_P ) {

        $sql="INSERT INTO facturacion.financiamiento (descripcion,adicional,inicial,estado) 
                    VALUES(:p1,:p2,:p3,:p4)" ;

        $stmt = $this->db->prepare($sql);
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $_P['adicional'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['inicial'] , PDO::PARAM_INT);
            $stmt->bindParam(':p4', $_P['activo'] , PDO::PARAM_INT);
            $stmt->execute();
            $id =  $this->lastInsertId('facturacion.financiamiento','idfinanciamiento');
            $row = $stmt->fetchAll();

            $stmt2  = $this->db->prepare("INSERT INTO facturacion.financiamientofactor(
                            idfinanciamiento, meses,factor)
                        VALUES ( :p1, :p2, :p3) ");

                foreach($_P['meses'] as $i => $meses)
                {
                    $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$meses,PDO::PARAM_INT);                    
                    $stmt2->bindParam(':p3',$_P['factor'][$i],PDO::PARAM_INT);   
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

        $idfinanciamiento= $_P['idfinanciamiento'];
        
         $del="DELETE FROM facturacion.financiamientofactor
                    WHERE idfinanciamiento='$idfinanciamiento' ";
                    
            $res = $this->db->prepare($del);
            $res->execute();
            
        $sql="UPDATE facturacion.financiamiento 
                set descripcion = :p1, 
                adicional= :p2, 
                inicial = :p3 ,
                 estado = :p4  
                
                WHERE idfinanciamiento = :idfinanciamiento";
        $stmt = $this->db->prepare($sql);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $_P['adicional'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['inicial'] , PDO::PARAM_INT);
            $stmt->bindParam(':p4', $_P['activo'] , PDO::PARAM_INT);
            $stmt->bindParam(':idfinanciamiento', $idfinanciamiento , PDO::PARAM_INT);
            $stmt->execute();
            


            $stmt2  = $this->db->prepare("INSERT INTO facturacion.financiamientofactor(
                            idfinanciamiento, meses,factor)
                        VALUES ( :p1, :p2, :p3) ");

                 foreach($_P['meses'] as $i => $meses)
                {
                    $stmt2->bindParam(':p1',$idfinanciamiento,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$meses,PDO::PARAM_INT);                    
                    $stmt2->bindParam(':p3',$_P['factor'][$i],PDO::PARAM_INT);   
                    $stmt2->execute();                

                }

            $this->db->commit();            
            return array('1','Bien!',$idfinanciamiento);

        }
        catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 
        
    }
    
    function delete($_P ) {
        
        $stmt = $this->db->prepare("DELETE FROM facturacion.financiamientofactor WHERE idfinanciamiento = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $stmt = $this->db->prepare("DELETE FROM facturacion.financiamiento WHERE idfinanciamiento = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    public function RFinanciamiento($idfinanc)
    {
        $sql = "SELECT
            f.idfinanciamiento,
            f.descripcion,
            f.adicional,
            f.inicial,
            ff.meses,
            ff.factor
            FROM
            facturacion.financiamiento AS f
            INNER JOIN facturacion.financiamientofactor AS ff ON f.idfinanciamiento = ff.idfinanciamiento

            WHERE ff.idfinanciamiento='$idfinanc' AND f.estado=1 ";    
        $stmt=$this->db->prepare($sql);
        $stmt->execute();       
        $data = array();
        foreach ($stmt->fetchAll() as $row) {
            $data[] = array(
                    'codigo'=>$row[0],
                    'descripcion'=>$row[1],
                    'adicional'=>$row[2],
                    'inicial'=>$row[3],
                    'meses'=>$row[4],
                    'factor'=>$row[5]
                );
        }
        return $data;
    }

}
?>