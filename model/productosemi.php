<?php
include_once("Main.php");
class ProductoSemi extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            p.idproductos_semi,
            p.descripcion,
            case p.estado when 1 then 'ACTIVO' else 'INCANTIVO' end
            FROM
            produccion.productos_semi AS p ";    
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM produccion.productos_semi WHERE idproductos_semi = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function insert($_P ) {
        $stmt = $this->db->prepare("INSERT INTO produccion.productos_semi (descripcion) VALUES(:p1)");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        //$stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_BOOL);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function update($_P ) {
        $stmt = $this->db->prepare("UPDATE produccion.productos_semi 
                set descripcion = :p1 
                WHERE idproductos_semi = :idproductos_semi");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        //$stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_BOOL);
        $stmt->bindParam(':idproductos_semi', $_P['idproductos_semi'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM produccion.productos_semi WHERE idproductos_semi = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>