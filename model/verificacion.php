<?php
include_once("Main.php");
class Verificacion extends Main
{    
    //indexGridi -> Grilla del index de ingresos.
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
        s.idsolicitud,
        c.dni,
        c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno AS cleintes,
        s.fechasolicitud,
        su.descripcion,        
        case 
            when s.estado=0 then 'POR EVALUAR' 
            when s.estado=1 then 'ANULADO'
            when s.estado=2 then 'APROBADA'
            when s.estado=3 then 'RECHAZADA'
            else 'ATENDIDA' end,
        case when s.estado=0 then
        '<a class=\"anular box-boton boton-anular\" id=\"v-'||s.idsolicitud||'\" href=\"#\" title=\"Anular\" ></a>'
        else '&nbsp;' end/*,
        case when s.estado=0
            then '<a class=\"evaluar box-boton boton-hand\" id=\"f-'||s.idsolicitud||'\" href=\"#\" title=\"Evaluar proforma\" ></a>'
                when s.estado=3
            then '<a class=\"box-boton boton-ok\" title=\"Solicitud atendida\" ></a>'
        else '&nbsp;' end*/

        FROM
        facturacion.solicitud AS s
        INNER JOIN sucursales AS su ON su.idsucursal = s.idsucursal
        INNER JOIN cliente AS c ON c.idcliente = s.idcliente 
        WHERE s.idsucursal = ".$_SESSION['idsucursal'];
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT
            s.idsolicitud,
            s.idsucursal,
            s.idvendedor,
            s.idtipvivicliente,
            s.idcliente,
            s.fechavenc1,
            s.fechasolicitud,
            s.idproforma,
            s.nombreref,
            s.relacionref,
            s.telefonoref,
            s.obs,
            s.estado,
            c.dni AS cli_dni,
            c.idtipocliente,
            c.nombres || ' ' || c.apematerno || ' ' || c.apepaterno AS nomcliente,
            c.sexo,
            c.direccion,
            c.referencia_ubic,
            c.telefono,
            c.ocupacion,
            c.idestado_civil,
            c.idgradinstruccion,
            c.idtipovivienda,
            c.trabajo,
            c.dirtrabajo,
            c.teltrab,
            c.cargo,
            c.carga_familiar AS cargafam,
            c.ingreso AS ingresocli,
            c.idconyugue,
            con.dni AS con_dni,
            con.nombres || ' ' || con.apematerno || ' ' || con.apepaterno AS nomconyugue,
            con.ocupacion AS con_ocupacion,
            con.trabajo AS con_trabajo,
            con.dirtrabajo AS con_dirtrabajo,
            con.cargo AS con_cargo,
            con.ingreso AS ingresocon,
            con.teltrab AS con_teltrab
            FROM
            facturacion.solicitud AS s
            INNER JOIN cliente AS c ON c.idcliente = s.idcliente
            LEFT JOIN cliente AS con ON con.idcliente = c.idconyugue

            WHERE idsolicitud = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT
            ds.idsolicitud,
            ds.idproducto,
            ds.idtipopago,
            tp.descripcion,
            ds.preciocash,
            ds.inicial,
            ds.nromeses,
            ds.cuota,
            ds.cantidad,
            ds.idfinanciamiento,
            ds.producto

            FROM
            facturacion.solicitud AS s
            INNER JOIN facturacion.solicituddetalle AS ds ON s.idsolicitud = ds.idsolicitud
            INNER JOIN produccion.subproductos_semi AS pr ON pr.idsubproductos_semi = ds.idproducto
            INNER JOIN produccion.tipopago AS tp ON tp.idtipopago = ds.idtipopago

            WHERE ds.idsolicitud = :id    
            ORDER BY ds.idproducto ");

        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function insert($_P ) 
    {
        $fecha= $_P['fecha'];
        $idvendedor = $_SESSION['idusuario'];
        $idsucursal = $_SESSION['idsucursal'];
        $idproforma= $_P['idproforma'];
        if($_P['Estado']=='undefined')
        {
            $estado=0;
        }else
            {
                $estado=$_P['Estado'];
            }


        $sql="INSERT INTO facturacion.solicitud(idsucursal, idvendedor, idcliente, idtipvivicliente, 
            trabajocliente, dirtrabajocliente, cargocliente, teltrabcliente, ingresocliente, trabajoconyugue, 
            dirtrabajoconyugue, cargoconyugue, teltrabconyugue, ingresoconyugue, fechasolicitud, fechavenc1,
            idproforma,estado,carga_familiar,nombreref , relacionref , telefonoref,obs)
            VALUES (:p1, :p2, :p3, :p4,:p5,:p6, :p7, :p8, :p9,:p10,:p11, :p12, :p13, :p14,:p15,:p16, :p17, 
                :p18, :p19, :p20, :p21, :p22, :p23 )";
        
        $stmt = $this->db->prepare($sql);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            if($idproforma=='' || $idproforma==0)
            {
                $idproforma=0;

                $stmt->bindParam(':p1',$idsucursal,PDO::PARAM_INT);
                $stmt->bindParam(':p2',$idvendedor,PDO::PARAM_INT);
                $stmt->bindParam(':p3',$_P['idcliente'],PDO::PARAM_INT);
                $stmt->bindParam(':p4',$_P['idtipovivienda'],PDO::PARAM_INT);
                $stmt->bindParam(':p5',$_P['trabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p6',$_P['dirtrabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p7',$_P['cargo'],PDO::PARAM_STR);
                $stmt->bindParam(':p8',$_P['teltrab'],PDO::PARAM_STR);
                $stmt->bindParam(':p9',$_P['ingresocli'],PDO::PARAM_INT);
                $stmt->bindParam(':p10',$_P['con_trabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p11',$_P['con_dirtrabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p12',$_P['con_cargo'],PDO::PARAM_STR);
                $stmt->bindParam(':p13',$_P['con_teltrab'],PDO::PARAM_STR);
                $stmt->bindParam(':p14',$_P['ingresocon'],PDO::PARAM_INT);
                $stmt->bindParam(':p15',$_P['fechasolicitud'],PDO::PARAM_STR);
                $stmt->bindParam(':p16',$_P['fechavenc'],PDO::PARAM_STR);
                $stmt->bindParam(':p17',$idproforma,PDO::PARAM_INT);
                $stmt->bindParam(':p18',$estado,PDO::PARAM_INT);
                $stmt->bindParam(':p19',$_P['cargafam'],PDO::PARAM_INT);
                $stmt->bindParam(':p20',$_P['nomgarant'],PDO::PARAM_STR);
                $stmt->bindParam(':p21',$_P['relacion'],PDO::PARAM_STR);
                $stmt->bindParam(':p22',$_P['gar_telefono'],PDO::PARAM_STR);
                $stmt->bindParam(':p23',$_P['obs'],PDO::PARAM_STR);

            }else
                {

                    $stmt->bindParam(':p1',$idsucursal,PDO::PARAM_INT);
                    $stmt->bindParam(':p2',$idvendedor,PDO::PARAM_INT);
                    $stmt->bindParam(':p3',$_P['idcliente'],PDO::PARAM_INT);
                    $stmt->bindParam(':p4',$_P['idtipovivienda'],PDO::PARAM_INT);
                    $stmt->bindParam(':p5',$_P['trabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p6',$_P['dirtrabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p7',$_P['cargo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p8',$_P['teltrab'],PDO::PARAM_STR);
                    $stmt->bindParam(':p9',$_P['ingresocli'],PDO::PARAM_INT);
                    $stmt->bindParam(':p10',$_P['con_trabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p11',$_P['con_dirtrabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p12',$_P['con_cargo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p13',$_P['con_teltrab'],PDO::PARAM_STR);
                    $stmt->bindParam(':p14',$_P['ingresocon'],PDO::PARAM_INT);
                    $stmt->bindParam(':p15',$_P['fechasolicitud'],PDO::PARAM_STR);
                    $stmt->bindParam(':p16',$_P['fechavenc'],PDO::PARAM_STR);
                    $stmt->bindParam(':p17',$idproforma,PDO::PARAM_INT);
                    $stmt->bindParam(':p18',$estado,PDO::PARAM_INT);
                    $stmt->bindParam(':p19',$_P['cargafam'],PDO::PARAM_INT);
                    $stmt->bindParam(':p20',$_P['nomgarant'],PDO::PARAM_STR);
                    $stmt->bindParam(':p21',$_P['relacion'],PDO::PARAM_STR);
                    $stmt->bindParam(':p22',$_P['gar_telefono'],PDO::PARAM_STR);
                    $stmt->bindParam(':p23',$_P['obs'],PDO::PARAM_STR);

                    $estdo=2;
                    $stmt1 = $this->db->prepare("UPDATE facturacion.proforma
                            set estado = :p1
                        WHERE idproforma = :idproforma");
                    $stmt1->bindParam(':p1', $estdo , PDO::PARAM_STR);                    

                    $stmt1->bindParam(':idproforma', $idproforma , PDO::PARAM_INT);
                    $stmt1->execute();

                }            

            $stmt->execute();
            
            $id =  $this->IdlastInsert('facturacion.solicitud','idsolicitud');
            $row = $stmt->fetchAll();

            $stmt2  = $this->db->prepare("INSERT INTO facturacion.solicituddetalle(
            idsolicitud, idsucursal, idtipopago, preciocash, inicial, 
            nromeses, cuota, idfinanciamiento, producto,cantidad,idproducto)
                VALUES ( :p1, :p2,:p3, :p4,:p5, :p6,:p7, :p8,:p9,:p10,:p11) ");

            $idsucursal = $_SESSION['idsucursal'];

            foreach($_P['idtipopago'] as $i => $idtipopago)
                {
                    $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$idsucursal,PDO::PARAM_INT);
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
                }

            $this->db->commit();            
            return array('1','Bien!',$id);

        }catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            }
            
    }

    function update($_P ) 
    {
        
        $fecha= $_P['fechasolicitud'];
        $idvendedor = $_SESSION['idusuario'];
        $idsucursal = $_SESSION['idsucursal'];
        $idproforma= $_P['idproforma'];

        if($_P['Estado']=='undefined')
        {
            $estado=0;
        }else
            {
                $estado=$_P['Estado'];
            }

        //$estado=0;
        $idsolicitud= $_P['idsolicitud'];

        $del="DELETE FROM facturacion.solicituddetalle
                    WHERE idsolicitud='$idsolicitud' ";
                    
            $res = $this->db->prepare($del);
            $res->execute();

        $sql = "UPDATE facturacion.solicitud
                    set
                        idsucursal=:p1, idvendedor=:p2, idcliente=:p3, idtipvivicliente=:p4, 
                       trabajocliente=:p5, dirtrabajocliente=:p6, cargocliente=:p7, 
                       teltrabcliente=:p8, ingresocliente=:p9, trabajoconyugue=:p10, 
                       dirtrabajoconyugue=:p11, cargoconyugue=:p12, teltrabconyugue=:p13, 
                       ingresoconyugue=:p14, fechasolicitud=:p15, 
                       fechavenc1=:p16, idproforma=:p17, estado=:p18, carga_familiar=:p19,
                       nombreref=:p20 , relacionref=:p21 , telefonoref=:p22 , obs=:p23
                WHERE   idsolicitud = :idsolicitud ";
                //print_r($sql);
        $stmt = $this->db->prepare($sql);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            if($idproforma=='' || $idproforma==0)
            {
                $idproforma=0;

                $stmt->bindParam(':p1',$idsucursal,PDO::PARAM_INT);
                $stmt->bindParam(':p2',$idvendedor,PDO::PARAM_INT);
                $stmt->bindParam(':p3',$_P['idcliente'],PDO::PARAM_INT);
                $stmt->bindParam(':p4',$_P['idtipovivienda'],PDO::PARAM_INT);
                $stmt->bindParam(':p5',$_P['trabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p6',$_P['dirtrabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p7',$_P['cargo'],PDO::PARAM_STR);
                $stmt->bindParam(':p8',$_P['teltrab'],PDO::PARAM_STR);
                $stmt->bindParam(':p9',$_P['ingresocli'],PDO::PARAM_INT);
                $stmt->bindParam(':p10',$_P['con_trabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p11',$_P['con_dirtrabajo'],PDO::PARAM_STR);
                $stmt->bindParam(':p12',$_P['con_cargo'],PDO::PARAM_STR);
                $stmt->bindParam(':p13',$_P['con_teltrab'],PDO::PARAM_STR);
                $stmt->bindParam(':p14',$_P['ingresocon'],PDO::PARAM_INT);
                $stmt->bindParam(':p15',$_P['fechasolicitud'],PDO::PARAM_STR);
                $stmt->bindParam(':p16',$_P['fechavenc'],PDO::PARAM_STR);
                $stmt->bindParam(':p17',$idproforma,PDO::PARAM_INT);
                $stmt->bindParam(':p18',$estado,PDO::PARAM_INT);
                $stmt->bindParam(':p19',$_P['cargafam'],PDO::PARAM_INT);
                $stmt->bindParam(':p20',$_P['nomgarant'],PDO::PARAM_STR);
                $stmt->bindParam(':p21',$_P['relacion'],PDO::PARAM_STR);
                $stmt->bindParam(':p22',$_P['gar_telefono'],PDO::PARAM_STR);
                $stmt->bindParam(':p23',$_P['obs'],PDO::PARAM_STR);

                $stmt->bindParam(':idsolicitud', $idsolicitud , PDO::PARAM_INT);

            }else
                {

                    $stmt->bindParam(':p1',$idsucursal,PDO::PARAM_INT);
                    $stmt->bindParam(':p2',$idvendedor,PDO::PARAM_INT);
                    $stmt->bindParam(':p3',$_P['idcliente'],PDO::PARAM_INT);
                    $stmt->bindParam(':p4',$_P['idtipovivienda'],PDO::PARAM_INT);
                    $stmt->bindParam(':p5',$_P['trabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p6',$_P['dirtrabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p7',$_P['cargo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p8',$_P['teltrab'],PDO::PARAM_STR);
                    $stmt->bindParam(':p9',$_P['ingresocli'],PDO::PARAM_INT);
                    $stmt->bindParam(':p10',$_P['con_trabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p11',$_P['con_dirtrabajo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p12',$_P['con_cargo'],PDO::PARAM_STR);
                    $stmt->bindParam(':p13',$_P['con_teltrab'],PDO::PARAM_STR);
                    $stmt->bindParam(':p14',$_P['ingresocon'],PDO::PARAM_INT);
                    $stmt->bindParam(':p15',$_P['fechasolicitud'],PDO::PARAM_STR);
                    $stmt->bindParam(':p16',$_P['fechavenc'],PDO::PARAM_STR);
                    $stmt->bindParam(':p17',$idproforma,PDO::PARAM_INT);
                    $stmt->bindParam(':p18',$estado,PDO::PARAM_INT);
                    $stmt->bindParam(':p19',$_P['cargafam'],PDO::PARAM_INT);
                    $stmt->bindParam(':p20',$_P['nomgarant'],PDO::PARAM_STR);
                    $stmt->bindParam(':p21',$_P['relacion'],PDO::PARAM_STR);
                    $stmt->bindParam(':p22',$_P['gar_telefono'],PDO::PARAM_STR);
                    $stmt->bindParam(':p23',$_P['obs'],PDO::PARAM_STR);
                    //echo $_P['nomgarant'];
                    $stmt->bindParam(':idsolicitud', $idsolicitud , PDO::PARAM_INT);

                    $estdo=2;
                    $stmt1 = $this->db->prepare("UPDATE facturacion.proforma
                            set estado = :p1
                        WHERE idproforma = :idproforma");
                    $stmt1->bindParam(':p1', $estdo , PDO::PARAM_STR);                    

                    $stmt1->bindParam(':idproforma', $idproforma , PDO::PARAM_INT);
                    $stmt1->execute();
                    
                }
            
            $stmt->execute();

            $stmt2  = $this->db->prepare("INSERT INTO facturacion.solicituddetalle(
            idsolicitud, idsucursal, idtipopago, preciocash, inicial, 
            nromeses, cuota, idfinanciamiento, producto,cantidad,idproducto)
                VALUES ( :p1, :p2,:p3, :p4,:p5, :p6,:p7, :p8,:p9,:p10,:p11) ");

            foreach($_P['idtipopago'] as $i => $idtipopago)
                {

                    $stmt2->bindParam(':p1',$idsolicitud,PDO::PARAM_INT);
                    $stmt2->bindParam(':p2',$idsucursal,PDO::PARAM_INT);
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
        $stmt = $this->db->prepare("DELETE FROM produccion.produccion WHERE idsolicitud = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function anularver($id)
    {
        // Estado ->0: resgistrado , 1:anulado, 2: Paso a solicitud
        $stmt = $this->db->prepare("UPDATE facturacion.solicitud
                            SET  
                               estado=1
                            WHERE idsolicitud = :p1");
        $stmt->bindParam(':p1', $id , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }



}
?>