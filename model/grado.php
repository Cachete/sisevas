<?php
include_once("Main.php");
class Grado extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT s.idgradinstruccion,
                       s.descripcion,
                       case s.estado WHEN 1 then 'ACTIVO' else 'INCANTIVO' end
                FROM grado_instruccion as s  ";    
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM grado_instruccion WHERE idgradinstruccion = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function insert($_P ) {
        $stmt = $this->db->prepare("INSERT INTO grado_instruccion (descripcion, estado) VALUES(:p1,:p2)");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function update($_P ) {
        $stmt = $this->db->prepare("UPDATE grado_instruccion 
            set descripcion = :p1,            
                estado = :p2
            WHERE 
            idgradinstruccion = :idgradinstruccion");

        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);

        $stmt->bindParam(':idgradinstruccion', $_P['idgradinstruccion'] , PDO::PARAM_INT);
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM grado_instruccion WHERE idgradinstruccion = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function getUnidades($idmaterial)
    {

        $stmt= $this->db->prepare("SELECT distinct um.idgradinstruccion,um.simbolo FROM grado_instruccion as um
                                        inner join tipo_unidad as tu on 
                                            um.idtipo_unidad = tu.idtipo_unidad
                                            inner join produccion.materiales as m
                                            on m.idtipo_unidad = tu.idtipo_unidad
                                        where m.idmateriales = :id    ");

        $stmt->bindParam(':id',$idmaterial,PDO::PARAM_INT);
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll() as $r)
        {
            $data[] = array('id'=>$r[0],'descripcion'=>$r[1]);

        }
        return $data;
    }   
}
?>