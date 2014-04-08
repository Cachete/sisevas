<?php
include_once("Main.php");
include_once("movimiento.php");
include_once("produccion.php");

class traslado extends Main
{    
    //indexGridi -> Grilla del index de ingresos.
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        //Estados 0->anulado, 1->registrado, 2->finalizado, 
        $sql = "SELECT
                    p.idproduccion,
                    upper(p.descripcion),                    
                    substr(cast(p.fecha as text),9,2)||'/'||substr(cast(p.fecha as text),6,2)||'/'||substr(cast(p.fecha as text),1,4),
                    a.descripcion,
                    a2.descripcion,
                    case p.estado when 1 then 'REGISTRADO'
                                  when 2 then 'FINALIZADO'                                   
                                  WHEN 0 THEN 'ANULADO'
                                  else '&nbsp;' end,
                    case p.estado when 1 then
                           case p.usuarioreg when '".$_SESSION['idusuario']."' then
                                case p.idproducciontipo when 4 then
                                    '<a class=\"anular box-boton boton-anular\" id=\"v-'||p.idproduccion||'\" href=\"#\" title=\"Anular\" ></a>'
                                else '&nbsp;'
                                end 
                           else
                                case ".$_SESSION['id_perfil']." when 1 then                                    
                                        '<a class=\"anular box-boton boton-anular\" id=\"p-'||p.idproduccion||'\" href=\"#\" title=\"Anular\" ></a>'                                    
                                else '&nbsp;'
                                end
                           end
                        else '&nbsp;'
                        end
                FROM
                produccion.produccion AS p
                INNER JOIN public.personal AS pe ON pe.idpersonal = p.idpersonal 
                inner join produccion.almacenes as a on a.idalmacen = p.idalmacen
                inner join produccion.almacenes as a2 on a2.idalmacen = p.idalmacend
                inner join produccion.producciontipo as pt on pt.idproducciontipo = p.idproducciontipo                
                WHERE p.idproducciontipo=4 or  p.idproducciontipo=7 and
                        (a.idsucursal = ".$_SESSION['idsucursal']." 
                        or a2.idsucursal = ".$_SESSION['idsucursal'].")";
        //echo $sql;
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
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


    function insert($_P)
    {        
        $prod = json_decode($_P['producto']);
        $item = $prod->nitem;        
        $cont_prod = 0;
        for($i=0;$i<$item;$i++)
        {
           if($prod->estado[$i])
               $cont_prod ++;
        }
      

        $fecha = date('Y-m-d');
        $fechai=date('Y-m-d');
        $fechaf=date('Y-m-d');
        $estado=1;        
        $idpersonal= $_SESSION['idusuario'];
        $usuarioreg = $_SESSION['idusuario'];
        $idalmacen = $_P['idalmacen']; //Almacen de produccion ORIGGEN
        $idalmacend = $_P['idalmacend']; //Almacen de produccion DESTINO

        
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();


            //Primero hacemos el movimiento correspondiente a un Egreso por transferencia
            $idproducciontipo = 4; //Egreso por transferencia
            $s = $this->db->prepare("SELECT descripcion,tipo FROM produccion.producciontipo
                                        where idproducciontipo={$idproducciontipo}");
            $s->execute();
            $prod_tipo = $s->fetchObject();
            $descripcion=$prod_tipo->descripcion;


            $sql="INSERT INTO produccion.produccion(
                        descripcion, fechaini, fechafin, estado, idpersonal, 
                        idalmacen,fecha,usuarioreg,idalmacend,idproducciontipo)
                VALUES (:p1, :p2, :p3, :p4,:p5,:p6,:p7,:p8,:p9,:p10)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':p1',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
            $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
            $stmt->bindParam(':p4',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':p5',$idpersonal,PDO::PARAM_STR);
            $stmt->bindParam(':p6',$idalmacen,PDO::PARAM_INT);
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
                        $idsps = $prod->idproducto[$i];
                        $cantd = $prod->cantidad[$i];

                        $stmt4->bindParam(':idal',$idalmacen,PDO::PARAM_INT);
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
                        $stmt2->bindParam(':p8',$idalmacen,PDO::PARAM_INT);
                        $stmt2->bindParam(':p9',$cantd,PDO::PARAM_INT);
                        $stmt2->execute();
                    }
                }
            }


            //Segundo hacemos el movimiento correspondiente a un Ingreso por transferencia 
            $idproducciontipo = 2; //Ingreso por transferencia
            $s = $this->db->prepare("SELECT descripcion,tipo FROM produccion.producciontipo
                                        where idproducciontipo={$idproducciontipo}");
            $s->execute();
            $prod_tipo = $s->fetchObject();
            $descripcion=$prod_tipo->descripcion.", Cod. Referencia: ".$idprod;

            $idprod_ref = $idprod;


            $sql="INSERT INTO produccion.produccion(
                        descripcion, fechaini, fechafin, estado, idpersonal, 
                        idalmacen,fecha,usuarioreg,idalmacend,idproducciontipo,idproduccion_ref)
                VALUES (:p1, :p2, :p3, :p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':p1',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
            $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
            $stmt->bindParam(':p4',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':p5',$idpersonal,PDO::PARAM_STR);
            $stmt->bindParam(':p6',$idalmacen,PDO::PARAM_INT);
            $stmt->bindParam(':p7',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':p8',$usuarioreg,PDO::PARAM_INT);
            $stmt->bindParam(':p9',$idalmacend,PDO::PARAM_INT);
            $stmt->bindParam(':p10',$idproducciontipo,PDO::PARAM_INT);
            $stmt->bindParam(':p11',$idprod_ref,PDO::PARAM_INT);
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
                        $idsps = $prod->idproducto[$i];
                        $cantd = $prod->cantidad[$i];

                        $stmt4->bindParam(':idal',$idalmacend,PDO::PARAM_INT);
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
                        $stmt2->bindParam(':p8',$idalmacend,PDO::PARAM_INT);
                        $stmt2->bindParam(':p9',$cantd,PDO::PARAM_INT);
                        $stmt2->execute();
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
    
    function delete($p) 
    {   
        $objprod = new Produccion();
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $s = $this->db->prepare("SELECT * from produccion.produccion where idproduccion = :id");
            $s->bindParam(':id',$p,PDO::PARAM_INT);
            $s->execute();
            $row = $s->fetchObject();        


            $_P['fechai'] = date('Y-m-d');
            $_P['fechaf'] = date('Y-m-d');        
            $_P['idpersonal'] = $_SESSION['idusuario'];
            
            $_P['idalmacen']  = $row->idalmacend;
            $_P['idalmacend']  = $row->idalmacen;
            $_P['idproducciontipo'] = 7;

            $sd = $this->db->prepare("SELECT * from produccion.produccion_detalle
                                          where idproduccion = :id");
            $sd->bindParam(':id',$p,PDO::PARAM_INT);
            $sd->execute();
            $cont = $sd->rowCount();
            $_P['prod'] = array();
            $_P['prod']['item'] = $cont;
            $_P['prod']['idsps']=array();
            $_P['prod']['cantidad']=array();
            $_P['prod']['estado']=array();
            foreach($sd->fetchAll() as $r)
            {
                $_P['prod']['idalmacen'][] = $row->idalmacen;
                $_P['prod']['idsps'][] = $r['idsubproductos_semi'];
                $_P['prod']['cantidad'][] = $r['cantidad'];
                $_P['prod']['estado'][] = true;
            } 

            $_P['idreferencia'] = $p;

            $objprod->InsertProduccion($_P);

            $s = $this->db->prepare("UPDATE produccion.produccion set estado = 0 
                                        where idproduccion = :id");
            $s->bindParam(':id',$p,PDO::PARAM_INT);
            $s->execute();

            

            ////////////////
            //Segunda parte
            ////////////////

            $s = $this->db->prepare("SELECT * from produccion.produccion where idproduccion_ref = :id");
            $s->bindParam(':id',$p,PDO::PARAM_INT);
            $s->execute();
            $row = $s->fetchObject();        

            $idprod_ref = $row->idproduccion;

            $_P['fechai'] = date('Y-m-d');
            $_P['fechaf'] = date('Y-m-d');        
            $_P['idpersonal'] = $_SESSION['idusuario'];
            
            $_P['idalmacen']  = $row->idalmacen;
            $_P['idalmacend']  = $row->idalmacend;
            $_P['idproducciontipo'] = 8;

            $sd = $this->db->prepare("SELECT * from produccion.produccion_detalle
                                          where idproduccion = :id");
            $sd->bindParam(':id',$p,PDO::PARAM_INT);
            $sd->execute();
            $cont = $sd->rowCount();
            $_P['prod'] = array();
            $_P['prod']['item'] = $cont;
            $_P['prod']['idsps']=array();
            $_P['prod']['cantidad']=array();
            $_P['prod']['estado']=array();
            foreach($sd->fetchAll() as $r)
            {
                $_P['prod']['idalmacen'][] = $row->idalmacend;
                $_P['prod']['idsps'][] = $r['idsubproductos_semi'];
                $_P['prod']['cantidad'][] = $r['cantidad'];
                $_P['prod']['estado'][] = true;
            } 

            $_P['idreferencia'] = $idprod_ref;

            $objprod->InsertProduccion($_P);

            $s = $this->db->prepare("UPDATE produccion.produccion set estado = 0 
                                        where idproduccion = :id");
            $s->bindParam(':id',$idprod_ref,PDO::PARAM_INT);
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
}
?>