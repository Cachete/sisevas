<?php
include_once("Main.php");
include_once("tipodocumento.php");

class Envio extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        
        $sql = "SELECT
            t.idtramite,
            td.descripcion,
            t.codigo,
            t.fechainicio,
            t.asunto
            FROM
            evaluacion.tramite AS t
            INNER JOIN tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento ";

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT
                    *
                    FROM
                    evaluacion.tramite AS t
                    INNER JOIN tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento
                    WHERE
                    t.idtramite = :id ");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        //print_r($_P) ;
        $obj_td = new Tipodocumento();
        
        $estado='T';
        $idtipodocumento= $_P['idtipo_documento'];
        $idpaciente= $_P['idremitente'];
        $idpersonalreg= $_SESSION['idusuario'];
        $horafin= date('H:i:s');
        $fechafin= date('Y-m-d');       

        $obj_td->UpdateCorrelativo($idtipodocumento);
        
        /**/
        $sql = "INSERT INTO evaluacion.tramite(
                idperdestinatario,idtipo_documento, problema, fechainicio, horainicio, usuarioreg, 
                asunto, estado, docref, codigo)

            VALUES(:p1,:p2,:p3,:p5,:p6,:p7,:p8,:p9,:p10,:p11) ";

        $stmt = $this->db->prepare($sql);

        /**/
        $sqlcli="INSERT INTO paciente(nrodocumento,nombres,appat,apmat,direccion,telefono,celular)
                VALUES ( :p11, :p21,:p31, :p41, :p51, :p61, :p71) ";

        $stmt3  = $this->db->prepare($sqlcli);

        /**/
        $sqlf1 = "INSERT INTO evaluacion.tramite(
                idpaciente, idtipo_documento, problema, resultados, fechainicio, horainicio, fechafin, horafin, 
                usuarioreg, estado, idcierre, codigo, idtipo_problema, idareai, idpersonalresp)

                VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15) ";

        $stmt1 = $this->db->prepare($sqlf1);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            if ($_P['idtipo_documento']== 1) {

                $stmt->bindParam(':p1', $_P['idpersonal'] , PDO::PARAM_STR);
                $stmt->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_STR);
                $stmt->bindParam(':p3', $_P['problema'] , PDO::PARAM_STR);
                $stmt->bindParam(':p5', $_P['fechainicio'] , PDO::PARAM_STR);
                $stmt->bindParam(':p6', $_P['horainicio'] , PDO::PARAM_STR);        
                
                $stmt->bindParam(':p7', $idpersonalreg , PDO::PARAM_STR);
                $stmt->bindParam(':p8', $_P['asunto'] , PDO::PARAM_STR);
                $stmt->bindParam(':p9', $estado , PDO::PARAM_STR);
                $stmt->bindParam(':p10', $_P['docref'] , PDO::PARAM_STR);
                $stmt->bindParam(':p11', $_P['correlativo'] , PDO::PARAM_STR);

                $stmt->execute();
            }
            else
                {
                    if ($idpaciente=='') {
                                                    
                        $stmt3->bindParam(':p11', $_P['dni'] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p21', $_P['nombres'], PDO::PARAM_STR);
                        $stmt3->bindParam(':p31', $_P['apellidopat'] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p41', $_P['apellidomat'] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p51', $_P['direccion'] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p61', $_P['telefono'], PDO::PARAM_STR);
                        $stmt3->bindParam(':p71', $_P['celular'] , PDO::PARAM_STR);

                        $stmt3->execute();
                        $idpaciente =  $this->IdlastInsert('paciente','idpaciente');
                    }  
                
                    $stmt1->bindParam(':p1', $idpaciente , PDO::PARAM_INT);
                    $stmt1->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p3', $_P['problema'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p4', $_P['resultados'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p5', $_P['fechainicio'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p6', $_P['horainicio'] , PDO::PARAM_STR);
                                            
                    $stmt1->bindParam(':p7', $fechafin , PDO::PARAM_STR);
                    $stmt1->bindParam(':p8', $horafin , PDO::PARAM_STR);
                    $stmt1->bindParam(':p9', $idpersonalreg , PDO::PARAM_INT);
                    $stmt1->bindParam(':p10', $estado , PDO::PARAM_STR);
                    $stmt1->bindParam(':p11', $_P['idcierre'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p12', $_P['correlativo'] , PDO::PARAM_STR);

                    $stmt1->bindParam(':p13', $_P['idtipo_problema'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p14', $_P['idareai'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p15', $_P['idpersonalresp'] , PDO::PARAM_INT);
                    
                    $stmt1->execute();

                }

            $this->db->commit();            
            return array('1','Bien!',$id);
        }
        catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 

        /*if ($idtipodocumento != 1) {
                
            if ($idpaciente=='') { 

                    $sqlcli="INSERT INTO paciente(nrodocumento,nombres,appat,
                            apmat,direccion,telefono,celular)
                            VALUES ( :p11, :p21,:p31, :p41, :p51, :p61, :p71) ";

                    $stmt3  = $this->db->prepare($sqlcli);
                                            
                    $stmt3->bindParam(':p11', $_P['dni'] , PDO::PARAM_STR);
                    $stmt3->bindParam(':p21', $_P['nombres'], PDO::PARAM_STR);
                    $stmt3->bindParam(':p31', $_P['apellidopat'] , PDO::PARAM_STR);
                    $stmt3->bindParam(':p41', $_P['apellidomat'] , PDO::PARAM_STR);
                    $stmt3->bindParam(':p51', $_P['direccion'] , PDO::PARAM_STR);
                    $stmt3->bindParam(':p61', $_P['telefono'], PDO::PARAM_STR);
                    $stmt3->bindParam(':p71', $_P['celular'] , PDO::PARAM_STR);

                    $stmt3->execute();
                    $idcliente =  $this->IdlastInsert('paciente','idpaciente');                        

                    $sqlf1 = "INSERT INTO evaluacion.tramite(
                        idpaciente, idtipo_documento, problema, resultados, fechainicio, horainicio, fechafin, horafin, 
                        usuarioreg, estado, idcierre, codigo, idtipo_problema, idareai, idpersonalresp)

                        VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15) ";

                    $stmt1 = $this->db->prepare($sqlf1); 

                    $stmt1->bindParam(':p1', $idcliente , PDO::PARAM_INT);
                    $stmt1->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p3', $_P['problema'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p4', $_P['resultados'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p5', $_P['fechainicio'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p6', $_P['horainicio'] , PDO::PARAM_STR);
                                            
                    $stmt1->bindParam(':p7', $fechafin , PDO::PARAM_STR);
                    $stmt1->bindParam(':p8', $horafin , PDO::PARAM_STR);
                    $stmt1->bindParam(':p9', $idpersonalreg , PDO::PARAM_INT);
                    $stmt1->bindParam(':p10', $estado , PDO::PARAM_STR);
                    $stmt1->bindParam(':p11', $_P['idcierre'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p12', $_P['correlativo'] , PDO::PARAM_STR);

                    $stmt1->bindParam(':p13', $_P['idtipo_problema'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p14', $_P['idareai'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p15', $_P['idpersonalresp'] , PDO::PARAM_INT);
                    //print_r($stmt1);
                    $stmt1->execute();
                    $p1 = $stmt1->execute();
                    $p2 = $stmt1->errorInfo();
                    //return array($p1 , $p2[2]);     
                }else
                    {
                        $idpaciente= $_P['idremitente'];
                        $sqlf1 = "INSERT INTO evaluacion.tramite(
                        idpaciente, idtipo_documento, problema, resultados, fechainicio, horainicio, fechafin, horafin, 
                        usuarioreg, estado, idcierre, codigo, idtipo_problema, idareai, idpersonalresp)

                        VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15) ";

                        $stmt1 = $this->db->prepare($sqlf1); 

                        $stmt1->bindParam(':p1', $idcliente , PDO::PARAM_INT);
                        $stmt1->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_INT);
                        $stmt1->bindParam(':p3', $_P['problema'] , PDO::PARAM_STR);
                        $stmt1->bindParam(':p4', $_P['resultados'] , PDO::PARAM_STR);
                        $stmt1->bindParam(':p5', $_P['fechainicio'] , PDO::PARAM_STR);
                        $stmt1->bindParam(':p6', $_P['horainicio'] , PDO::PARAM_STR);
                                                
                        $stmt1->bindParam(':p7', $fechafin , PDO::PARAM_STR);
                        $stmt1->bindParam(':p8', $horafin , PDO::PARAM_STR);
                        $stmt1->bindParam(':p9', $idpersonalreg , PDO::PARAM_INT);
                        $stmt1->bindParam(':p10', $estado , PDO::PARAM_STR);
                        $stmt1->bindParam(':p11', $_P['idcierre'] , PDO::PARAM_INT);
                        $stmt1->bindParam(':p12', $_P['correlativo'] , PDO::PARAM_STR);

                        $stmt1->bindParam(':p13', $_P['idtipo_problema'] , PDO::PARAM_INT);
                        $stmt1->bindParam(':p14', $_P['idareai'] , PDO::PARAM_INT);
                        $stmt1->bindParam(':p15', $_P['idpersonalresp'] , PDO::PARAM_INT);
                        //print_r($stmt1);
                        $stmt1->execute();
                        $p1 = $stmt1->execute();
                        $p2 = $stmt1->errorInfo();
                    }    
            }
            else
                {
                    $sql = "INSERT INTO evaluacion.tramite(
                        idperdestinatario,idtipo_documento, problema, fechainicio, horainicio, usuarioreg, 
                        asunto, estado, docref, codigo)

                        VALUES(:p1,:p2,:p3,:p5,:p6,:p7,:p8,:p9,:p10,:p11) ";

                    $stmt = $this->db->prepare($sql);

                    $stmt->bindParam(':p1', $_P['idpersonal'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p3', $_P['problema'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p5', $_P['fechainicio'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p6', $_P['horainicio'] , PDO::PARAM_STR);        
                    
                    $stmt->bindParam(':p7', $idpersonalreg , PDO::PARAM_STR);
                    $stmt->bindParam(':p8', $_P['asunto'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p9', $estado , PDO::PARAM_STR);
                    $stmt->bindParam(':p10', $_P['docref'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p11', $_P['correlativo'] , PDO::PARAM_STR);

                    $stmt->execute();
                    $p1 = $stmt->execute();
                    $p2 = $stmt->errorInfo();
                                                                  
                }                   
           
        return array($p1 , $p2[2]);*/
        
    }

    function update($_P ) 
    {
        $sql = "UPDATE produccion.producto 
                SET                    
                    idmaderba=:p3,
                    medidas=:p4,
                    precio_u=:p5,
                    stock=:p6,
                    idunidad_medida=:p7,                    
                    estado=:p8

                WHERE idproducto = :idproducto";

        $stmt = $this->db->prepare($sql);

        //$stmt->bindParam(':p2', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['idmaderba'] , PDO::PARAM_STR);        
        $stmt->bindParam(':p4', $_P['medidas'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['precio_u'] , PDO::PARAM_INT);     
        $stmt->bindParam(':p6', $_P['stock'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['idunidad_medida'] , PDO::PARAM_INT);
        $stmt->bindParam(':p8', $_P['activo'] , PDO::PARAM_INT);

        $stmt->bindParam(':idproducto', $_P['idproducto'] , PDO::PARAM_INT);            
            
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM produccion.producto WHERE idproducto = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function nuevos()
    {
        $stmt = $this->db->prepare("SELECT
                count(t.idtramite)
                FROM
                evaluacion.tramite AS t
                WHERE
                t.idperdestinatario =:p and t.estado = 'T' ");
        $stmt->bindParam(':p',$_SESSION['idusuario'],PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetchAll();
        return array($r[0][0],'');
    }
    
    function getPrice($id)
    {
        $stmt = $this->db->prepare("SELECT precio_u from produccion.producto WHERE idproducto = :p1");
        $stmt->bindParam(':p1', $id, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetchObject();
        return $r->precio_u;
    }

    function getStock($id,$a)
    {
        $sql = "SELECT t.idm,t.c,t.item
                from (
                SELECT max(idmovimiento) as idm ,ctotal_current as c, item
                FROM movimientosdetalle
                where idtipoproducto = 2 and idproducto = :idp and idalmacen = :ida 
                group by ctotal_current,item,idmovimiento
                order by idmovimiento desc
                limit 1) as t
                order by t.item desc ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idp',$id,PDO::PARAM_INT);
        $stmt->bindParam(':ida',$a,PDO::PARAM_INT); 
        $stmt->execute();
        $row = $stmt->fetchObject();
        if($row->c>0)
            return $row->c;
        else return '0.000';
    }
}
?>