<?php
include_once("Main.php");
class valores extends Main
{    
    function edit($id)
    {   
        $sql="SELECT *  
              FROM  evaluacion.valores
              WHERE idaspecto = :id ";
        $stmt = $this->db->prepare($sql);        
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function save($items) 
    {       
        $fecha_reg = date('Y-m-d');        
        $idperiodo = (!isset($_SESSION['idperiodo'])) ? '1' : $_SESSION['idperiodo'];
        $estado = 1;
        $ids = "";
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            //Eleminamos los Eliminados
            $ida = "";$idc="";
            foreach ($items as $k => $v) 
            {
                if($v->idvalor!="")
                    $ids .= $v->idvalor.",";
                $ida = $v->idconsultorio;
                $idc = $v->idaspecto;
            }
            $ids .= "0";
            $delete = $this->db->prepare("DELETE FROM evaluacion.valores where idvalor not in (".$ids.")
                                                    and idaspecto = ".$ida." and idconsultorio = ".$idc);
            $delete->execute();

            //News y Updates
            foreach ($items as $k => $v) 
            {

                if($v->idvalor=="")
                {
                    //*** New                    
                    $n = $this->vParametro($v->idparam);
                    if($n==0)
                    {
                        $insert = $this->db->prepare("INSERT INTO evaluacion.valores (idaspecto,
                                                                                      idparametro,
                                                                                      idconsultorio,
                                                                                      orden,
                                                                                      valor,
                                                                                      fecha_reg,
                                                                                      estado,
                                                                                      idperiodo) 
                                                        VALUES (:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8); ");
                        $insert->bindParam(':p1',$v->idaspecto,PDO::PARAM_INT);
                        $insert->bindParam(':p2',$v->idparam,PDO::PARAM_INT);
                        $insert->bindParam(':p3',$v->idconsultorio,PDO::PARAM_INT);
                        $insert->bindParam(':p4',$v->order,PDO::PARAM_INT);
                        $insert->bindParam(':p5',$v->valor,PDO::PARAM_INT);
                        $insert->bindParam(':p6',$fecha_reg,PDO::PARAM_STR);
                        $insert->bindParam(':p7',$estado,PDO::PARAM_INT);
                        $insert->bindParam(':p8',$idperiodo,PDO::PARAM_INT);
                        $insert->execute();
                    }
                }
                else 
                {
                    //Edit                    
                    $update = $this->db->prepare("UPDATE evaluacion.valores set valor = :p1, 
                                                                                orden = :p2 
                                                        where idvalor = :p0 ");
                    $update->bindParam(':p1',$v->valor,PDO::PARAM_INT);
                    $update->bindParam(':p2',$v->order,PDO::PARAM_INT);
                    $update->bindParam(':p0',$v->idvalor,PDO::PARAM_INT);
                    $update->execute();
                }
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
    
    function getValores($_G)
    {
        $idperiodo = (!isset($_SESSION['idperiodo'])) ? '1' : $_SESSION['idperiodo'];
        $idconsultorio = $_G['idconsultorio'];
        $idaspecto = $_G['idaspecto'];

        $stmt = $this->db->prepare("SELECT  v.idvalor,
                                            v.idparametro,
                                            p.descripcion,
                                            v.orden,
                                            v.valor
                                    FROM evaluacion.valores as v inner join evaluacion.parametros as p 
                                            on p.idparametro = v.idparametro 
                                    WHERE v.idperiodo = :p1 and v.idconsultorio = :p2 and v.idaspecto = :p3");
        $stmt->bindParam(':p1',$idperiodo,PDO::PARAM_INT);
        $stmt->bindParam(':p2',$idconsultorio,PDO::PARAM_INT);
        $stmt->bindParam(':p3',$idaspecto,PDO::PARAM_INT);
        $stmt->execute();
        $data = array();
        foreach ($stmt->fetchAll() as $row) 
        {
            $data[] = array('idvalor'=>$row['idvalor'],
                            'idparametro'=>$row['idparametro'],
                            'parametro'=>$row['descripcion'],
                            'order'=>$row['orden'],
                            'valor'=>(int)$row['valor']);
        }
        return $data;
    }
    function vParametro($idparametro)
    {
        $idperiodo = (!isset($_SESSION['idperiodo'])) ? '1' : $_SESSION['idperiodo'];
        $stmt = $this->db->prepare("SELECT count(*) as n from evaluacion.valores 
                                    where idparametro = :p1 and idperiodo = :p2");
        $stmt->bindParam(':p1',$idparametro,PDO::PARAM_INT);
        $stmt->bindParam(':p2',$idperiodo,PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetchObject();
        return $r->n;
    }
}
?>