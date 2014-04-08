<?php

include_once("Main.php");

class Tipodocumento extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)    
    {
        $sql = "SELECT
            t.idtipo_documento,
            t.descripcion,
            t.abreviado,
            case t.estado when 1 then 'ACTIVO' else 'INCANTIVO' end
            FROM
            tipo_documento AS t ";    
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM tipo_documento WHERE idtipo_documento = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function insert($_P ) {
        $stmt = $this->db->prepare("INSERT INTO tipo_documento (descripcion, abreviado, estado) 
                            VALUES(:p1,:p2,:p3)");

        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['abreviado'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function update($_P ) {
        $stmt = $this->db->prepare("UPDATE tipo_documento 
                            set 
                            descripcion = :p1, 
                            abreviado = :p2,
                            estado = :p3 

                            WHERE idtipo_documento = :idtipo_documento");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['abreviado'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);

        $stmt->bindParam(':idtipo_documento', $_P['idtipo_documento'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM tipo_documento WHERE idtipo_documento = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    public function GCorrelativo($idtp)
    {
        $sql = "SELECT
            upper(t.abreviado) as abre,           
            c.serie,
            c.numero            
            FROM
            correlativo AS c
            INNER JOIN tipo_documento AS t ON t.idtipo_documento = c.idtipodocumento
            WHERE c.idtipodocumento='$idtp' AND c.estado=1 ";    
        $stmt=$this->db->prepare($sql);
        $stmt->execute();
        $data = array();
        $row= $stmt->fetchObject();

        $Abreviatura= $row->abre;
        $Serie = $row->serie;
        $Serie= str_pad($Serie, 4, "000", STR_PAD_LEFT);

        $Num =$row->numero;
        $Num= str_pad($Num, 6,"00000", STR_PAD_LEFT);

        $correlativo= $Abreviatura.''.$Serie.''.$Num;
        //$data = array('serie'=>$Serie,'numero'=>$Num, 'abre'=>$Abreviatura);
        $data = array('correlativo' =>$correlativo );
        return $data;
    }

    public function UpdateCorrelativo($idtp)
    {
        $s = $this->db->prepare("SELECT * from correlativo 
                                where idtipodocumento = :id ");
        $s->bindParam(':id',$idtp,PDO::PARAM_INT);
        //$s->bindParam(':ids',$_SESSION['idsucursal'],PDO::PARAM_INT);
        $s->execute();
        $r = $s->fetchObject();
        $vserie = $r->serie;
        if($r->numero>=$r->valormaximo)
            {$vs = $r->valorminimo;$vserie=$r->serie+1;}
        else 
            {$vs = $r->numero+$r->incremento;}
        $sql = "UPDATE correlativo set numero = {$vs} , 
                                    serie = {$vserie}
                                where idtipodocumento = :id ";
                                
        $s = $this->db->prepare($sql);
        $s->bindParam(':id',$idtp,PDO::PARAM_INT);
        $s->execute();
    }
}
?>