<?php
include_once("Main.php");
class acabado extends Main
{    
    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        
        $sql = "SELECT  a.idacabado,
                        ps.descripcion||' '||sps.descripcion,
                        upper(pe.nombres || ' ' || pe.apellidos) AS personal,
                        a.cantidad,
                        substr(cast(a.fecha as text),9,2)||'/'||substr(cast(a.fecha as text),6,2)||'/'||substr(cast(a.fecha as text),1,4),
                        '<p style=\"color:green\">'||substr(cast(a.fechaini as text),9,2)||'/'||substr(cast(a.fechaini as text),6,2)||'/'||substr(cast(a.fechaini as text),1,4)||'</p>',
                        '<p style=\"color:red\">'||substr(cast(a.fechafin as text),9,2)||'/'||substr(cast(a.fechafin as text),6,2)||'/'||substr(cast(a.fechafin as text),1,4)||'</p>',                    
                        al.descripcion,
                        case a.estado when 1 then 'REGISTRADO'
                              when 2 then 'FINALIZADO'                                   
                              WHEN 0 THEN 'ANULADO'
                              else '&nbsp;' end,
                        case a.estado when 1 then
                           case a.usuarioreg when '".$_SESSION['idusuario']."' then
                           '<a class=\"anular box-boton boton-anular\" id=\"a-'||a.idacabado||'\" href=\"#\" title=\"Anular\" ></a>'
                           else
                            case ".$_SESSION['id_perfil']." when 1 then
                            '<a class=\"anular box-boton boton-anular\" id=\"a-'||a.idacabado||'\" href=\"#\" title=\"Anular\" ></a>'
                            else '&nbsp;'
                            end
                           end
                        else '&nbsp;'
                        end,
                        case a.estado when 1 
                        then '<a class=\"finalizar box-boton boton-hand\" id=\"f-'||a.idacabado||'\" href=\"#\" title=\"Confirmar el finalizar acabado\" ></a>'
                            when 2
                        then '<a class=\"box-boton boton-ok\" title=\"Finalizado\" ></a>'
                        else '&nbsp;' end
                    FROM produccion.acabado AS a
                    INNER JOIN public.personal AS pe ON pe.idpersonal = a.idpersonal 
                    inner join produccion.produccion_detalle as pd on pd.idproduccion_detalle = a.idproduccion_detalle
                    inner join produccion.subproductos_semi as sps on sps.idsubproductos_semi = pd.idsubproductos_semi
                    inner join produccion.productos_semi as ps on ps.idproductos_semi = sps.idproductos_semi
                    inner join produccion.produccion as p on p.idproduccion = pd.idproduccion
                    inner join produccion.almacenes as al on al.idalmacen = p.idalmacen";
        
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT  a.*,
                                            pe.nombres || ' ' || pe.apellidos AS personal,
                                            pe.dni,
                                            ps.descripcion||' '||sps.descripcion as producto,
                                            pd.cantidad as tprod,
                                            (pd.stock+a.cantidad) as stock,
                                            pe2.nombres || ' ' || pe2.apellidos AS responsable
                                    FROM produccion.acabado AS a
                                         INNER JOIN public.personal AS pe ON pe.idpersonal = a.idpersonal 
                                            inner join produccion.produccion_detalle as pd on pd.idproduccion_detalle = a.idproduccion_detalle
                                            inner join produccion.subproductos_semi as sps on sps.idsubproductos_semi = pd.idsubproductos_semi
                                            inner join produccion.productos_semi as ps on ps.idproductos_semi = sps.idproductos_semi
                                            inner join produccion.produccion as p on p.idproduccion = pd.idproduccion
                                            inner join produccion.almacenes as al on al.idalmacen = p.idalmacen
                                            inner join personal as pe2 on pe2.idpersonal = p.idpersonal
                                    WHERE a.idacabado = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT m.idmateriales,
                                            m.descripcion,
                                            am.idunidad_medida,
                                            um.descripcion,
                                            am.cantidad 
                                    from produccion.acabadoxmateriales as am
                                    inner join produccion.materiales as m on m.idmateriales = am.idmateriales
                                    inner join unidad_medida as um on um.idunidad_medida = am.idunidad_medida
                                    WHERE am.idacabado = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll() as $r)
        {
            $data[] = array('idmaterial'=>$r[0],'material'=>$r[1],'idunidad'=>$r[2],'unidad'=>$r[3],'cantidad'=>$r[4]);
        }
        return $data;
    }

    function insert($_P ) 
    {             
         $materiales = json_decode($_P['materiales']);                  
         $item = $materiales->nitem;
         $cont_ma = 0;
         for($i=0;$i<$item;$i++)
         {
            if($materiales->estado[$i])
                $cont_ma ++;
         }
         
        $fechai=$this->fdate($_P['fechai'],"EN");
        $fechaf=$this->fdate($_P['fechaf'],"EN");
        $observacion=$_P['observacion'];
        $estado=1;
        $idpersonal=$_P['idpersonal'];
        $usuarioreg = $_SESSION['idusuario'];
        $cantidad = $_P['cantidad'];
        $fecha = date('Y-m-d');
        $idproduccion_detalle = $_P['idproduccion_detalle'];
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();
            $sql="INSERT INTO produccion.acabado(
                                idproduccion_detalle, 
                                idpersonal, 
                                cantidad, 
                                fechaini,
                                fechafin,
                                usuarioreg, 
                                observacion,
                                estado,
                                fecha)
                VALUES (:p1, :p2, :p3, :p4,:p5,:p6,:p7,:p8,:p9)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':p1',$idproduccion_detalle,PDO::PARAM_INT);
            $stmt->bindParam(':p2',$idpersonal,PDO::PARAM_INT);
            $stmt->bindParam(':p3',$cantidad,PDO::PARAM_INT);
            $stmt->bindParam(':p4',$fechai,PDO::PARAM_STR);
            $stmt->bindParam(':p5',$fechaf,PDO::PARAM_STR);
            $stmt->bindParam(':p6',$usuarioreg,PDO::PARAM_INT);            
            $stmt->bindParam(':p7',$observacion,PDO::PARAM_STR);            
            $stmt->bindParam(':p8',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':p9',$fecha,PDO::PARAM_STR);            
            $stmt->execute();

            $idacabado =  $this->IdlastInsert('produccion.acabado','idacabado');


            $stmt = $this->db->prepare("UPDATE produccion.produccion_detalle 
                                                set stock = stock - :c
                                        where idproduccion_detalle = :id");
            $stmt->bindParam(':c',$cantidad,PDO::PARAM_INT);
            $stmt->bindParam(':id',$idproduccion_detalle,PDO::PARAM_INT);
            $stmt->execute();

            if($cont_ma>0)
            {
                $stmt2  = $this->db->prepare('INSERT INTO produccion.acabadoxmateriales(
                                                    idacabado, idmateriales, idunidad_medida, cantidad)
                                                VALUES (:p1, :p2, :p3, :p4) ');
                
                $estado = 1;                
                for($i=0;$i<$item;$i++)
                {
                    //$items = $prod->materiap[$i]->nitem;
                    $idmaterial = $materiales->idmaterial[$i];
                    $idunidad = $materiales->idunidad[$i];
                    $cantidad = $materiales->cantidad[$i];

                    $stmt2->bindParam(':p1',$idacabado,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$idmaterial,PDO::PARAM_INT);
                    $stmt2->bindParam(':p3',$idunidad,PDO::PARAM_INT);
                    $stmt2->bindParam(':p4',$cantidad,PDO::PARAM_INT);                    
                    $stmt2->execute();
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
        die;
    }
    
    function delete($p) 
    {   
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("SELECT cantidad,idproduccion_detalle FROM produccion.acabado 
                                        where idacabado = :id");
            $stmt->bindParam(':id',$p,PDO::PARAM_INT);
            $stmt->execute();
            $r = $stmt->fetchObject();
            $cant = $r->cantidad;
            $idpd = $r->idproduccion_detalle;

            $stmt = $this->db->prepare("UPDATE produccion.produccion_detalle 
                                                set stock = stock + {$cant},
                                                    cantidad = cantidad - {$cant}
                                        where idproduccion_detalle = {$idpd}");
            $stmt->execute();

            $stmt = $this->db->prepare("UPDATE produccion.acabado set estado = 0 
                                            where idacabado = :id");
            $stmt->bindParam(':id',$p,PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return array('1','Bien!',$id);
        }
        catch(PDOException $e)
        {
            $this->db->rollBack();
            return array('2',$e->getMessage().$str,'');
        }   
    }

    function end($p)
    {
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("UPDATE produccion.acabado set estado = 2
                                        where idacabado = :id and estado = 1");
            $stmt->bindParam(':id',$p,PDO::PARAM_INT);
            $r = $stmt->execute();

            $s = $this->db->prepare("SELECT a.cantidad,a.idproduccion_detalle, pd.idalmacen, 
                                            pd.idsubproductos_semi
                                    from produccion.acabado as a inner join produccion.produccion_detalle as pd
                                        on pd.idproduccion_detalle = a.idproduccion_detalle
                                     where a.idacabado = :id ");
            $s->bindParam(':id',$p,PDO::PARAM_INT);
            $s->execute();
            $row = $s->fetchObject();

            $s2 = $this->db->prepare("UPDATE produccion.produccion_detalle 
                                            set cantidad = cantidad + ".$row->cantidad.",
                                                ctotal = ctotal + ".$row->cantidad."
                                            WHERE idproduccion_detalle = ".$row->idproduccion_detalle);

            //Verifico si ese detalle de produccion fue el ultimo para ese producto y almacen            
            $s3 = $this->db->prepare("SELECT max(idproduccion_detalle) as ipd
                                     FROM produccion.produccion_detalle
                                     WHERE idalmacen = ".$row->idalmacen." 
                                            and idsubproductos_semi=".$row->idsubproductos_semi);
            $s3->execute();
            $row2 = $s3->fetchObject();
            if($row2->ipd!=$row->idproduccion_detalle)
            {
                $s4 = $this->db->prepare("UPDATE produccion.produccion_detalle 
                                    set ctotal = ctotal + ".$row->cantidad."
                                 WHERE idproduccion_detalle = ".$row2->ipd);
                $s4->execute();
                    
            }
            

            $s2->execute();

            $this->db->commit();
            return array("1",'Ok, este acabado fue finalizada');
        }
        catch(PDOException $e)
        {
            $this->db->rollBack();
            return array('2',$e->getMessage().$str,'');            
        }


    }
}
?>