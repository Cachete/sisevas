<?php
include_once("Main.php");
class Zona extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT 
            s.idzona,
            u.descripcion AS ubigeo,
            s.descripcion AS zona,

            case s.estado when 1 then 'ACTIVO' else 'INCANTIVO' end

            from zona AS s
            INNER JOIN ubigeo as u ON u.idubigeo = s.idubigeo ";    
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM zona WHERE idzona = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function insert($_P ) {
        $stmt = $this->db->prepare("INSERT INTO zona (descripcion, estado, idubigeo) VALUES(:p1,:p2,:p3)");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idubigeo'] , PDO::PARAM_STR);
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function update($_P ) {
        $stmt = $this->db->prepare("UPDATE zona 
                    set 
                        descripcion = :p1, 
                        estado = :p2,
                        idubigeo= :p3 
                    WHERE idzona = :idzona");

        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idubigeo'] , PDO::PARAM_STR);

        $stmt->bindParam(':idzona', $_P['idzona'] , PDO::PARAM_INT);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM zona WHERE idzona = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function getList($IdZo=null)
    {
        $sql = "SELECT idzona, descripcion from zona ";
        if($IdZo!=null)
        {
            $sql .= " WHERE idubigeo = '{$IdZo}' ";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll() as $r)
        {
            $data[] = array('idzona'=>$r[0],'descripcion'=>$r[1]);
        }
        return $data;
    }


}
?>