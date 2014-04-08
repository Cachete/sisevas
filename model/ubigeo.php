<?php
include_once("Main.php");
class Ubigeo extends Main
{
    function Provincia($idd)
    {
        $id2 = substr($idd,0,2);
        $sql = "SELECT * FROM ubigeo 
            WHERE idubigeo like '".$id2."%00' AND idubigeo <> '".$id2."0000' 
            ORDER BY descripcion asc";    
        $stmt=$this->db->prepare($sql);
        $stmt->execute();
        $data = array();
        foreach ($stmt->fetchAll() as $row) {
            $data[] = array('codigo'=>$row[0],'descripcion'=>$row[1]);
        }
        return $data;
    }

    function Distrito($idd1)
    {
        $Id1 = substr($idd1,0,4);
        $sql = "SELECT * FROM ubigeo 
            WHERE idubigeo like '".$Id1."%' AND SUBSTRING(idubigeo,5,2) <> '00' ORDER BY descripcion asc";    
        $stmt=$this->db->prepare($sql);
        $stmt->execute();
        $data = array();
        foreach ($stmt->fetchAll() as $row) {
            $data[] = array('codigo'=>$row[0],'descripcion'=>$row[1]);
        }
        return $data;
    }
}
?>