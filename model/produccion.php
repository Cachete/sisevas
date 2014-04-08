<?php
include_once("Main.php");
include_once("movimiento.php");

class Produccion extends Main
{    
    //indexGridi -> Grilla del index de ingresos.
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        //Estados 0->anulado, 1->registrado, 2->finalizado, 
        $sql = "SELECT
                    p.idproduccion,
                    '('||pt.descripcion||') '||upper(p.descripcion),
                    upper(pe.nombres || ' ' || pe.apellidos) AS personal,
                    a.descripcion,
                    substr(cast(p.fecha as text),9,2)||'/'||substr(cast(p.fecha as text),6,2)||'/'||substr(cast(p.fecha as text),1,4),
                    '<p style=\"color:green\">'||substr(cast(p.fechaini as text),9,2)||'/'||substr(cast(p.fechaini as text),6,2)||'/'||substr(cast(p.fechaini as text),1,4)||'</p>',
                    '<p style=\"color:red\">'||substr(cast(p.fechafin as text),9,2)||'/'||substr(cast(p.fechafin as text),6,2)||'/'||substr(cast(p.fechafin as text),1,4)||'</p>',                    
                    case p.estado when 1 then 'REGISTRADO'
                                  when 2 then 'FINALIZADO'                                   
                                  WHEN 0 THEN 'ANULADO'
                                  else '&nbsp;' end,
                    case p.estado when 1 then
                           case p.usuarioreg when '".$_SESSION['idusuario']."' then
                           '<a class=\"anular box-boton boton-anular\" id=\"v-'||p.idproduccion||'\" href=\"#\" title=\"Anular\" ></a>'
                           else
                                case ".$_SESSION['id_perfil']." when 1 then
                                '<a class=\"anular box-boton boton-anular\" id=\"p-'||p.idproduccion||'\" href=\"#\" title=\"Anular\" ></a>'
                                else '&nbsp;'
                                end
                           end
                        else '&nbsp;'
                        end,
                    case p.estado when 1 
                        then '<a class=\"finalizar box-boton boton-hand\" id=\"f-'||p.idproduccion||'\" href=\"#\" title=\"Finalizar produccion\" ></a>'
                            when 2
                        then '<a class=\"box-boton boton-ok\" title=\"En fase de acabado\" ></a>'
                    else '&nbsp;' end
                FROM
                produccion.produccion AS p
                INNER JOIN public.personal AS pe ON pe.idpersonal = p.idpersonal 
                inner join produccion.almacenes as a on a.idalmacen = p.idalmacen
                inner join produccion.producciontipo as pt on pt.idproducciontipo = p.idproducciontipo
                WHERE p.idproducciontipo=1 and a.idsucursal = ".$_SESSION['idsucursal'];
        //echo $sql;
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }
    function indexGridList($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        //Estados 0->anulado, 1->registrado, 2->finalizado, 
        $sql = "SELECT  dp.idproduccion_detalle,
                        ps.descripcion||' '||sps.descripcion as producto,
                        (dp.totalp) as cantidad,
                        dp.stock,
                        p.fechaini,
                        p.fechafin,
                        a.descripcion,  
                        pp.nombres||' '||pp.apellidos as responsable
                     from produccion.produccion_detalle as dp inner join
                        produccion.produccion as p on p.idproduccion = dp.idproduccion
                        inner join produccion.subproductos_semi as sps on sps.idsubproductos_semi = dp.idsubproductos_semi
                        inner join produccion.productos_semi as ps on ps.idproductos_semi = sps.idproductos_semi
                        inner join produccion.almacenes as a on a.idalmacen = p.idalmacen 
                        inner join personal as pp on p.idpersonal = pp.idpersonal
                     where p.estado = 2 and a.idsucursal = ".$_SESSION['idsucursal'];
     
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }
    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT
                                    p.idproduccion,
                                    p.descripcion,
                                    p.fechaini,
                                    p.fechafin,
                                    p.fecha,
                                    case p.estado when 1 then 'ACTIVO' else 'INCANTIVO' end,
                                    p.estado,
                                    p.idpersonal,
                                    pe.nombres || ' ' || pe.apellidos AS personal,
                                    pe.dni
                                    FROM
                                    produccion.produccion AS p
                                    INNER JOIN public.personal AS pe ON pe.idpersonal = p.idpersonal
                                    WHERE idproduccion = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT
                                        distinct d.idproduccion,
                                        d.idsubproductos_semi,
                                        (d.totalp) as cantidad,
                                        pr.descripcion || ', ' || spr.descripcion AS descripcion
                                    FROM produccion.produccion AS p
                                        INNER JOIN produccion.produccion_detalle AS d ON p.idproduccion = d.idproduccion
                                        INNER JOIN produccion.subproductos_semi AS spr ON spr.idsubproductos_semi = d.idsubproductos_semi
                                        INNER JOIN produccion.productos_semi AS pr ON pr.idproductos_semi = spr.idproductos_semi
                                    WHERE d.idproduccion = :id
                                    ORDER BY d.idproduccion");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();

        $stmt2 = $this->db->prepare("SELECT distinct p.descripcion,
                                    md.ctotal,
                                    a.descripcion as almacen,
                                    md.idtipoproducto as tipo
                                    from produccion.movim_proddet as mp
                                        inner join produccion.produccion_detalle as pd
                                        on mp.idproduccion_detalle = pd.idproduccion_detalle
                                        inner join movimientosdetalle as md on md.idmovimiento = mp.idmovimiento
                                            inner join produccion.producto as p on p.idproducto = md.idproducto
                                            inner join produccion.almacenes as a on a.idalmacen = md.idalmacen
                                    where pd.idproduccion = :id");

        $data = array();
        foreach($stmt->fetchAll() as $row)
        {
            $stmt2->bindParam(':id',$row['idproduccion'],PDO::PARAM_INT);
            $stmt2->execute();
            $data2 = array();
            foreach($stmt2->fetchAll() as $r)
            {
                $data2[] = array('descripcion'=>$r['descripcion'],'cantidad'=>$r['ctotal'],'almacen'=>$r['almacen'],'tipo'=>$r['tipo']);
            }
            $data[] = array('descripcion'=>$row['descripcion'],'cantidad'=>$row['cantidad'],'det'=>$data2);
        }
        return $data;
    }

    function insert($_P ) 
    {             
         $prod = json_decode($_P['prod']);         
         $item = $prod->item;
         $cont_prod = 0;
         for($i=0;$i<$item;$i++)
         {            
            if($prod->estado[$i])
                $cont_prod ++;
         }
        
        $idmovimientostipo = 12; //Salida por produccion
        $idmoneda = 1; //Soles
        $fecha = date('Y-m-d');
        $referencia =  'SALIDAS';
        $estado = 1;
        $idsucursal = $_SESSION['idsucursal'];
        $usuarioreg = $_SESSION['idusuario'];

        $idtipodocumento = 7;        
        $serie = '';
        $numero = '';
        $fechae =  date('Y-m-d');
        $idproveedor = 1;        
        $idformapago = 3; //No declara
        $guia_serie = '';
        $guia_numero = '';
        $fecha_guia =  date('Y-m-d');        
        $afecto=0;
        $idalmacen = $_P['idalmacenma']; //Almacen de movimiento
        $idalmacenp = $_P['idalmacenma'];; //Almacen de produccion ORIGGEN
        $idalmacend = $_P['idalmacenma'];; //Almacen de produccion DESTINO

        $igv = 0;
        $autogen = $_P['autogen'];
        if($autogen=="") $autogen=0;
        $sql = "INSERT INTO movimientos(idmovimientosubtipo, idmoneda, fecha, referencia, 
                                        estado, idsucursal, usuarioreg, idtipodocumento, serie, numero, 
                                        fechae, idproveedor, idformapago, guia_serie, guia_numero, 
                                        fecha_guia, afecto, idalmacen, igv, autogen) 
                            values(:p1, :p2, :p3, :p4, 
                                        :p5, :p6, :p7, :p8, :p9, :p10, 
                                        :p11, :p12, :p13, :p14, :p15, 
                                        :p16, :p17, :p18, :p19, :p20)";
        $stmt = $this->db->prepare($sql);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            if($cont_prod>0)
            {
                $stmt->bindParam(':p1',$idmovimientostipo,PDO::PARAM_INT);
                $stmt->bindParam(':p2',$idmoneda,PDO::PARAM_INT);
                $stmt->bindParam(':p3',$fecha,PDO::PARAM_STR);
                $stmt->bindParam(':p4',$referencia,PDO::PARAM_STR);
                $stmt->bindParam(':p5',$estado,PDO::PARAM_INT);
                $stmt->bindParam(':p6',$idsucursal,PDO::PARAM_INT);
                $stmt->bindParam(':p7',$usuarioreg,PDO::PARAM_INT);
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

                $stmt2 = $this->db->prepare('INSERT INTO movimientosdetalle(
                                                                idmovimiento, idalmacen, item, idproducto,
                                                                 idtipoproducto, cantidad, precio, estado, 
                                                                 largo,alto,espesor,ctotal,ctotal_current) 
                                                    values(:p1, :p2, :p3, :p4, :p5, :p6, 
                                                           :p7, :p8, :p9,:p10,:p11,:p12,:p13);');
                
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
                $items = 1;

                      
                for($i=0;$i<$item;$i++)
                {
                    $ite = $prod->materiap[$i]->nitem;
                    if($prod->estado[$i])
                    {
                        for($j=0;$j<$ite;$j++)
                        {
                            if($prod->materiap[$i]->estado->{$j})
                            {
                                $largo = 0; $alto = 0; $espesor = 0;
                                $too = (float)$prod->materiap[$i]->cantidad->{$j};
                                $idproducto = $prod->materiap[$i]->idproducto->{$j};
                                $idt = $prod->materiap[$i]->idt->{$j};

                                $stmt4->bindParam(':idtp',$idt,PDO::PARAM_INT);
                                $stmt4->bindParam(':ida',$idalmacen,PDO::PARAM_INT);
                                $stmt4->bindParam(':idp',$idproducto,PDO::PARAM_INT);
                                $stmt4->execute();
                                $row4 = $stmt4->fetchObject();                    

                                $too_current = (float)$row4->c - $too;
                                $cant = 1;
                                $precio = 0;

                                $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);
                                $stmt2->bindParam(':p2',$idalmacen,PDO::PARAM_INT);
                                $stmt2->bindParam(':p3',$items,PDO::PARAM_INT);
                                $stmt2->bindParam(':p4',$idproducto,PDO::PARAM_INT);
                                $stmt2->bindParam(':p5',$idt,PDO::PARAM_INT);
                                $stmt2->bindParam(':p6',$cant,PDO::PARAM_INT);
                                $stmt2->bindParam(':p7',$precio,PDO::PARAM_INT);
                                $stmt2->bindParam(':p8',$estado,PDO::PARAM_INT);
                                $stmt2->bindParam(':p9',$largo,PDO::PARAM_INT);
                                $stmt2->bindParam(':p10',$alto,PDO::PARAM_INT);
                                $stmt2->bindParam(':p11',$espesor,PDO::PARAM_INT);
                                $stmt2->bindParam(':p12',$too,PDO::PARAM_INT);
                                $stmt2->bindParam(':p13',$too_current,PDO::PARAM_INT);
                                $stmt2->execute();
                                $items += 1;

                                $stmt3->bindParam(':cant',$too,PDO::PARAM_INT);                    
                                $stmt3->bindParam(':idp',$idproducto,PDO::PARAM_INT);
                                $stmt3->execute();
                            }                        
                        }
                    }                
                }
            }

            //Ahora insertamos la produccion.
            $fechai=$_P['fechai'];
            $fechaf=$_P['fechaf'];
            $descripcion=$_P['descripcion'];
            $estado=1;
            $idpersonal=$_P['dni'];
            $idpersonal=$_P['idpersonal'];
            $sql="INSERT INTO produccion.produccion(
                        descripcion, fechaini, fechafin, estado, idpersonal, idalmacen,fecha,usuarioreg,idalmacend)
                VALUES (:p1, :p2, :p3, :p4,:p5,:p6,:p7,:p8,:p9)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':p1',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
            $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
            $stmt->bindParam(':p4',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':p5',$idpersonal,PDO::PARAM_STR);
            $stmt->bindParam(':p6',$idalmacenp,PDO::PARAM_INT);
            $stmt->bindParam(':p7',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':p8',$usuarioreg,PDO::PARAM_INT);
            $stmt->bindParam(':p9',$idalmacenp,PDO::PARAM_INT);
            $stmt->execute();

            $idprod =  $this->IdlastInsert('produccion.produccion','idproduccion');

            if($cont_prod>0)
            {

                $stmt2  = $this->db->prepare('INSERT INTO produccion.produccion_detalle(
                                            idproduccion, 
                                            idsubproductos_semi, 
                                            cantidad, 
                                            stock, 
                                            estado,
                                            item,
                                            ctotal,
                                            idalmacen,
                                            totalp)
                                            VALUES (:p1, :p2, :p3, :p4, :p5, :p6, :p7, :p8, :p9) ');
                $stmt3 = $this->db->prepare("INSERT INTO produccion.movim_proddet(
                                                idmovimiento,
                                            idproduccion_detalle) 
                                             values (:p1,:p2) ");

                $stmt4 = $this->db->prepare("SELECT t.idp,t.c
                                            from (
                                            SELECT max(idproduccion) as idp ,ctotal as c, item
                                            FROM produccion.produccion_detalle
                                            where idalmacen = :idal and idsubproductos_semi = :idsps
                                            group by ctotal,item,idproduccion
                                            order by idproduccion desc
                                            limit 1
                                            ) as t
                                            order by t.item,t.c");


                $estado = 1;
                //$items = 1;
                $contador = 0;
                for($i=0;$i<$item;$i++)
                {
                    if($prod->estado[$i])                
                    {
                        $contador +=1;
                        $idsps = $prod->idsps[$i];
                        $cantd = $prod->cantidad[$i];
                        $cantd1 = 0;

                        $stmt4->bindParam(':idal',$idalmacenp,PDO::PARAM_INT);
                        $stmt4->bindParam(':idsps',$idsps,PDO::PARAM_INT);
                        $stmt4->execute();
                        $row4 = $stmt4->fetchObject();                
                        $ctotal = (float)$row4->c + $cantd1;

                        $stmt2->bindParam(':p1',$idprod,PDO::PARAM_INT);
                        $stmt2->bindParam(':p2',$idsps,PDO::PARAM_INT);
                        $stmt2->bindParam(':p3',$cantd1,PDO::PARAM_INT);
                        $stmt2->bindParam(':p4',$cantd,PDO::PARAM_INT);
                        $stmt2->bindParam(':p5',$estado,PDO::PARAM_INT);
                        $stmt2->bindParam(':p6',$contador,PDO::PARAM_INT);
                        $stmt2->bindParam(':p7',$ctotal,PDO::PARAM_INT);
                        $stmt2->bindParam(':p8',$idalmacenp,PDO::PARAM_INT);
                        $stmt2->bindParam(':p9',$cantd,PDO::PARAM_INT);                        
                        $stmt2->execute();

                        $iddprod =  $this->IdlastInsert('produccion.produccion_detalle','idproduccion_detalle');

                        $stmt3->bindParam(':p1',$id,PDO::PARAM_INT);
                        $stmt3->bindParam(':p2',$iddprod,PDO::PARAM_INT);
                        $stmt3->execute();    
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

    function InsertProduccion($_P)
    {
        if(is_string($_P['prod']))
            $prod = json_decode($_P['prod']);
        else 
            $prod = json_decode(json_encode($_P['prod']));

        $item = $prod->item;
        $cont_prod = 0;
        for($i=0;$i<$item;$i++)
        {
           if($prod->estado[$i])
               $cont_prod ++;
        }

        $fecha = date('Y-m-d');
        $fechai=$_P['fechai'];
        $fechaf=$_P['fechaf'];        
        $estado=1;        
        $idpersonal=$_P['idpersonal'];
        $usuarioreg = $_SESSION['idusuario'];
        $idalmacenp = $_P['idalmacen']; //Almacen de produccion ORIGGEN
        if($_P['idalmacend']=="") 
            $_P['idalmacend'] = $_P['idalmacend'];
        $idalmacend = $_P['idalmacend']; //Almacen de produccion DESTINO
        $idproducciontipo = $_P['idproducciontipo'];

        $s = $this->db->prepare("SELECT descripcion,tipo FROM produccion.producciontipo
                                    where idproducciontipo={$idproducciontipo}");
        $s->execute();
        $prod_tipo = $s->fetchObject();
        $descripcion=$prod_tipo->descripcion.", Cod. Referencia: ".$_P['idreferencia'];

        $sql="INSERT INTO produccion.produccion(
                    descripcion, fechaini, fechafin, estado, idpersonal, idalmacen,fecha,usuarioreg,idalmacend,idproducciontipo)
            VALUES (:p1, :p2, :p3, :p4,:p5,:p6,:p7,:p8,:p9,:p10)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':p1',$descripcion,PDO::PARAM_STR);
        $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
        $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
        $stmt->bindParam(':p4',$estado,PDO::PARAM_INT);
        $stmt->bindParam(':p5',$idpersonal,PDO::PARAM_STR);
        $stmt->bindParam(':p6',$idalmacenp,PDO::PARAM_INT);
        $stmt->bindParam(':p7',$fecha,PDO::PARAM_STR);
        $stmt->bindParam(':p8',$usuarioreg,PDO::PARAM_INT);
        $stmt->bindParam(':p9',$idalmacend,PDO::PARAM_INT);
        $stmt->bindParam(':p10',$idproducciontipo,PDO::PARAM_INT);
        $stmt->execute();

        $idprod =  $this->IdlastInsert('produccion.produccion','idproduccion');

        if($cont_prod>0)
        {

            $stmt2  = $this->db->prepare('INSERT INTO produccion.produccion_detalle(
                                        idproduccion, 
                                        idsubproductos_semi, 
                                        cantidad, 
                                        stock, 
                                        estado,
                                        item,
                                        ctotal,
                                        idalmacen,
                                        totalp)
                                        VALUES (:p1, :p2, :p3, :p4, :p5, :p6, :p7, :p8, :p9) ');

            $stmt4 = $this->db->prepare("SELECT t.idp,t.c
                                        from (
                                        SELECT max(idproduccion) as idp ,ctotal as c, item
                                        FROM produccion.produccion_detalle
                                        where idalmacen = :idal and idsubproductos_semi = :idsps
                                        group by ctotal,item,idproduccion
                                        order by idproduccion desc
                                        limit 1
                                        ) as t
                                        order by t.item,t.c");


            $estado = 1;
            //$items = 1;
            $contador = 0;
            for($i=0;$i<$item;$i++)
            {
                if($prod->estado[$i])                
                {
                    $contador +=1;
                    $idsps = $prod->idsps[$i];
                    $cantd = $prod->cantidad[$i];

                    $idalm = $idalmacenp;
                    if(isset($prod->idalmacen[$i])&&$prod->idalmacen[$i]!="")
                        $idalm = $prod->idalmacen[$i];
                    $stmt4->bindParam(':idal',$idalm,PDO::PARAM_INT);
                    $stmt4->bindParam(':idsps',$idsps,PDO::PARAM_INT);
                    $stmt4->execute();
                    $row4 = $stmt4->fetchObject();

                    if($prod_tipo->tipo=='I')                
                        $ctotal = (float)$row4->c + $cantd;
                    else
                        $ctotal = (float)$row4->c - $cantd;
                    $stock=0; //Esto es el stock disponible para realizar acabados
                    $stmt2->bindParam(':p1',$idprod,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$idsps,PDO::PARAM_INT);
                    $stmt2->bindParam(':p3',$cantd,PDO::PARAM_INT);
                    $stmt2->bindParam(':p4',$stock,PDO::PARAM_INT);
                    $stmt2->bindParam(':p5',$estado,PDO::PARAM_INT);
                    $stmt2->bindParam(':p6',$contador,PDO::PARAM_INT);
                    $stmt2->bindParam(':p7',$ctotal,PDO::PARAM_INT);
                    $stmt2->bindParam(':p8',$idalm,PDO::PARAM_INT);
                    $stmt2->bindParam(':p9',$cantd,PDO::PARAM_INT);
                    $stmt2->execute();
                }        
                
            }
        }
    }

    function update($_P ) 
    {
        $prod = json_decode($_P['prod']);
        $item = $prod->item;        
        $cont_prod = 0;
        for($i=0;$i<$item;$i++)
        {
            if($prod->estado[$i])
                $cont_prod ++;
        }
        
        $idmovimientostipo = 12; //Salida por produccion
        $idmoneda = 1; //Soles
        $fecha = date('Y-m-d');
        $referencia =  'SALIDAS';
        $estado = 1;
        $idsucursal = 1;
        $usuarioreg = $_SESSION['idusuario'];

        $idtipodocumento = 7;        
        $serie = '';
        $numero = '';
        $fechae =  date('Y-m-d');
        $idproveedor = 1;        
        $idformapago = 3; //No declara
        $guia_serie = '';
        $guia_numero = '';
        $fecha_guia =  date('Y-m-d');        
        $afecto=0;
        $idalmacen = $_P['idalmacenma']; //Almacen de movimiento
        $idalmacenp = $_P['idalmacenma'];; //Almacen de produccion
        $igv = 0;

        $sql = "INSERT INTO movimientos(idmovimientosubtipo, idmoneda, fecha, referencia, 
                                        estado, idsucursal, usuarioreg, idtipodocumento, serie, numero, 
                                        fechae, idproveedor, idformapago, guia_serie, guia_numero, 
                                        fecha_guia, afecto, idalmacen, igv) 
                            values(:p1, :p2, :p3, :p4, 
                                        :p5, :p6, :p7, :p8, :p9, :p10, 
                                        :p11, :p12, :p13, :p14, :p15, 
                                        :p16, :p17, :p18, :p19)";
        $stmt = $this->db->prepare($sql);
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            if($cont_prod>0)
            {
                $stmt->bindParam(':p1',$idmovimientostipo,PDO::PARAM_INT);
                $stmt->bindParam(':p2',$idmoneda,PDO::PARAM_INT);
                $stmt->bindParam(':p3',$fecha,PDO::PARAM_STR);
                $stmt->bindParam(':p4',$referencia,PDO::PARAM_STR);
                $stmt->bindParam(':p5',$estado,PDO::PARAM_INT);
                $stmt->bindParam(':p6',$idsucursal,PDO::PARAM_INT);
                $stmt->bindParam(':p7',$usuarioreg,PDO::PARAM_INT);
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

                $stmt->execute();
                $id =  $this->IdlastInsert('movimientos','idmovimiento');
                $row = $stmt->fetchAll();

                $stmt2 = $this->db->prepare('INSERT INTO movimientosdetalle(
                                                                idmovimiento, idalmacen, item, idproducto,
                                                                 idtipoproducto, cantidad, precio, estado, 
                                                                 largo,alto,espesor,ctotal,ctotal_current) 
                                                    values(:p1, :p2, :p3, :p4, :p5, :p6, 
                                                           :p7, :p8, :p9,:p10,:p11,:p12,:p13);');
                
                $stmt3 = $this->db->prepare('UPDATE produccion.producto 
                                                        set stock = stock - :cant
                                                     WHERE idproducto = :idp');
                
                $stmt4 = $this->db->prepare('SELECT max(idmovimiento) as idm ,ctotal_current as c
                                                FROM movimientosdetalle
                                                where idtipoproducto = :idtp and idalmacen = :ida and idproducto = :idp
                                                group by ctotal_current,item
                                                order by item desc
                                                limit 1');
                $estado = 1;
                $items = 1;

                      
                for($i=0;$i<$item;$i++)
                {
                    $ite = $prod->materiap[$i]->nitem;
                    if($prod->estado[$i])
                    {
                        for($j=0;$j<$ite;$j++)
                        {
                            if($prod->materiap[$i]->estado->{$j})
                            {
                                $largo = 0; $alto = 0; $espesor = 0;
                                $too = (float)$prod->materiap[$i]->cantidad->{$j};
                                $idproducto = $prod->materiap[$i]->idproducto->{$j};
                                $idt = $prod->materiap[$i]->idt->{$j};

                                $stmt4->bindParam(':idtp',$idproducto,PDO::PARAM_INT);
                                $stmt4->bindParam(':ida',$idalmacen,PDO::PARAM_INT);
                                $stmt4->bindParam(':idp',$idproducto,PDO::PARAM_INT);
                                $stmt4->execute();
                                $row4 = $stmt4->fetchObject();                    
                                $too_current = (float)$row4->c - $too;

                                $cant = 1;
                                $precio = 0;

                                $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);
                                $stmt2->bindParam(':p2',$idalmacen,PDO::PARAM_INT);
                                $stmt2->bindParam(':p3',$items,PDO::PARAM_INT);
                                $stmt2->bindParam(':p4',$idproducto,PDO::PARAM_INT);
                                $stmt2->bindParam(':p5',$idt,PDO::PARAM_INT);
                                $stmt2->bindParam(':p6',$cant,PDO::PARAM_INT);
                                $stmt2->bindParam(':p7',$precio,PDO::PARAM_INT);
                                $stmt2->bindParam(':p8',$estado,PDO::PARAM_INT);
                                $stmt2->bindParam(':p9',$largo,PDO::PARAM_INT);
                                $stmt2->bindParam(':p10',$alto,PDO::PARAM_INT);
                                $stmt2->bindParam(':p11',$espesor,PDO::PARAM_INT);
                                $stmt2->bindParam(':p12',$too,PDO::PARAM_INT);
                                $stmt2->bindParam(':p13',$too_current,PDO::PARAM_INT);
                                $stmt2->execute();
                                $items += 1;

                                $stmt3->bindParam(':cant',$too,PDO::PARAM_INT);                    
                                $stmt3->bindParam(':idp',$idproducto,PDO::PARAM_INT);
                                $stmt3->execute();
                            }                        
                        }
                    }                
                }
            }



            $fechai=$_P['fechai'];
            $fechaf=$_P['fechaf'];
            $descripcion=$_P['descripcion'];
            $estado=1;
            $idpersonal=$_P['idpersonal'];
            $idproduccion= $_P['idproduccion'];

            $sql = "UPDATE produccion.produccion 
                        set 
                            descripcion=:p1,
                            fechaini=:p2,
                            fechafin=:p3,
                            estado=:p4,
                            idpersonal=:p5

                    WHERE   idproduccion = :idproduccion";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':p1', $descripcion , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $fechai , PDO::PARAM_STR);
            $stmt->bindParam(':p3', $fechaf , PDO::PARAM_STR);
            $stmt->bindParam(':p4', $estado , PDO::PARAM_INT);
            $stmt->bindParam(':p5', $idpersonal , PDO::PARAM_INT);

            $stmt->bindParam(':idproduccion', $idproduccion , PDO::PARAM_INT);
            $stmt->execute();

            $idprod = $idproduccion;
            if($cont_prod>0)
            {
                $stmt2  = $this->db->prepare('INSERT INTO produccion.produccion_detalle(
                                            idproduccion, 
                                            idsubproductos_semi, 
                                            cantidad, 
                                            stock, 
                                            estado,item,
                                            ctotal,
                                            idalmacen,
                                            totalp)
                                            VALUES (:p1, :p2, :p3, :p4, :p5) ');
                $stmt3 = $this->db->prepare("INSERT INTO produccion.movim_proddet(
                                                idmovimiento,
                                            idproduccion_detalle) 
                                             values (:p1,:p2) ");

                $stmt4 = $this->db->prepare("SELECT t.idp,t.c
                                            from (
                                            SELECT max(idproduccion) as idp ,ctotal as c, item
                                            FROM produccion.produccion_detalle
                                            where idalmacen = :idal and idsubproductos_semi = :idsps
                                            group by ctotal,item,idproduccion
                                            order by idproduccion desc
                                            limit 1
                                            ) as t
                                            order by t.item,t.c");
                $estado = 1;
                //$items = 1;
                for($i=0;$i<$item;$i++)
                {
                    //$items = $prod->materiap[$i]->nitem;
                    $contador +=1;
                    $idsps = $prod->idsps[$i];
                    $cantd1=0;
                    $cantd = $prod->cantidad[$i];

                    $stmt4->bindParam(':idal',$idalmacenp,PDO::PARAM_INT);
                    $stmt4->bindParam(':idsps',$idsps,PDO::PARAM_INT);
                    $stmt4->execute();
                    $row4 = $stmt4->fetchObject();                
                    $ctotal = (float)$row4->c + $cantd1;
                    

                    $stmt2->bindParam(':p1',$idprod,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$idsps,PDO::PARAM_INT);
                    $stmt2->bindParam(':p3',$cantd1,PDO::PARAM_INT);
                    $stmt2->bindParam(':p4',$cantd,PDO::PARAM_INT);
                    $stmt2->bindParam(':p5',$estado,PDO::PARAM_INT);

                    $stmt2->bindParam(':p6',$contador,PDO::PARAM_INT);
                    $stmt2->bindParam(':p7',$ctotal,PDO::PARAM_INT);
                    $stmt2->bindParam(':p8',$idalmacenp,PDO::PARAM_INT);
                    $stmt2->bindParam(':p9',$cantd,PDO::PARAM_INT); 
                    $stmt2->execute();

                    $iddprod =  $this->IdlastInsert('produccion.produccion_detalle','idproduccion_detalle');

                    $stmt3->bindParam(':p1',$id,PDO::PARAM_INT);
                    $stmt3->bindParam(':p2',$iddprod,PDO::PARAM_INT);
                    $stmt3->execute();
                }
            }

            $this->db->commit();            
            return array('1','Bien!',$idproduccion);

        }
        catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 
    }
    
    function delete($p) 
    {        
        $objmov = new movimiento();
        $stmtd = $this->db->prepare("SELECT distinct idmovimiento
                from produccion.movim_proddet as mp
                    inner join produccion.produccion_detalle as pd
                    on mp.idproduccion_detalle =
                      pd.idproduccion_detalle
                WHERE pd.idproduccion=:id");
        $stmtd->bindParam(':id',$p,PDO::PARAM_INT);
        $stmtd->execute();

        //Anulamos la produccion
        $stmtp = $this->db->prepare("UPDATE produccion.produccion set estado = 0 where idproduccion = :id");
        $stmtp->bindParam(':id',$p,PDO::PARAM_INT);
        $stmtp->execute();

        foreach($stmtd->fetchAll() as $r)
        {                
            $r = $objmov->delete($r['idmovimiento']);
        }
        return $r;        
    }

    function end($p)
    {
        $stmt = $this->db->prepare("UPDATE produccion.produccion set estado = 2
                                    where idproduccion = :id and estado = 1");
        $stmt->bindParam(':id',$p,PDO::PARAM_INT);
        $r = $stmt->execute();
        if($r) return array("1",'Ok, esta produccion fue finalizada');
            else return array("2",'Ha ocurrido un error, porfavor intentelo nuevamente');
    }

    function ViewResultados($_G)
    {
       
        $fechai = $this->fdate($_G['fechai'], 'EN');
        $fechaf = $this->fdate($_G['fechaf'], 'EN');

        $sql="SELECT
                p.idproduccion,
                upper(p.descripcion) AS produccion,
                upper(pe.nombres || ' ' || pe.apellidos) AS personal,
                a.descripcion AS almacen,
                substr(cast(p.fechaini as text),9,2)||'/'||substr(cast(p.fechaini as text),6,2)||'/'||substr(cast(p.fechaini as text),1,4) AS fechaini,
                substr(cast(p.fechafin as text),9,2)||'/'||substr(cast(p.fechafin as text),6,2)||'/'||substr(cast(p.fechafin as text),1,4) AS fechafin,                    
                case p.estado when 1 then 'REGISTRADO'
                      when 2 then 'FINALIZADO'                                   
                      WHEN 0 THEN 'ANULADO'
                      else '&nbsp;' end AS estado
            FROM
            produccion.produccion AS p
            INNER JOIN public.personal AS pe ON pe.idpersonal = p.idpersonal 
            inner join produccion.almacenes as a on a.idalmacen = p.idalmacen
            WHERE 
                p.idproducciontipo= 1 AND p.idproduccion<> 1 AND
                p.fecha BETWEEN CAST(:p1 AS DATE) AND CAST(:p2 AS DATE)
            ORDER BY p.idproduccion";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':p1', $fechai , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $fechaf, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll();

    }

    //
    function rptDetails($id)
    {
        $stmt = $this->db->prepare("SELECT
                    distinct d.idproduccion,
                    d.idsubproductos_semi,
                    d.cantidad,
                    pr.descripcion || ', ' || spr.descripcion AS descripcion
                FROM produccion.produccion AS p
                    INNER JOIN produccion.produccion_detalle AS d ON p.idproduccion = d.idproduccion
                    INNER JOIN produccion.subproductos_semi AS spr ON spr.idsubproductos_semi = d.idsubproductos_semi
                    INNER JOIN produccion.productos_semi AS pr ON pr.idproductos_semi = spr.idproductos_semi
                WHERE d.idproduccion = :id
                ORDER BY d.idproduccion");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();

        $stmt2 = $this->db->prepare("SELECT distinct p.descripcion,md.ctotal,
                    a.descripcion as almacen,
                    md.idtipoproducto as tipo
                    from produccion.movim_proddet as mp
                        inner join produccion.produccion_detalle as pd
                        on mp.idproduccion_detalle = pd.idproduccion_detalle
                        inner join movimientosdetalle as md on md.idmovimiento = mp.idmovimiento
                            inner join produccion.producto as p on p.idproducto = md.idproducto
                            inner join produccion.almacenes as a on a.idalmacen = md.idalmacen
                    where pd.idproduccion = :id");

        $data = array();
        foreach($stmt->fetchAll() as $row)
        {
            $stmt2->bindParam(':id',$row['idproduccion'],PDO::PARAM_INT);
            $stmt2->execute();
            $data2 = array();
            foreach($stmt2->fetchAll() as $r)
            {
                $data2[] = array('descripcion'=>$r['descripcion'],'cantidad'=>$r['ctotal'],'almacen'=>$r['almacen'],'tipo'=>$r['tipo']);
            }
            $data[] = array('descripcion'=>$row['descripcion'],'cantidad'=>$row['cantidad'],'det'=>$data2);
        }
        //print_r($data[0]);
        return $data;
    }

    
}
?>