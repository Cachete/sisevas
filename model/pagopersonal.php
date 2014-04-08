<?php
include_once("Main.php");

class PagoPersonal extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
        pa.idpagos,
        p.dni,
        p.nombres || ' ' || p.apellidos,
        pa.motivo,
        pa.importe,
        pa.nrorecibo,
        substr(cast(pa.fechacancelacion as text),9,2)||'/'||substr(cast(pa.fechacancelacion as text),6,2)||'/'||substr(cast(pa.fechacancelacion as text),1,4)

        FROM
        produccion.pagos AS pa
        INNER JOIN personal AS p ON p.idpersonal = pa.idpersonal ";                
        
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT
            p.idpagos,
            per.idpersonal,
            per.dni,
            per.nombres || ' ' || per.apellidos AS personal,
            p.nrorecibo,
            p.motivo,
            p.fechacancelacion,
            p.importe,
            p.horapago,
            p.pagomes,
            p.pagoanio

            FROM
            produccion.pagos AS p
            INNER JOIN personal AS per ON per.idpersonal = p.idpersonal

            WHERE p.idpagos = :id ");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $stmt = $this->db->prepare("INSERT INTO produccion.pagos(
            nrorecibo, fechacancelacion, importe, pagomes, pagoanio, 
            idpersonal, horapago, motivo) 
                    VALUES(:p1,:p2,:p3,:p4,:p5, :p6, :p7, :p8)");
        $stmt->bindParam(':p1', $_P['nrorecibo'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['fechacancelacion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['montopag'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['mes'] , PDO::PARAM_STR);
        $stmt->bindParam(':p5', $_P['anio'] , PDO::PARAM_STR);
        $stmt->bindParam(':p6', $_P['idpersonal'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['horapago'] , PDO::PARAM_STR);
        $stmt->bindParam(':p8', $_P['motivopago'] , PDO::PARAM_STR);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
         
    }

    function update($_P ) 
    {
        $sql = "UPDATE produccion.pagos
                   SET  nrorecibo= :p1,
                        importe= :p3, pagomes= :p4, 
                       pagoanio= :p5, idpersonal= :p6, 
                       motivo= :p8
                 WHERE idpagos = :idpagos";
        $stmt = $this->db->prepare($sql);
               
        $stmt->bindParam(':p1', $_P['nrorecibo'] , PDO::PARAM_STR);
        //$stmt->bindParam(':p2', $_P['fechacancelacion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['montopag'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['mes'] , PDO::PARAM_STR);
        $stmt->bindParam(':p5', $_P['anio'] , PDO::PARAM_STR);
        $stmt->bindParam(':p6', $_P['idpersonal'] , PDO::PARAM_INT);
        //$stmt->bindParam(':p7', $_P['horapago'] , PDO::PARAM_STR);
        $stmt->bindParam(':p8', $_P['motivopago'] , PDO::PARAM_STR);
        $stmt->bindParam(':idpagos', $_P['idpagos'] , PDO::PARAM_INT);
           
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM seguridad.modulo WHERE idmodulo = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function ViewCuotas($id)
    {
        $stmt = $this->db->prepare("SELECT
            mc.monto,
            substr(cast(mc.fechapago as text),9,2)||'/'||substr(cast(mc.fechapago as text),6,2)||'/'||substr(cast(mc.fechapago as text),1,4) AS fechapago,
            mc.monto_saldado
            FROM
            facturacion.movimiento AS m
            INNER JOIN facturacion.movimientocuotas AS mc ON m.idmovimiento = mc.idmovimiento

            WHERE mc.idmovimiento = :id
            ORDER BY mc.idmovimientocuota ");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //Reporte
    function ViewResultado($_G)
    {
        $idpersonal =$_G['idper'];
        $fechai = $this->fdate($_G['fechai'], 'EN');
        $fechaf = $this->fdate($_G['fechaf'], 'EN');
        
        if($idpersonal==0)
        {   
            $sql="SELECT
              m.idmovimiento,
              c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno AS nomcliente,
              tpd.descripcion AS tipodoc ,
              m.documentonumero,
              tpp.descripcion AS tipopag,
              substr(cast(m.fecha as text),9,2)||'/'||substr(cast(m.fecha as text),6,2)||'/'||substr(cast(m.fecha as text),1,4) AS fechareg,
              m.total 
                 
              FROM
              facturacion.movimiento AS m
              INNER JOIN facturacion.movimientodetalle AS md ON m.idmovimiento = md.idmovimiento
              INNER JOIN cliente AS c ON c.idcliente = m.idcliente
              INNER JOIN facturacion.tipodocumento AS tpd ON tpd.idtipodocumento = m.idtipodocumento
              INNER JOIN produccion.tipopago AS tpp ON tpp.idtipopago = m.idtipopago

              WHERE
              m.fecha BETWEEN CAST(:p1 AS DATE) AND CAST(:p2 AS DATE) 
              ORDER BY
              m.idmovimiento ASC ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':p1', $fechai , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $fechaf, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetchAll();

        }else
            {   
                $sql="SELECT
                  m.idmovimiento,
                  c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno AS nomcliente,
                  tpd.descripcion AS tipodoc ,
                  m.documentonumero,
                  tpp.descripcion AS tipopag,
                  substr(cast(m.fecha as text),9,2)||'/'||substr(cast(m.fecha as text),6,2)||'/'||substr(cast(m.fecha as text),1,4) AS fechareg,
                  m.total 
                     
                  FROM
                  facturacion.movimiento AS m
                  INNER JOIN facturacion.movimientodetalle AS md ON m.idmovimiento = md.idmovimiento
                  INNER JOIN cliente AS c ON c.idcliente = m.idcliente
                  INNER JOIN facturacion.tipodocumento AS tpd ON tpd.idtipodocumento = m.idtipodocumento
                  INNER JOIN produccion.tipopago AS tpp ON tpp.idtipopago = m.idtipopago

                  WHERE
                  m.fecha BETWEEN CAST(:p1 AS DATE) AND CAST(:p2 AS DATE) AND
                  m.idusuarioreg= :id
                  ORDER BY
                  m.idmovimiento ASC ";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $idpersonal , PDO::PARAM_INT);
                $stmt->bindParam(':p1', $fechai , PDO::PARAM_STR);
                $stmt->bindParam(':p2', $fechaf, PDO::PARAM_STR);

                $stmt->execute();
                return $stmt->fetchAll();
            }
        

    }
    
    // VER EL DETALLE DEL REPORTE
    function rptDetails($id)
    {
        $stmt = $this->db->prepare("SELECT
          sp.descripcion || ' ' || p.descripcion AS producto,
          md.precio,
          md.cantidad,
          md.precio * md.cantidad AS importe

          FROM
          facturacion.movimiento AS m
          INNER JOIN facturacion.movimientodetalle AS md ON m.idmovimiento = md.idmovimiento
          INNER JOIN produccion.subproductos_semi AS p ON p.idsubproductos_semi = md.idproducto
          INNER JOIN produccion.productos_semi AS sp ON sp.idproductos_semi = p.idproductos_semi

          WHERE md.idmovimiento = :id

          ORDER BY
          md.idmovimiento ASC ");

        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }




}
?>