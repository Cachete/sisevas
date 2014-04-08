<?php
include_once("Main.php");
class movimiento extends Main
{   
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT  m.idmovimiento,
                        m.fecha,
                        upper(m.referencia),
                        td.abreviado,
                        m.serie,
                        m.numero,                        
                        upper(p.razonsocial),
                        p.ruc,
                        case m.afecto when 1 then '18%' else '' end,                        
                        cast(m.afecto*t.total*m.igv/100+t.total as numeric(18,2)) as total,
                        case m.estado when 1 then 'Activo'
                                      when 2 then 'Anulado'
                             else ''
                        end,
                        case m.estado when 1 then
                           case m.usuarioreg when '".$_SESSION['idusuario']."' then
                           '<a class=\"anular box-boton boton-anular\" id=\"v-'||m.idmovimiento||'\" href=\"#\" title=\"Anular\" ></a>'
                           else
                                case ".$_SESSION['id_perfil']." when 1 then
                                '<a class=\"anular box-boton boton-anular\" id=\"v-'||m.idmovimiento||'\" href=\"#\" title=\"Anular\" ></a>'
                                else '&nbsp;'
                                end
                           end
                        else '&nbsp;'
                        end
                    FROM movimientos as m 
                         inner join movimientosubtipo as mst on mst.idmovimientosubtipo = m.idmovimientosubtipo
                         inner join movimientostipo as mt on mt.idmovimientostipo = mst.idmovimientostipo
                         inner join facturacion.tipodocumento as td on td.idtipodocumento = m.idtipodocumento
                         inner join proveedor as p on p.idproveedor = m.idproveedor
                         inner join (select idmovimiento,sum(precio*ctotal) as total
                                    from movimientosdetalle
                                    group by idmovimiento) as t on t.idmovimiento = m.idmovimiento
                    WHERE mt.idmovimientostipo = 1 and autogen = 0 ";
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT m.*,p.ruc,p.razonsocial
                                    FROM movimientos as m
                                        inner join proveedor as p on p.idproveedor = m.idproveedor
                                    WHERE idmovimiento = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT mv.* ,
                                            case mv.idtipoproducto when 1 then p.descripcion
                                                        when 2 then l.descripcion||', '||ma.descripcion||' '||ma.espesor||' - '||p.medidas
                                            else ''
                                            end as descripcion
                                        FROM movimientosdetalle as mv inner join produccion.producto as p on p.idproducto = mv.idproducto
                                            inner join produccion.maderba as ma on ma.idmaderba = p.idmaderba
                                            inner join produccion.linea as l on l.idlinea = ma.idlinea
                                        WHERE idmovimiento = :id    
                                        order by item ");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function insert($_P) 
    {
        
        $idmovimientosubtipo = $_P['idmovimientosubtipo'];        
        $idmoneda = 1; //Soles
        $fecha = date('Y-m-d');
        $referencia = $_P['referencia'];
        $estado = 1;
        $idsucursal = $_SESSION['idsucursal'];
        $usuarioreg = $_SESSION['idusuario'];
        $idtipodocumento = $_P['idtipodocumento'];
        if($idtipodocumento=="") $idtipodocumento=7; //No definido
        $serie = $_P['serie'];
        $numero = $_P['numero'];
        $fechae =  $this->fdate($_P['fechae'],'EN');
        $idproveedor = $_P['idproveedor'];
        if($idproveedor=="") $idproveedor = 1;
        $idformapago = $_P['idformapago'];
        $guia_serie = $_P['guia_serie'];
        $guia_numero = $_P['guia_numero'];
        $fecha_guia =  $this->fdate($_P['fecha_guia'],'EN');
        if(isset($_P['afecto'])) $afecto = 1;
            else $afecto=0;
        $idalmacen = $_P['idalmacen'];
        $igv = $_P['igv_val'];
        $autogen = $_P['autogen'];
        if($autogen=="") $autogen=0;
        $stmt = $this->db->prepare("SELECT idmovimientostipo from movimientosubtipo
                                    where idmovimientosubtipo = :id");
        $stmt->bindParam(':id',$idmovimientosubtipo,PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetchObject();
        $idmovimientostipo = $r->idmovimientostipo;

        $sql = "INSERT INTO movimientos(idmovimientosubtipo, idmoneda, fecha, referencia, 
                                        estado, idsucursal, usuarioreg, idtipodocumento, serie, numero, 
                                        fechae, idproveedor, idformapago, guia_serie, guia_numero, 
                                        fecha_guia, afecto, idalmacen, igv,autogen) 
                            values(:p1, :p2, :p3, :p4, 
                                        :p5, :p6, :p7, :p8, :p9, :p10, 
                                        :p11, :p12, :p13, :p14, :p15, 
                                        :p16, :p17, :p18, :p19, :p20)";
        $stmt = $this->db->prepare($sql);
        try 
        { 
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

                $stmt->bindParam(':p1',$idmovimientosubtipo,PDO::PARAM_INT);
                $stmt->bindParam(':p2',$idmoneda,PDO::PARAM_INT);
                $stmt->bindParam(':p3',$fecha,PDO::PARAM_STR);
                $stmt->bindParam(':p4',$referencia,PDO::PARAM_STR);
                $stmt->bindParam(':p5',$estado,PDO::PARAM_INT);
                $stmt->bindParam(':p6',$idsucursal,PDO::PARAM_INT);
                $stmt->bindParam(':p7',$usuarioreg,PDO::PARAM_STR);
                $stmt->bindParam(':p8',$idtipodocumento,PDO::PARAM_INT);
                $stmt->bindParam(':p9',$serie,PDO::PARAM_STR);
                $stmt->bindParam(':p10',$numero,PDO::PARAM_STR);
                $stmt->bindParam(':p11',$fechae,PDO::PARAM_STR);
                $stmt->bindParam(':p12',$idproveedor,PDO::PARAM_INT);
                $stmt->bindParam(':p13',$idformapago,PDO::PARAM_INT);
                $stmt->bindParam(':p14',$guia_serie,PDO::PARAM_STR);
                $stmt->bindParam(':p15',$guia_numero,PDO::PARAM_STR);
                $stmt->bindParam(':p16',$fecha_guia,PDO::PARAM_STR);
                $stmt->bindParam(':p17',$afecto,PDO::PARAM_INT);
                $stmt->bindParam(':p18',$idalmacen,PDO::PARAM_INT);
                $stmt->bindParam(':p19',$igv,PDO::PARAM_INT);
                $stmt->bindParam(':p20',$autogen,PDO::PARAM_INT);

                $stmt->execute();
                $id =  $this->IdlastInsert('movimientos','idmovimiento');
                $row = $stmt->fetchAll();

                $stmt2  = $this->db->prepare('INSERT INTO movimientosdetalle(
                                                            idmovimiento, idalmacen, item, idproducto,
                                                             idtipoproducto, cantidad, precio, estado, 
                                                             largo,alto,espesor,ctotal,ctotal_current) 
                                                values(:p1, :p2, :p3, :p4, :p5, :p6, 
                                                       :p7, :p8, :p9,:p10,:p11,:p12,:p13);');                
                
                if($idmovimientostipo==1)
                    $stmt3 = $this->db->prepare('UPDATE produccion.producto 
                                                    set stock = stock + :cant
                                                 WHERE idproducto = :idp');
                else
                    $stmt3 = $this->db->prepare('UPDATE produccion.producto 
                                                    set stock = stock - :cant
                                                 WHERE idproducto = :idp');
          
                $sql = "SELECT t.idm,t.c
                        from (
                        SELECT max(idmovimiento) as idm ,ctotal_current as c, item
                        FROM movimientosdetalle
                        where idtipoproducto = :idtp and idalmacen = :ida and idproducto = :idp
                        group by ctotal_current,item,idmovimiento
                        order by idmovimiento desc
                        limit 1) as t
                        order by t.item desc";

                $stmt4 = $this->db->prepare($sql);

                $estado = 1;
                $item = 1;

                foreach($_P['idtipod'] as $i => $idproducto)
                {
                    $largo = 0;
                    if($_P['largod'][$i]!="") $largo = $_P['largod'][$i];
                    $alto = 0;
                    if($_P['altod'][$i]!="") $alto = $_P['altod'][$i];
                    $espesor = 0;
                    if($_P['espesord'][$i]!="") $espesor = $_P['espesord'][$i];
                    if($_P['tipod'][$i]==1) $too = $_P['cantd'][$i]*$largo*$alto*$espesor/12;
                        else $too = $_P['cantd'][$i];

                    $idtipod = $_P['tipod'][$i];

                    if($idtipod==1)
                    {
                        //Si es madera, y el movimiento se hizo atraves de
                        //una produccion, entonses los valores de largo,
                        //alto y espesor posiblemente sean cero "0".                        
                        if($too==0) $too = $_P['ctotal'][$i];
                    }

                    $stmt4->bindParam(':idtp',$idtipod,PDO::PARAM_INT);
                    $stmt4->bindParam(':ida',$idalmacen,PDO::PARAM_INT);
                    $stmt4->bindParam(':idp',$idproducto,PDO::PARAM_INT);
                    $stmt4->execute();
                    $row4 = $stmt4->fetchObject();

                    if($idmovimientostipo==1)
                        $too_current = (float)$row4->c + $too;
                    else 
                        $too_current = (float)$row4->c - $too;

                    $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$idalmacen,PDO::PARAM_INT);
                    $stmt2->bindParam(':p3',$item,PDO::PARAM_INT);
                    $stmt2->bindParam(':p4',$idproducto,PDO::PARAM_INT);
                    $stmt2->bindParam(':p5',$_P['tipod'][$i],PDO::PARAM_INT);
                    $stmt2->bindParam(':p6',$_P['cantd'][$i],PDO::PARAM_INT);
                    $stmt2->bindParam(':p7',$_P['preciod'][$i],PDO::PARAM_INT);
                    $stmt2->bindParam(':p8',$estado,PDO::PARAM_INT);
                    $stmt2->bindParam(':p9',$largo,PDO::PARAM_INT);
                    $stmt2->bindParam(':p10',$alto,PDO::PARAM_INT);
                    $stmt2->bindParam(':p11',$espesor,PDO::PARAM_INT);
                    $stmt2->bindParam(':p12',$too,PDO::PARAM_INT);
                    $stmt2->bindParam(':p13',$too_current,PDO::PARAM_INT);
                    $stmt2->execute();
                    $item += 1;
                    
                    $stmt3->bindParam(':cant',$too,PDO::PARAM_INT);                    
                    $stmt3->bindParam(':idp',$idproducto,PDO::PARAM_INT);
                    $stmt3->execute();
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
    
    function delete($p) 
    {
        //Obtenemos los datos de la cabecera del movimiento
        $stmt = $this->db->prepare("SELECT m.*,mst.idmovimientostipo 
                                    FROM movimientos as m
                                        inner join movimientosubtipo as mst 
                                        on mst.idmovimientosubtipo = m.idmovimientosubtipo                                                                                
                                    where idmovimiento = :id");
        $stmt->bindParam(':id',$p,PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetchObject();
        //Seteamos los valores
        $_P = array();        
        $_P['idmovimientosubtipo'] = $r->idmovimientosubtipo;        
        if($r->idmovimientostipo==1) $_P['idmovimientosubtipo']=18;
            else $_P['idmovimientosubtipo']=5;        
        $_P['referencia'] = "POR ANULACION DEL MOVIMIENTO ".$r->idmovimiento;        
        $_P['idtipodocumento'] = 7; //No definido
        $_P['serie'] = '';
        $_P['numero'] = '';
        $_P['fechae'] = date('Y-m-d');
        $_P['idproveedor'] = 1;        
        $_P['idformapago'] = $r->idformapago;
        $_P['guia_serie'] = '';
        $_P['guia_numero'] = '';
        $_P['fecha_guia'] = date('Y-m-d');
        $_P['afecto'] = 0;
        $_P['idalmacen'] = $r->idalmacen;
        $_P['igv_val'] = 0;
        $_P['autogen'] = 1;

        //Obtenemos los datos del detalle del movimiento
        $stmt = $this->db->prepare("SELECT * from movimientosdetalle where idmovimiento = :id");
        $stmt->bindParam(':id',$p,PDO::PARAM_INT);
        $stmt->execute();
        $tipod = array(); //Tipo de producto 1 -> madera 2 -> melamina
        $idtipod = array();$largod = array();$altod = array();
        $espesord = array();$cantd = array();$preciod = array();
        foreach($stmt->fetchAll() as $r)
        {
            $_P['tipod'][] = $r['idtipoproducto'];
            $_P['idtipod'][] = $r['idproducto'];
            $_P['largod'][] = $r['largo'];
            $_P['altod'][] = $r['alto'];
            $_P['espesord'][] = $r['espesor'];
            $_P['cantd'][] = $r['cantidad'];
            $_P['preciod'][] = $r['precio'];
            $_P['itemd'][] = $r['item'];
            $_P['ctotal'][] = $r['ctotal'];
        }

        $stmt = $this->db->prepare("UPDATE movimientos set estado = 2 
                                    WHERE idmovimiento = :p1 and estado = 1");            
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();

        if($p1)        
            $resp = $this->insert($_P);        
        else 
            $resp = array("2","Orror");
        return $resp;
    }
    
    function test()
    {
        return 1;
    }

    function ViewResultado($_G)
    {
       
        $fechai = $this->fdate($_G['fechai'], 'EN');
        $fechaf = $this->fdate($_G['fechaf'], 'EN');

        $sql="SELECT  
            m.idmovimiento,
            m.fecha,
            upper(m.referencia) AS referencia,
            td.abreviado,
            m.serie,
            m.numero,                        
            upper(p.razonsocial) AS proveedor,
            p.ruc,  
            cast(t.total*m.igv/100+t.total as numeric(18,2)) as total,
            case m.estado when 1 then 'Activo'
                      when 2 then 'Anulado'
                 else ''
            end AS estado
            FROM movimientos as m 
             inner join movimientosubtipo as mst on mst.idmovimientosubtipo = m.idmovimientosubtipo
             inner join movimientostipo as mt on mt.idmovimientostipo = mst.idmovimientostipo
             inner join facturacion.tipodocumento as td on td.idtipodocumento = m.idtipodocumento
             inner join proveedor as p on p.idproveedor = m.idproveedor
             inner join (select idmovimiento,sum(precio*cantidad) as total
                    from movimientosdetalle
                    group by idmovimiento) as t on t.idmovimiento = m.idmovimiento
            WHERE mt.idmovimientostipo = 1 and autogen = 0 AND
                m.fecha BETWEEN CAST(:p1 AS DATE) AND CAST(:p2 AS DATE)
            ORDER BY m.idmovimiento ASC ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':p1', $fechai , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $fechaf, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll();

    }

    //
    function rptDetails($id)
    {
        $stmt = $this->db->prepare("SELECT mv.* ,
                        case mv.idtipoproducto when 1 then p.descripcion
                                    when 2 then l.descripcion||', '||ma.descripcion||' '||ma.espesor||' - '||p.medidas
                        else ''
                        end as descripcion
                    FROM movimientosdetalle as mv inner join produccion.producto as p on p.idproducto = mv.idproducto
                        inner join produccion.maderba as ma on ma.idmaderba = p.idmaderba
                        inner join produccion.linea as l on l.idlinea = ma.idlinea
                    WHERE idmovimiento = :id    
                    order by item ");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }




}

?>