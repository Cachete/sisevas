<?php
include_once("Main.php");
include_once("tipodocumento.php");
include_once("produccion.php");
class Ventas extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            m.idmovimiento,
            c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno,
            tpd.descripcion ,
            m.documentonumero,
            tpp.descripcion,
            substr(cast(m.fecha as text),9,2)||'/'||substr(cast(m.fecha as text),6,2)||'/'||substr(cast(m.fecha as text),1,4),
            m.total,    
            case m.estado when 1 then 'PAGADO'
                          WHEN 2 then 'CON DEUDA'
                          WHEN 3 then 'ANULADO'
                  END,
            case when m.idtipopago=2 then
                '<a class=\"pagar box-boton boton-pay\" id=\"v-'||m.idmovimiento||'\" title=\"Pagar sus cuotas\" ></a>'
            else '&nbsp;' end,
            case m.estado when 3 then '' 
                 else '<a href=\"javascript:\" class=\"anular box-boton boton-anular\" id=\"v-'||m.idmovimiento||'\" title=\"Anular Venta\" ></a>'
            end
            FROM
            facturacion.movimiento AS m
            INNER JOIN facturacion.movimientodetalle AS md ON m.idmovimiento = md.idmovimiento
            INNER JOIN cliente AS c ON c.idcliente = m.idcliente
            INNER JOIN facturacion.tipodocumento AS tpd ON tpd.idtipodocumento = m.idtipodocumento
            INNER JOIN produccion.tipopago AS tpp ON tpp.idtipopago = m.idtipopago 
            inner join produccion.almacenes as a on a.idalmacen = m.idalmacen 
            WHERE a.idsucursal = ".$_SESSION['idsucursal'];                
        //echo $sql;
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT
                                        m.idmovimiento,
                                        m.fecha,
                                        m.idtipodocumento,
                                        m.documentoserie,
                                        m.documentonumero,
                                        m.documentofecha,
                                        m.idcliente,
                                        m.idmoneda,
                                        m.tipocambio,
                                        m.subtotal,
                                        m.porcentajeigv,
                                        m.total,
                                        m.pagoestado,
                                        m.pagofecha,
                                        m.estado,
                                        m.idusuarioreg,
                                        m.fechareg,
                                        m.idusuarioanu,
                                        m.fechaanu,
                                        m.obs,
                                        m.igv,
                                        m.motivoanulacion,
                                        m.tipodescuento,
                                        m.idtipopago,
                                        m.idalmacen,
                                        m.descuento,
                                        c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno AS nomcliente,
                                        c.dni,
                                        c.direccion,
                                        p.nombres||' '||p.apellidos as u,
                                        td.descripcion as tidodoc,
                                        m.afectoigv
                                        FROM
                                        facturacion.movimiento AS m
                                        INNER JOIN cliente AS c ON c.idcliente = m.idcliente
                                        inner join personal as p on p.idpersonal = m.idusuarioreg
                                        inner join facturacion.tipodocumento as td on td.idtipodocumento = m.idtipodocumento
                                        WHERE idmovimiento = :id ");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT
            md.item,
            p.descripcion,
            md.idproducto,
            md.precio,
            md.cantidad,
            md.precio * md.cantidad AS importe
            FROM
            facturacion.movimiento AS m
            INNER JOIN facturacion.movimientodetalle AS md ON m.idmovimiento = md.idmovimiento
            INNER JOIN produccion.subproductos_semi AS p ON p.idsubproductos_semi = md.idproducto

            WHERE md.idmovimiento = :id    
            ORDER BY md.idmovimiento ");

        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll() as $r)
        {
          $data[] = array('idproducto'=>$r['idproducto'],
                          'descripcion'=>$r['descripcion'],
                          'precio'=>$r['precio'],
                          'cantidad'=>$r['cantidad']
                          );
        }
        return $data;
    }

    function insert($_P ) 
    {
        
         $obj_td = new Tipodocumento();
         $obj_produccion = new Produccion();
      
         $prod = json_decode($_P['producto']);         
         $item = $prod->nitem;
         $cont = 0;

         if(isset($_P['aigv'])) $afecto = 1;
            else $afecto=0;

         $st = 0;
         $igv = $_P['igv_val'];
         $tigv = 0;
         $t = 0;
         $dsct = $_P['monto_descuento'];
         $tdsct = $_P['tipod'];         
        
           for($i=0;$i<$item;$i++)
           {
                if($prod->estado[$i])
                {
                    $cont ++;
                    $st += $prod->cantidad[$i]*$prod->precio[$i];
                }
           }

           $st_bruto = $st;

           if($tdsct==1) $dsct_val = $dsct;
            else $dsct_val = $st*$dsct/100;

           $st = $st - $dsct_val;

           if($afecto==1)
           {
              $tigv = $st*$igv/100;
              $t = $st+$tigv;
           }
           else
            {
              $tigv = 0;
              $t = $st+$tigv;
            } 

         $idmoneda = 1; //Soles
         $tipocambio=0;
         $fecha = date('Y-m-d');         
         $fehcaemision = $this->fdate($_P['fechaemision'],'EN');
         
         $idsucursal = $_SESSION['idsucursal'];
         $usuarioreg = $_SESSION['idusuario'];
         $idtipopago = $_P['idtipopago'];         
         $idcliente = $_P['idcliente'];
         $serie = '';
         $numero = '';
         $idtipodocumento = 7; //No declara    
         $idalmacen = $_P['idalmacen'];    

         if($idtipopago==1) $estado = 1;
          else $estado = 2;

         $sql = "INSERT INTO facturacion.movimiento(
                        fecha, idtipodocumento, documentoserie, 
                        documentonumero, documentofecha, idcliente, idmoneda, tipocambio, 
                        subtotal, porcentajeigv, total, pagoestado,  estado, 
                        idusuarioreg, fechareg, obs, igv,  
                        tipodescuento, idtipopago,idalmacen,descuento)
                VALUES (:p2, :p3, :p4, 
                        :p5, :p6, :p7, :p8, :p9, 
                        :p10, :p11, :p12, :p13,:p15, 
                        :p16, :p17, :p21, :p22, 
                        :p23, :p24,:p25,:p26); ";
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt = $this->db->prepare($sql);            
            $stmt->bindParam(':p2',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':p3',$idtipodocumento,PDO::PARAM_INT);
            $stmt->bindParam(':p4',$serie,PDO::PARAM_STR);
            $stmt->bindParam(':p5',$numero,PDO::PARAM_STR);
            $stmt->bindParam(':p6',$fehcaemision,PDO::PARAM_STR);
            $stmt->bindParam(':p7',$idcliente,PDO::PARAM_INT);
            $stmt->bindParam(':p8',$idmoneda,PDO::PARAM_INT);
            $stmt->bindParam(':p9',$tipocambio,PDO::PARAM_INT);
            $stmt->bindParam(':p10',$st,PDO::PARAM_INT);
            $stmt->bindParam(':p11',$igv,PDO::PARAM_INT);
            $stmt->bindParam(':p12',$t,PDO::PARAM_INT);
            $stmt->bindParam(':p13',$estado,PDO::PARAM_INT);
            
            $stmt->bindParam(':p15',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':p16',$usuarioreg,PDO::PARAM_INT);
            $stmt->bindParam(':p17',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':p21',$_P['observacion'],PDO::PARAM_STR);
            $stmt->bindParam(':p22',$tigv,PDO::PARAM_INT);
            
            
            $stmt->bindParam(':p23',$tdsct,PDO::PARAM_INT);
            $stmt->bindParam(':p24',$_P['idtipopago'],PDO::PARAM_INT);
            $stmt->bindParam(':p25',$idalmacen,PDO::PARAM_INT);
            $stmt->bindParam(':p26',$dsct,PDO::PARAM_INT);

            $stmt->execute();
            $id =  $this->IdlastInsert('facturacion.movimiento','idmovimiento');

            
            //Afectamos los stock de los producctos a vender
            //(Asginamos los valores para enviar)
            $_Pp = array();
            $_Pp['fechai'] = date('Y-m-d');
            $_Pp['fechaf'] = date('Y-m-d');;
            $_Pp['idpersonal'] = $usuarioreg;
            $_Pp['idalmacen'] = $idalmacen;
            $_Pp['idalmacen'] = $idalmacen;
            $_Pp['idproducciontipo'] = 3;
            $_Pp['prod'] = array();
            $_Pp['prod']['item'] = $cont;
            $_Pp['idreferencia'] = $id;

            $_Pp['prod']['idsps']=array();
            $_Pp['prod']['cantidad']=array();
            $_Pp['prod']['estado']=array();

            for($i=0;$i<$item;$i++)
             {
                  if($prod->estado[$i])
                  {
                      $_Pp['prod']['idsps'][] = $prod->idproducto[$i];
                      $_Pp['prod']['cantidad'][] = $prod->cantidad[$i];
                      $_Pp['prod']['estado'][] = true;
                  }
             }
            $obj_produccion->InsertProduccion($_Pp);

            //Insertamos los detalles de la venta
            $detalle = $this->db->prepare("INSERT INTO facturacion.movimientodetalle(
                                            idmovimiento, idproducto, item, cantidad, precio)
                                            VALUES (:p1, :p2, :p3, :p4, :p5);");
            $itm = 0;
            for($i=0;$i<$item;$i++)
            {
                if($prod->estado[$i])
                {
                    $itm += 1;
                    $detalle->bindParam(':p1',$id,PDO::PARAM_INT);
                    $detalle->bindParam(':p2',$prod->idproducto[$i],PDO::PARAM_INT);
                    $detalle->bindParam(':p3',$itm,PDO::PARAM_INT);
                    $detalle->bindParam(':p4',$prod->cantidad[$i],PDO::PARAM_INT);
                    $detalle->bindParam(':p5',$prod->precio[$i],PDO::PARAM_INT);
                    $detalle->execute();
                }
            }
            //Cuotas
            if($idtipopago==2)
            {
                $estado = 0;
                $observacion = "";
                $cuotas = $this->db->prepare("INSERT INTO facturacion.movimientocuotas(
                                                      idmovimiento, monto, monto_saldado, fechareg, 
                                                      fechapago, estado, observacion, tipo)
                                              VALUES (:p1, :p2, 0, :p4, 
                                                      :p5, :p6, :p7, :p8);");                
                //Inicial
                $tipo = 1;
                if($_P['inicial']>0)
                {
                   $cuotas->bindParam(':p1',$id,PDO::PARAM_INT);
                   $cuotas->bindParam(':p2',$_P['inicial'],PDO::PARAM_INT);
                   //$cuotas->bindParam(':p3','',PDO::PARAM_INT);
                   $cuotas->bindParam(':p4',$fecha,PDO::PARAM_STR);
                   $cuotas->bindParam(':p5',$fecha,PDO::PARAM_STR);
                   $cuotas->bindParam(':p6',$estado,PDO::PARAM_INT);
                   $cuotas->bindParam(':p7',$observacion,PDO::PARAM_STR);
                   $cuotas->bindParam(':p8',$tipo,PDO::PARAM_INT);
                   $cuotas->execute();
                }

                $tipo = 2;
                foreach($_P['totalcouta'] as $k => $v)   
                {
                   
                   $cuotas->bindParam(':p1',$id,PDO::PARAM_INT);
                   $cuotas->bindParam(':p2',$v,PDO::PARAM_INT);
                   //$cuotas->bindParam(':p3','',PDO::PARAM_INT);
                   $cuotas->bindParam(':p4',$fecha,PDO::PARAM_STR);
                   $cuotas->bindParam(':p5',$_P['fechacuota'][$k],PDO::PARAM_STR);
                   $cuotas->bindParam(':p6',$estado,PDO::PARAM_INT);
                   $cuotas->bindParam(':p7',$observacion,PDO::PARAM_STR);
                   $cuotas->bindParam(':p8',$tipo,PDO::PARAM_INT);
                   $cuotas->execute();
                }
            }

            //Pagos
            $pagos = json_decode($_P['pagos']);         
            $item = $pagos->nitem;
            $total_pago = 0;
            for($i=0;$i<$item;$i++)
            {
                if($pagos->estado[$i])
                {
                    $total_pago += $pagos->monto[$i];
                }
            }

            $descuento = 0;
            $observacion_pago = "";
            if($idtipopago==1)
              $idtipodocumento = $_P['idtipodocumento'];
            else 
              $idtipodocumento = 6; //Recibo de ingreso
            
            $comprobante = $obj_td->GCorrelativo($idtipodocumento);
            $obj_td->UpdateCorrelativo($idtipodocumento);

            $pago = $this->db->prepare("INSERT INTO facturacion.movimientospago(
                                    idmovimiento, fecha, total, descuento, observacion, 
                                    idtipodocumento, serie, numero)
                                    VALUES (:p1, :p2, :p3, :p4, :p5, 
                                            :p6, :p7, :p8)");
            $pago->bindParam(':p1',$id,PDO::PARAM_INT);
            $pago->bindParam(':p2',$fecha,PDO::PARAM_STR);
            $pago->bindParam(':p3',$total_pago,PDO::PARAM_INT);
            $pago->bindParam(':p4',$descuento,PDO::PARAM_INT);
            $pago->bindParam(':p5',$observacion_pago,PDO::PARAM_STR);
            $pago->bindParam(':p6',$idtipodocumento,PDO::PARAM_INT);
            $pago->bindParam(':p7',$comprobante['serie'],PDO::PARAM_STR);
            $pago->bindParam(':p8',$comprobante['numero'],PDO::PARAM_STR);
            $pago->execute();

            $idp =  $this->IdlastInsert('facturacion.movimientospago','idmovimientopago');

            //
            if($idtipopago==1)
            {
              //Actualizamos las series y numero del documento generado              
              $updt_mov = $this->db->prepare("UPDATE facturacion.movimiento set documentoserie = :serie, 
                                                            documentonumero = :numero,
                                                            idtipodocumento = :idtd
                                  where idmovimiento = :id");
              $updt_mov->bindParam(':serie',$comprobante['serie'],PDO::PARAM_STR);
              $updt_mov->bindParam(':numero',$comprobante['numero'],PDO::PARAM_STR);
              $updt_mov->bindParam(':idtd',$idtipodocumento,PDO::PARAM_INT);
              $updt_mov->bindParam(':id',$id,PDO::PARAM_INT);
              $updt_mov->execute();
            }

            $pagodetalle = $this->db->prepare("INSERT INTO facturacion.mov_pagodetalle(
                                                idmovimientopago, idformapago, monto, nrotarjeta, nrocheque, 
                                                bancocheque, fechavcheque, observacion, estado,nrovoucher)
                                        VALUES (:p1, :p2, :p3, :p4, :p5, 
                                                :p6, :p7, :p8, :p9,:p10);");
            $observacion='';
            $estado = 1;
            for($i=0;$i<$item;$i++)
            {
                if($pagos->estado[$i])
                {
                    $f = $pagos->fechav[$i];
                    if(trim($f)=="")
                        $f = date('Y-m-d');
                    else 
                        $f = $this->fdate($f,'EN');

                    $pagodetalle->bindParam(':p1',$idp,PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p2',$pagos->idformapago[$i],PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p3',$pagos->monto[$i],PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p4',$pagos->nrotarjeta[$i],PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p5',$pagos->nrocheque[$i],PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p6',$pagos->banco[$i],PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p7',$f,PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p8',$observacion,PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p9',$estado,PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p10',$pagos->nrovoucher[$i],PDO::PARAM_STR);
                    $pagodetalle->execute();

                    if($idtipopago==2)
                    {
                       //Si es al credito se hace el pago de la inicial
                       $idpd =  $this->IdlastInsert('facturacion.mov_pagodetalle','idmovimientopago');

                       $monto_pago = $pagos->monto[$i];
                       do
                       {
                          $s = $this->db->prepare("SELECT * from facturacion.movimientocuotas 
                                                  where idmovimiento = {$id} and estado = 0 
                                                  order by idmovimientocuota asc limit 1");
                          $s->execute();
                          $row = $s->fetchObject();
                          $mont_p = $row->monto-$row->monto_saldado;
                          if($mont_p>$monto_pago)
                          {
                             $s1 = $this->db->prepare("UPDATE facturacion.movimientocuotas
                                                        SET monto_saldado = monto_saldado + {$monto_pago}
                                                       where idmovimientocuota = {$row->idmovimientocuota}");
                             $s1->execute();
                             $s2 = $this->db->prepare("INSERT INTO facturacion.mov_pagocuota 
                                                      values({$idpd},{$row->idmovimientocuota})");
                             $s2->execute();
                             $monto_pago = 0;
                          }
                          else
                          {                             
                             $fecha = date('Y-m-d');
                             $s1 = $this->db->prepare("UPDATE facturacion.movimientocuotas
                                                        SET monto_saldado = monto_saldado + {$mont_p},
                                                            estado = 1,
                                                            fechapagoe = '{$fecha}'
                                                       where idmovimientocuota = {$row->idmovimientocuota}");
                             $s1->execute();
                             $s2 = $this->db->prepare("INSERT INTO facturacion.mov_pagocuota 
                                                      values({$idpd},{$row->idmovimientocuota})");
                             $s2->execute();                            
                             $monto_pago = $monto_pago - $mont_p;
                          }

                       }
                       while($monto_pago>0);
                    }

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

    function pay_cuotas($_P)
    {
        $obj_td = new Tipodocumento();

        $id = $_P['id'];
        $pagos = json_decode($_P['pagos']);         
        $item = $pagos->nitem;
        $total_pago = 0;
        for($i=0;$i<$item;$i++)
        {
            if($pagos->estado[$i])
            {
                $total_pago += $pagos->monto[$i];
            }
        }

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $fecha = date('Y-m-d');
            $descuento = 0;
            $observacion_pago = "";
            $idtipopago=2; //Pago al credito          
            
            $idtipodocumento = 6; //Recibo de ingreso
            
            $comprobante = $obj_td->GCorrelativo($idtipodocumento);
            $obj_td->UpdateCorrelativo($idtipodocumento);

            $pago = $this->db->prepare("INSERT INTO facturacion.movimientospago(
                                    idmovimiento, fecha, total, descuento, observacion, 
                                    idtipodocumento, serie, numero)
                                    VALUES (:p1, :p2, :p3, :p4, :p5, 
                                            :p6, :p7, :p8)");
            $pago->bindParam(':p1',$id,PDO::PARAM_INT);
            $pago->bindParam(':p2',$fecha,PDO::PARAM_STR);
            $pago->bindParam(':p3',$total_pago,PDO::PARAM_INT);
            $pago->bindParam(':p4',$descuento,PDO::PARAM_INT);
            $pago->bindParam(':p5',$observacion_pago,PDO::PARAM_STR);
            $pago->bindParam(':p6',$idtipodocumento,PDO::PARAM_INT);
            $pago->bindParam(':p7',$comprobante['serie'],PDO::PARAM_STR);
            $pago->bindParam(':p8',$comprobante['numero'],PDO::PARAM_STR);
            $pago->execute();

            $idp =  $this->IdlastInsert('facturacion.movimientospago','idmovimientopago');

            $pagodetalle = $this->db->prepare("INSERT INTO facturacion.mov_pagodetalle(
                                                    idmovimientopago, idformapago, monto, nrotarjeta, nrocheque, 
                                                    bancocheque, fechavcheque, observacion, estado,nrovoucher)
                                            VALUES (:p1, :p2, :p3, :p4, :p5, 
                                                    :p6, :p7, :p8, :p9,:p10);");
            $observacion='';
            $estado = 1;
            for($i=0;$i<$item;$i++)
            {
                if($pagos->estado[$i])
                {
                    $f = $pagos->fechav[$i];
                    if(trim($f)=="")
                        $f = date('Y-m-d');
                    else 
                        $f = $this->fdate($f,'EN');

                    $pagodetalle->bindParam(':p1',$idp,PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p2',$pagos->idformapago[$i],PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p3',$pagos->monto[$i],PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p4',$pagos->nrotarjeta[$i],PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p5',$pagos->nrocheque[$i],PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p6',$pagos->banco[$i],PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p7',$f,PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p8',$observacion,PDO::PARAM_STR);
                    $pagodetalle->bindParam(':p9',$estado,PDO::PARAM_INT);
                    $pagodetalle->bindParam(':p10',$pagos->nrovoucher[$i],PDO::PARAM_STR);
                    $pagodetalle->execute();

                    if($idtipopago==2)
                    {
                       //Si es al credito se hace el pago de la inicial
                       $idpd =  $this->IdlastInsert('facturacion.mov_pagodetalle','idmovimientopago');

                       $monto_pago = $pagos->monto[$i];
                       do
                       {
                          $s = $this->db->prepare("SELECT * from facturacion.movimientocuotas 
                                                  where idmovimiento = {$id} and estado = 0 
                                                  order by idmovimientocuota asc limit 1");
                          $s->execute();
                          $n = $s->rowCount();
                          if($n>0)
                          {
                            //Esto es para verificar si aún existen cuotas pendientes de pago
                            $row = $s->fetchObject();
                            $mont_p = $row->monto-$row->monto_saldado;
                            if($mont_p>$monto_pago)
                            {
                               $s1 = $this->db->prepare("UPDATE facturacion.movimientocuotas
                                                          SET monto_saldado = monto_saldado + {$monto_pago}
                                                         where idmovimientocuota = {$row->idmovimientocuota}");
                               $s1->execute();
                               $s2 = $this->db->prepare("INSERT INTO facturacion.mov_pagocuota 
                                                        values({$idpd},{$row->idmovimientocuota})");
                               $s2->execute();
                               $monto_pago = 0;
                            }
                            else
                            {                             
                               $fecha = date('Y-m-d');
                               $s1 = $this->db->prepare("UPDATE facturacion.movimientocuotas
                                                          SET monto_saldado = monto_saldado + {$mont_p},
                                                              estado = 1,
                                                              fechapagoe = '{$fecha}'
                                                         where idmovimientocuota = {$row->idmovimientocuota}");
                               $s1->execute();
                               $s2 = $this->db->prepare("INSERT INTO facturacion.mov_pagocuota 
                                                        values({$idpd},{$row->idmovimientocuota})");
                               $s2->execute();                            
                               $monto_pago = $monto_pago - $mont_p;
                            }
                          }
                          else 
                          {
                             //Actualizo el estado del movimiento a pagado
                             $u = $this->db->prepare("UPDATE facturacion.movimiento 
                                                        set estado = 1
                                                      where idmovimiento = {$id}");
                             $u->execute();
                             $monto_pago=0;
                          }
                       }
                       while($monto_pago>0);
                    }

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

    function genDoc($_P)
    {
        $obj_td = new Tipodocumento();
        $idtipodocumento = (int)$_P['idd'];
        $idm = (int)$_P['id'];
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $comprobante = $obj_td->GCorrelativo($idtipodocumento);
            $obj_td->UpdateCorrelativo($idtipodocumento);

            $s = $this->db->prepare("UPDATE facturacion.movimiento 
                                          set idtipodocumento = :idtd,
                                              documentoserie = :serie,
                                              documentonumero = :numero
                                    where idmovimiento = :id");
            $s->bindParam(':idtd',$idtipodocumento,PDO::PARAM_INT);
            $s->bindParam(':serie',$comprobante['serie'],PDO::PARAM_STR);
            $s->bindParam(':numero',$comprobante['numero'],PDO::PARAM_STR);
            $s->bindParam(':id',$idm,PDO::PARAM_INT);
            $s->execute();

            $this->db->commit();
            return array('1','Bien!',$id);
        }
        catch(PDOException $e)
        {
            $this->db->rollBack();
            return array('2',$e->getMessage().$str,'');
        }
    }

    function verificarCuotas($id)
    {
      $s = $this->db->prepare("SELECT * from facturacion.movimientocuotas 
                                                  where idmovimiento = :i and estado = 0 
                                                  order by idmovimientocuota asc");
      $s->bindParam(':i',$id,PDO::PARAM_INT);
      $s->execute();
      $n = $s->rowCount();
      return $n;
    }
    
    function delete($p) 
    {  
      $obj_produccion = new Produccion();
      try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();        
            //Afectamos los stock de los producctos a vender
            //(Asginamos los valores para enviar)
            $s = $this->db->prepare("SELECT * from facturacion.movimiento 
                                      WHERE idmovimiento = :id");
            $s->bindParam(':id',$p,PDO::PARAM_INT);
            $s->execute();
            $r = $s->fetchObject();

            $_Pp = array();
            $_Pp['fechai'] = date('Y-m-d');
            $_Pp['fechaf'] = date('Y-m-d');
            $_Pp['idpersonal'] = $_SESSION['idusuario'];
            $_Pp['idalmacen'] = $r->idalmacen;
            $_Pp['idalmacen'] = $r->idalmacen;
            $_Pp['idproducciontipo'] = 6;            
            $_Pp['idreferencia'] = $r->idmovimiento;

            $sd = $this->db->prepare("SELECT * from facturacion.movimientodetalle
                                      where idmovimiento = :id");
            $sd->bindParam(':id',$p,PDO::PARAM_INT);
            $sd->execute();
            $cont = $sd->rowCount();
            $_Pp['prod'] = array();
            $_Pp['prod']['item'] = $cont;
            $_Pp['prod']['idsps']=array();
            $_Pp['prod']['cantidad']=array();
            $_Pp['prod']['estado']=array();
            foreach($sd->fetchAll() as $r)
            {
                $_Pp['prod']['idsps'][] = $r['idproducto'];
                $_Pp['prod']['cantidad'][] = $r['cantidad'];
                $_Pp['prod']['estado'][] = true;
            }            
            $obj_produccion->InsertProduccion($_Pp);

            $s = $this->db->prepare("UPDATE facturacion.movimiento set estado = 3 
                                    where idmovimiento = :id");
            $s->bindParam(':id',$p,PDO::PARAM_INT);
            $s->execute();

            $this->db->commit();
            return array('1','Bien!',$id);
        }
        catch(PDOException $e)
        {
            $this->db->rollBack();
            return array('2',$e->getMessage().$str,'');
        }
    }

    function ViewCuotas($id)
    {
        $stmt = $this->db->prepare("SELECT
            mc.monto,
            substr(cast(mc.fechapago as text),9,2)||'/'||substr(cast(mc.fechapago as text),6,2)||'/'||substr(cast(mc.fechapago as text),1,4) AS fechapago,
            mc.monto_saldado,
            mc.tipo
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
    function pagosEfectuados($id)
    {
        $stmt = $this->db->prepare("SELECT  
                                    m.idmovimientopago,
                                    m.fecha,
                                    td.descripcion as documento,
                                    m.serie,
                                    m.numero,
                                    m.total
                                  from facturacion.movimientospago as m
                                    inner join facturacion.mov_pagodetalle as pd 
                                    on m.idmovimientopago = pd.idmovimientopago
                                    inner join facturacion.tipodocumento as td on td.idtipodocumento = m.idtipodocumento
                                    WHERE m.idmovimiento = :idm");
        $stmt->bindParam(':idm',$id,PDO::PARAM_INT);
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll() as $r)
        {

          $stmt2 = $this->db->prepare("SELECT   fp.descripcion,
                                        case pd.idformapago when 1 then '' 
                                          when 4 then 'Nro Tarjeta: '||pd.nrotarjeta||', Nro Voucher: '||pd.nrovoucher
                                          when 5 then 'Nro Tarjeta: '||pd.nrotarjeta||', Nro Voucher: '||pd.nrovoucher
                                          when 6 then 'Nro Cheque: '||pd.nrocheque||', Banco: '||pd.bancocheque||', Fecha Venc. '||fechavcheque
                                        end as d,
                                        pd.monto
                                      from facturacion.mov_pagodetalle as pd 
                                        inner join formapago as fp on pd.idformapago = fp.idformapago
                                      where pd.idmovimientopago = ".$r['idmovimientopago']);
          $stmt2->execute();


          $data[] = array('fecha'=>$r['fecha'],
                          'documento'=>$r['documento'],
                          'serie'=>$r['serie'],
                          'numero'=>$r['numero'],
                          'total'=>$r['total'],
                          'detalle'=>$stmt2->fetchAll());
        }
        return $data;

    }

}

?>
