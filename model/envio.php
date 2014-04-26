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
            substr(cast(t.fechainicio as text),9,2)||'/'||substr(cast(t.fechainicio as text),6,2)||'/'||substr(cast(t.fechainicio as text),1,4),
            t.asunto,
            '<a class=\"printer box-boton boton-print\" id=\"f-'||t.idtramite||'-'||t.idtipo_documento||'\" href=\" #\" title=\"Imprimir Documento\" ></a>'
            FROM
            evaluacion.tramite AS t
            INNER JOIN tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento ";

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $ver="SELECT
            t.idtramite,
            t.idtipo_documento
            FROM
            evaluacion.tramite AS t 
            WHERE t.idtramite= :p1 ";
        $stmt1 = $this->db->prepare($ver);
        $stmt1->bindParam(':p1', $id, PDO::PARAM_INT);
        $stmt1->execute();
        $row= $stmt1->fetchObject();
        $idtpdoc= $row->idtipo_documento;        

        if ($idtpdoc== 1) {

            $stmt = $this->db->prepare("SELECT
                t.idtramite,
                t.fechainicio,
                t.idpersonalresp,
                t.horainicio,
                t.asunto,
                t.problema,
                t.docref,
                t.idperdestinatario,
                t.idtipo_documento,
                t.codigo
                FROM
                evaluacion.tramite AS t
                INNER JOIN tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento
                WHERE
                t.idtramite = :id ");
            $stmt->bindParam(':id', $id , PDO::PARAM_INT);
            $stmt->execute();
        }

        if ($idtpdoc== 2 || $idtpdoc== 3) {
            $stmt = $this->db->prepare("SELECT
                t.idtramite,
                t.fechainicio,
                t.horainicio,
                t.asunto,
                t.problema,
                t.docref,
                t.idperdestinatario,
                t.idtipo_problema,
                t.idtipo_documento,
                t.codigo,
                t.idareai,
                t.idpersonalresp,
                t.resultados,
                p.idpaciente,
                p.nrodocumento,
                p.nombres,
                p.appat,
                p.apmat,
                p.direccion,
                p.telefono,
                p.celular
                
                FROM
                evaluacion.tramite AS t
                INNER JOIN tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento
                LEFT JOIN paciente AS p ON p.idpaciente = t.idpaciente

                WHERE
                t.idtramite = :id ");
            $stmt->bindParam(':id', $id , PDO::PARAM_INT);
            $stmt->execute();
        }    
        
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
        $derivado='N';

        $obj_td->UpdateCorrelativo($idtipodocumento);
        
        /* MEMORANDUN */
        $sql = "INSERT INTO evaluacion.tramite(
                idperdestinatario,idtipo_documento, problema, fechainicio, horainicio, idpersonalresp, 
                asunto, estado, docref, codigo,derivado)

            VALUES(:p1,:p2,:p3,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12) ";

        $stmt = $this->db->prepare($sql);

        /* INGRESAR PACIENTE NUEVO */
        $sqlcli="INSERT INTO paciente(nrodocumento,nombres,appat,apmat,direccion,telefono,celular)
                VALUES ( :p11, :p21,:p31, :p41, :p51, :p61, :p71) ";

        $stmt3  = $this->db->prepare($sqlcli);

        /* SNC y IR */
        $sqlf1 = "INSERT INTO evaluacion.tramite(idpaciente, idtipo_documento, problema, 
            fechainicio, horainicio, usuarioreg, estado, codigo, 
            idtipo_problema, idareai, idpersonalresp,derivado)

                VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12) ";

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
                
                $stmt->bindParam(':p7', $_P['idperremitente'] , PDO::PARAM_STR);
                $stmt->bindParam(':p8', $_P['asunto'] , PDO::PARAM_STR);
                $stmt->bindParam(':p9', $estado , PDO::PARAM_STR);
                $stmt->bindParam(':p10', $_P['docref'] , PDO::PARAM_STR);
                $stmt->bindParam(':p11', $_P['correlativo'] , PDO::PARAM_STR);
                $stmt->bindParam(':p12', $derivado , PDO::PARAM_STR);

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
                    $tprob= $_P['idtipo_problema'];
                    if($tprob == 1)
                    {
                        $idcon= $_P['idareai'];
                        $idperinvestigad= 0;
                    }else
                        {
                            $idperinvestigad =$_P['idareai'];
                            $idcon= 0;
                        }
                    
                    $stmt1->bindParam(':p1', $idpaciente , PDO::PARAM_INT);
                    $stmt1->bindParam(':p2', $_P['idtipo_documento'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p3', $_P['problema'] , PDO::PARAM_STR);
                    //$stmt1->bindParam(':p4', $_P['resultados'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p4', $_P['fechainicio'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p5', $_P['horainicio'] , PDO::PARAM_STR);
                                            
                    //$stmt1->bindParam(':p7', $fechafin , PDO::PARAM_STR);
                    //$stmt1->bindParam(':p8', $horafin , PDO::PARAM_STR);
                    $stmt1->bindParam(':p6', $idpersonalreg , PDO::PARAM_INT);
                    $stmt1->bindParam(':p7', $estado , PDO::PARAM_STR);
                    //$stmt1->bindParam(':p11', $_P['idcierre'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p8', $_P['correlativo'] , PDO::PARAM_STR);

                    $stmt1->bindParam(':p9', $_P['idtipo_problema'] , PDO::PARAM_INT);
                    $stmt1->bindParam(':p10', $idcon , PDO::PARAM_INT);
                    $stmt1->bindParam(':p11', $idperinvestigad , PDO::PARAM_INT);
                    $stmt1->bindParam(':p12', $derivado , PDO::PARAM_STR);

                    $stmt1->execute();
                    //print_r($stmt1);
                }

            $this->db->commit();            
            return array('1','Bien!',$idpaciente);
        }
        catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 
        
    }

    function update($_P ) 
    {
        $idtpdoc= $_P['idtipo_documento'];        
        
        $sqlm = "UPDATE evaluacion.tramite
            SET 
            idpersonalresp= :p1, 
            asunto= :p2, 
            problema= :p3, 
            docref= :p4,
            idperdestinatario= :p5
            WHERE idtramite= :idtramite";

        $stmt = $this->db->prepare($sqlm);
                        
        $sqlo = "UPDATE evaluacion.tramite
            SET 
            idpaciente= :p1, 
            problema= :p2, 
            idtipo_problema= :p3,
            idareai= :p4,
            idperdestinatario= :p5
            
        WHERE idtramite= :idtramite";        
        
        $stmt1 = $this->db->prepare($sqlo);        
        
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();
            
            if ($idtpdoc== 1){
                
                $stmt->bindParam(':p1', $_P['idperremitente'] , PDO::PARAM_INT);        
                $stmt->bindParam(':p2', $_P['asunto'] , PDO::PARAM_STR);
                $stmt->bindParam(':p3', $_P['problema'] , PDO::PARAM_STR);     
                $stmt->bindParam(':p4', $_P['docref'] , PDO::PARAM_STR);
                $stmt->bindParam(':p5', $_P['idpersonal'] , PDO::PARAM_INT);
                
                $stmt->bindParam(':idtramite', $_P['idtramite'] , PDO::PARAM_INT);
                $stmt->execute();
            }
                else {
                    
                    $tprob= $_P['idtipo_problema'];
                        if($tprob == 1)
                        {
                            $idcon= $_P['idareai'];
                            $idperinvestigad= 0;

                        }else
                            {
                                $idperinvestigad =$_P['idareai'];
                                $idcon= 0;
                            }
            
                    $stmt1->bindParam(':p1', $_P['idpaciente'] , PDO::PARAM_INT);        
                    $stmt1->bindParam(':p2', $_P['problema'] , PDO::PARAM_STR);
                    $stmt1->bindParam(':p3', $tprob , PDO::PARAM_INT);     
                    $stmt1->bindParam(':p4', $idcon , PDO::PARAM_INT);
                    $stmt1->bindParam(':p5', $idperinvestigad , PDO::PARAM_INT);
                    
                    $stmt1->bindParam(':idtramite', $_P['idtramite'] , PDO::PARAM_INT);
                    $stmt1->execute();
                }                            

            $this->db->commit();            
            return array('1','Bien!',$idtpdoc);

        }
            catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 
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