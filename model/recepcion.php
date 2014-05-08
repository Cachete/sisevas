<?php
include_once("Main.php");
class Recepcion extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {        
        $sql = "SELECT
            t.idtramite,
            td.descripcion,
            t.codigo,
            substr(cast(t.fechainicio as text),9,2)||'/'||substr(cast(t.fechainicio as text),6,2)||'/'||substr(cast(t.fechainicio as text),1,4),
            p.nombres ||' '||p.apellidos,
            substr(cast(t.fechafin as text),9,2)||'/'||substr(cast(t.fechafin as text),6,2)||'/'||substr(cast(t.fechafin as text),1,4),
            t.horafin,
            case t.estado when 'R' 
                    then '<a class=\"printer box-boton boton-print\" id=\"f-'||t.idtramite||'-'||t.idtipo_documento||'-'||d.idpersonal||'\" href=\" #\" title=\"Imprimir Documento\" ></a>'
            else '&nbsp;' end,
            case t.estado when 'T' 
                    then '<a class=\"recepcion box-boton boton-hand\" id=\"f-'||t.idtramite||'\" href=\" #\" title=\"Recepcion de Documento\" ></a>'
            else '&nbsp;' end,
            case t.derivado when 'N' 
                    then '<a class=\"derivar box-boton boton-derivar\" id=\"f-'||t.idtramite||'\" href=\" #\" title=\"Derivar Documento\" ></a>'
            else '<p style=\"color:green;font-weight: bold;\">DERIVADO</p>' end,
            '<input name=\"checkbox\" class=\"capacitacion\" id=\"f-'||t.idtramite||'\" type=\"checkbox\" value=\"1\" />'
            FROM
            evaluacion.tramite AS t
            INNER JOIN public.tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento
            INNER JOIN evaluacion.derivaciones AS d ON t.idtramite = d.idtramite
            INNER JOIN public.personal AS p ON p.idpersonal = d.idpersonal
    
           WHERE
            d.idpersonal = ".$_SESSION['idusuario'];
        //echo $sql;    
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) 
    {
        //print_r($id);
        $ver="SELECT
            t.idtramite,
            t.idtipo_documento
            FROM
            evaluacion.tramite AS t 
            WHERE t.idtramite= ".$id;
        $stmt1 = $this->db->prepare($ver);
        //$stmt1->bindParam(':p1', $id, PDO::PARAM_INT);
        $stmt1->execute();
        //print_r($stmt1);
        $row= $stmt1->fetchObject();
        $idtpdoc= $row->idtipo_documento;        

        if ($idtpdoc== 1) {

            $stmt = $this->db->prepare("SELECT
                t.idtramite,
                t.fechainicio,
                t.usuarioreg,
                t.horainicio,
                t.asunto,
                t.problema,
                t.docref,
                t.idpersonalresp,
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
                t.idcierre,
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

    function insert($_P ) {

        $idvendedor=$_SESSION['idusuario'];
        $estado= 0;
        $sql="INSERT INTO facturacion.proforma(
            idsucursal, idcliente, fecha,hora, estado, observacion,idvendedor,serie,numero) 
            VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9)" ;

        $stmt = $this->db->prepare($sql);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt->bindParam(':p1', $_P['idsucursal'] , PDO::PARAM_INT);
            $stmt->bindParam(':p2', $_P['idcliente'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['fecha'] , PDO::PARAM_STR);
            $stmt->bindParam(':p4', $_P['hora'] , PDO::PARAM_STR);
            $stmt->bindParam(':p5', $estado , PDO::PARAM_INT);
            $stmt->bindParam(':p6', $_P['observacion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p7', $idvendedor , PDO::PARAM_STR);
            $stmt->bindParam(':p8', $_P['serie'] , PDO::PARAM_STR);
            $stmt->bindParam(':p9', $_P['numero'] , PDO::PARAM_STR);
            
            $stmt->execute();
            $id =  $this->IdlastInsert('facturacion.proforma','idproforma');
            $row = $stmt->fetchAll();

            $stmt2  = $this->db->prepare("INSERT INTO facturacion.proformadetalle(
            idproforma, idsucursal, tipo, preciocash, inicial, 
            nromeses, cuota, idfinanciamiento, producto,cantidad,idproducto)
                VALUES ( :p1, :p2,:p3, :p4,:p5, :p6,:p7, :p8,:p9,:p10,:p11 ) ");

            $stmt3  = $this->db->prepare("INSERT INTO facturacion.proformadetalle(
            idproforma, idsucursal, tipo,preciocash, producto,cantidad,idproducto)
                VALUES ( :p1, :p2,:p3, :p4,:p9,:p10,:p11) ");


                foreach($_P['idtipopago'] as $i => $idtipopago)
                {   
                   // echo $idtipopago;          
                    if($idtipopago==2)
                    {
                        $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);
                        $stmt2->bindParam(':p2',$_P['idsucursal'],PDO::PARAM_INT);
                        $stmt2->bindParam(':p3',$idtipopago,PDO::PARAM_INT);
                        $stmt2->bindParam(':p4',$_P['precio'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p5',$_P['inicial'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p6',$_P['nromeses'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p7',$_P['mensual'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p8',$_P['idfinanciamiento'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p9',$_P['producto'][$i],PDO::PARAM_STR);
                        $stmt2->bindParam(':p10',$_P['cantidad'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p11',$_P['idproducto'][$i],PDO::PARAM_INT);
                        $stmt2->execute();
                        
                    } else
                        {
                            $stmt3->bindParam(':p1',$id,PDO::PARAM_INT);
                            $stmt3->bindParam(':p2',$_P['idsucursal'],PDO::PARAM_INT);
                            $stmt3->bindParam(':p3',$idtipopago,PDO::PARAM_INT);
                            $stmt3->bindParam(':p4',$_P['precio'][$i],PDO::PARAM_INT);
                            $stmt3->bindParam(':p9',$_P['producto'][$i],PDO::PARAM_STR);
                            $stmt3->bindParam(':p10',$_P['cantidad'][$i],PDO::PARAM_INT);
                            $stmt3->bindParam(':p11',$_P['idproducto'][$i],PDO::PARAM_INT);
                            $stmt3->execute();
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

    function update($_P ) 
    {
        $idtramite= $_P['idtramite'];
        $horaf= date('H:i:s');
        $fechaf= date('Y-m-d');
        
        $sql="UPDATE evaluacion.tramite
            SET resultados= :p1, 
            fechafin= :p2, 
            horafin= :p3, 
            idcierre= :p4,
            idperdestinatario= :p5,
            estado= 'R'
        WHERE idtramite = :idtramite";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':p1', $_P['resultados'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $fechaf , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $horaf , PDO::PARAM_STR);
        $stmt->bindParam(':p4', $_P['idcierre'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['idpersonal'] , PDO::PARAM_INT);
        
        $stmt->bindParam(':idtramite', $idtramite , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
        
    }
    
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM facturacion.proforma WHERE idproforma = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    //Reporte
    function recibirdoc($p) 
    {   
        $horaf= date('H:i:s');
        $fechaf= date('Y-m-d');
        
            $sql="UPDATE evaluacion.tramite
                SET 
                estado= 'R',
                fechafin= :p2, 
                horafin= :p3
            WHERE idtramite = ".$p;
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':p2', $fechaf , PDO::PARAM_STR);
            $stmt->bindParam(':p3', $horaf , PDO::PARAM_STR);
            
            $r = $stmt->execute();
        
            if($r) return array("1",'Ok, este documento fue recibido');
            else return array("2",'Ha ocurrido un error, porfavor intentelo nuevamente');    
           
    }
    
    //Imrprimir memorandum
    function printDoc($_G)
    {   
        $id= $_G['id'];
        $idper= $_G['idper'];
        
        $cab= "SELECT
            t.idtramite,
            r.nombres||' '||r.apellidos AS remitente,
            d.nombres||' '||d.apellidos AS destinatario,
            substr(cast(t.fechainicio as text),9,2)||'/'||substr(cast(t.fechainicio as text),6,2)||'/'||substr(cast(t.fechainicio as text),1,4) AS fecha,
            t.problema,
            t.asunto,
            t.docref,
            t.codigo,
            td.descripcion,
            cd.descripcion AS cargo_d,
            cr.descripcion AS cargo_r
            FROM
            evaluacion.tramite AS t
            LEFT JOIN public.personal AS r ON r.idpersonal = t.idpersonalresp
            INNER JOIN public.tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento
            LEFT JOIN public.cargo AS cr ON cr.idcargo = r.idcargo
            INNER JOIN evaluacion.derivaciones AS de ON t.idtramite = de.idtramite
            LEFT JOIN public.personal AS d ON d.idpersonal = de.idpersonal
            LEFT JOIN public.cargo AS cd ON cd.idcargo = d.idcargo
    
            WHERE
            de.idpersonal=".$idper." AND t.idtramite= ".$id;

            $stmt = $this->db->prepare($cab);
            //$stmt->bindParam(':id', $id , PDO::PARAM_INT);
 
            $stmt->execute();
            $cab= $stmt->fetchAll();

            return array($cab);
    }
    
    //Imrprimir Servicio de no conformidad y Informe de reclamos
    function printDoc2($_G)
    {   
        $id= $_G['id'];
        $idper= $_G['idper'];
        
        $cab= "SELECT
            t.idtramite,
            t.codigo,
            t.fechainicio,
            t.horainicio,
            p.nombres||' '||p.appat||' '||p.apmat AS paciente,
            p.direccion,
            p.nrodocumento,
            t.problema,
            t.idtipo_problema,
            ci.descripcion AS cierre,
            ai.descripcion AS areai,
            t.idpersonalresp,
            t.idperdestinatario,
            r.nombres||' '||r.apellidos AS remitente,
            d.nombres||' '||d.apellidos AS destinatario,
            t.resultados,
            t.idcierre
                
            FROM
            evaluacion.tramite AS t
            INNER JOIN public.paciente AS p ON p.idpaciente = t.idpaciente
            LEFT JOIN public.cierre AS ci ON ci.idcierre = t.idcierre
            LEFT JOIN public.area AS ai ON ai.idarea = t.idareai
            LEFT JOIN public.personal AS r ON r.idpersonal = t.idpersonalresp
            LEFT JOIN public.personal AS d ON d.idpersonal = t.idperdestinatario    
            WHERE
            t.idtramite=:id ";

            $stmt = $this->db->prepare($cab);
            $stmt->bindParam(':id', $id , PDO::PARAM_INT);

            $stmt->execute();
            $cab= $stmt->fetchAll();
            
            $verc="SELECT
                c.idcierre,
                c.descripcion
                FROM
                cierre AS c ";
            $stmt = $this->db->prepare($verc);
            $stmt->execute();
            $rowsc= $stmt->fetchAll();
            
            return array($cab,$rowsc);
    }
   
   //Imprimir carta de cumpleaños
    function printDoc3($_G)
    {   
        $id= $_G['id'];
        $idper= $_G['idper'];
        
        $cab= "SELECT
            t.idtramite,
            r.nombres||' '||r.apellidos AS remitente,
            d.nombres||' '||d.apellidos AS destinatario,
            substr(cast(t.fechainicio as text),9,2)||'/'||substr(cast(t.fechainicio as text),6,2)||'/'||substr(cast(t.fechainicio as text),1,4) AS fecha,
            t.problema,
            t.asunto,
            t.docref,
            t.codigo,
            td.descripcion,
            cd.descripcion AS cargo_d,
            cr.descripcion AS cargo_r
            FROM
            evaluacion.tramite AS t
            LEFT JOIN public.personal AS r ON r.idpersonal = t.idpersonalresp
            INNER JOIN public.tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento
            LEFT JOIN public.cargo AS cr ON cr.idcargo = r.idcargo
            INNER JOIN evaluacion.derivaciones AS de ON t.idtramite = de.idtramite
            LEFT JOIN public.personal AS d ON d.idpersonal = de.idpersonal
            LEFT JOIN public.cargo AS cd ON cd.idcargo = d.idcargo
    
            WHERE
            de.idpersonal=".$idper." AND t.idtramite= ".$id;

            $stmt = $this->db->prepare($cab);
            //$stmt->bindParam(':id', $id , PDO::PARAM_INT);
 
            $stmt->execute();
            $cab= $stmt->fetchAll();

            return array($cab);
    }
    
    //Imprimir carta de felicitaciones
    function printDoc4($_G)
    {   
        $id= $_G['id'];
        $idper= $_G['idper'];
        
        $cab= "SELECT
            t.idtramite,
            r.nombres||' '||r.apellidos AS remitente,
            d.nombres||' '||d.apellidos AS destinatario,
            substr(cast(t.fechainicio as text),9,2)||'/'||substr(cast(t.fechainicio as text),6,2)||'/'||substr(cast(t.fechainicio as text),1,4) AS fecha,
            t.problema,
            t.asunto,
            t.docref,
            t.codigo,
            td.descripcion,
            cd.descripcion AS cargo_d,
            cr.descripcion AS cargo_r
            FROM
            evaluacion.tramite AS t
            LEFT JOIN public.personal AS r ON r.idpersonal = t.idpersonalresp
            INNER JOIN public.tipo_documento AS td ON td.idtipo_documento = t.idtipo_documento
            LEFT JOIN public.cargo AS cr ON cr.idcargo = r.idcargo
            INNER JOIN evaluacion.derivaciones AS de ON t.idtramite = de.idtramite
            LEFT JOIN public.personal AS d ON d.idpersonal = de.idpersonal
            LEFT JOIN public.cargo AS cd ON cd.idcargo = d.idcargo
    
            WHERE
            de.idpersonal=".$idper." AND t.idtramite= ".$id;

            $stmt = $this->db->prepare($cab);
            //$stmt->bindParam(':id', $id , PDO::PARAM_INT);
 
            $stmt->execute();
            $cab= $stmt->fetchAll();

            return array($cab);
    }
    
    function InsertDerivar($_P)
    {
        //print_r($_P);
        $id= $_P['idtramite'];
        $hora= date('H:i:s');
        $fecha= date('Y-m-d');

        $capacitacion= 'N';   
        
        $sql="INSERT INTO evaluacion.derivaciones(
            idtramite, idpersonal, fechader, horader, capacitacion)
            VALUES(:p1,:p2,:p3,:p4,:p5)" ;

        $stmt = $this->db->prepare($sql);
        
        /*try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();            */
            
            foreach($_P['idtramitedoc'] as $i => $idtramite)
                {   
                    //echo $idtramite;
                    $stmt->bindParam(':p1',$idtramite,PDO::PARAM_INT);
                    $stmt->bindParam(':p2',$_P['idpersonal'][$i],PDO::PARAM_INT);
                    $stmt->bindParam(':p3',$fecha,PDO::PARAM_STR);
                    $stmt->bindParam(':p4',$hora,PDO::PARAM_STR);
                    $stmt->bindParam(':p5',$capacitacion,PDO::PARAM_STR);
                    $stmt->execute();                           
                }
                
                $stmt1 = $this->db->prepare("UPDATE evaluacion.tramite
                    set derivado = 'S'
                        
                    WHERE idtramite = :p1 ");            
                $stmt1->bindParam(':p1', $id, PDO::PARAM_INT);
                $r = $stmt1->execute();
        
            if($r) return array("1",'Ok, este documento fue recibido');
            else return array("2",'Ha ocurrido un error, porfavor intentelo nuevamente'); 
        
            /*$this->db->commit();            
            return array('1','Bien!',$id);

        }
            catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } */
    }
}
?>