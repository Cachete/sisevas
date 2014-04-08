<?php
include_once("Main.php");
class HojaRuta extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
        h.idhojarutas,
        z.descripcion || ' - ' || u.descripcion AS zonas,
        r.descripcion,
        p.nombres || ' ' || p.apellidos AS personal,        
        substr(cast(h.fechareg as text),9,2)||'/'||substr(cast(h.fechareg as text),6,2)||'/'||substr(cast(h.fechareg as text),1,4)        
        FROM
        hojarutas AS h
        INNER JOIN personal AS p ON p.idpersonal = h.idpersonal
        INNER JOIN zona AS z ON z.idzona = h.idzona
        INNER JOIN ubigeo AS u ON u.idubigeo = z.idubigeo
        INNER JOIN rutas AS r ON r.idrutas = h.idrutas         
        INNER JOIN sucursales AS s ON s.idsucursal = h.idsucursal 
        WHERE s.idsucursal = ".$_SESSION['idsucursal'];


        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT
            h.idhojarutas,
            r.descripcion,
            h.idpersonal,
            p.dni,
            p.nombres || ' ' || p.apellidos AS personal,
            z.idubigeo,
            h.idzona,
            substr(cast(h.fechareg as text),9,2)||'/'||substr(cast(h.fechareg as text),6,2)||'/'||substr(cast(h.fechareg as text),1,4) AS fechareg,
            r.idrutas
            FROM
            hojarutas AS h
            INNER JOIN personal AS p ON p.idpersonal = h.idpersonal
            INNER JOIN zona AS z ON z.idzona = h.idzona
            INNER JOIN ubigeo AS u ON u.idubigeo = z.idubigeo
            INNER JOIN rutas AS r ON r.idrutas = h.idrutas
            
            WHERE idhojarutas = :id ");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function getDetails($id)
    {
        $stmt = $this->db->prepare("SELECT
            dh.idcliente,
            dh.idsubproductos_semi,
            dh.producto,
            c.dni,
            c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno as cliente,
            c.direccion,
            c.telefono,
            dh.cantidad,
            dh.observacion

            FROM
            public.hojarutas_detalle AS dh
            INNER JOIN public.cliente AS c ON c.idcliente = dh.idcliente
            LEFT JOIN produccion.subproductos_semi AS sps ON sps.idsubproductos_semi = dh.idsubproductos_semi
            LEFT JOIN produccion.productos_semi AS sp ON sp.idproductos_semi = sps.idproductos_semi
            WHERE dh.idhojarutas = :id    
            ORDER BY dh.idsubproductos_semi ");

        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function insert($_P ) {

        $sql="INSERT INTO hojarutas(
            idrutas, idpersonal, idzona, fechareg, idsucursal) 
                    VALUES(:p1,:p2,:p3,:p4, :p5)" ;

        $stmt = $this->db->prepare($sql);
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $_P['fechareg']=$this->fdate($_P['fechareg'], 'EN');
            $stmt->bindParam(':p1', $_P['idrutas'] , PDO::PARAM_INT);
            $stmt->bindParam(':p2', $_P['idpersonal'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['idzona'] , PDO::PARAM_INT);
            $stmt->bindParam(':p4', $_P['fechareg'], PDO::PARAM_INT);
            $stmt->bindParam(':p5', $_SESSION['idsucursal'], PDO::PARAM_INT);

            $stmt->execute();
            $id =  $this->IdlastInsert('hojarutas','idhojarutas');
            $row = $stmt->fetchAll();

            $stmt2  = $this->db->prepare("INSERT INTO hojarutas_detalle(
            idhojarutas, idcliente, idsubproductos_semi,observacion, cantidad,producto)
                VALUES ( :p1, :p2,:p3, :p4, :p5, :p6) ");

                foreach($_P['idcliente'] as $i => $idcliente)
                {
                    if($idcliente=='')
                    {
                        $sqlcli="INSERT INTO cliente(dni,nombres,apepaterno,
                            apematerno,direccion,telefono)
                            VALUES ( :p1, :p2,:p3, :p4, :p5, :p6) ";

                        $stmt3  = $this->db->prepare($sqlcli);
                        $stmt3->bindParam(':p1', $_P['dnicli'][$i] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p2', $_P['nombres'] [$i], PDO::PARAM_STR);
                        $stmt3->bindParam(':p3', $_P['apepaterno'][$i] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p4', $_P['apematerno'][$i] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p5', $_P['direccion'][$i] , PDO::PARAM_STR);
                        $stmt3->bindParam(':p6', $_P['telefono'] [$i], PDO::PARAM_STR);
                        //$stmt3->bindParam(':p1', $_P['direccion'] , PDO::PARAM_STR);
                        $stmt3->execute();
                        $idcliente =  $this->IdlastInsert('cliente','idcliente');
                        $row = $stmt3->fetchAll();

                        $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);                    
                        $stmt2->bindParam(':p2',$idcliente,PDO::PARAM_INT);
                        $stmt2->bindParam(':p3',$_P['idsubproductos_semi'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p4',$_P['observacion'][$i],PDO::PARAM_STR);
                        $stmt2->bindParam(':p5',$_P['cantidad'][$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p6',$_P['producto'][$i],PDO::PARAM_INT);
                        $stmt2->execute();

                    }else
                        {
                            $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);                    
                            $stmt2->bindParam(':p2',$idcliente,PDO::PARAM_INT);
                            $stmt2->bindParam(':p3',$_P['idsubproductos_semi'][$i],PDO::PARAM_INT);
                            $stmt2->bindParam(':p4',$_P['observacion'][$i],PDO::PARAM_STR);
                            $stmt2->bindParam(':p5',$_P['cantidad'][$i],PDO::PARAM_INT);
                            $stmt2->bindParam(':p6',$_P['producto'][$i],PDO::PARAM_INT);
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

    function update($_P ) {

        $idhojarutas= $_P['idhojarutas'];
        
        $del="DELETE FROM hojarutas_detalle
             WHERE idhojarutas='$idhojarutas' ";
                    
            $res = $this->db->prepare($del);
            $res->execute();
            
        $sql="UPDATE hojarutas 
                set idrutas = :p1, 
                    idpersonal= :p2, 
                    idzona = :p3,
                    fechareg= :p4
                
                WHERE idhojarutas = :idhojarutas";
        $stmt = $this->db->prepare($sql);

        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            $stmt->bindParam(':p1', $_P['idrutas'] , PDO::PARAM_INT);
            $stmt->bindParam(':p2', $_P['idpersonal'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['idzona'] , PDO::PARAM_INT);
            $stmt->bindParam(':p4', $_P['fechareg'] , PDO::PARAM_INT);
           
            $stmt->bindParam(':idhojarutas', $idhojarutas , PDO::PARAM_INT);
            $stmt->execute();
            $id =  $this->IdlastInsert('hojarutas','idhojarutas');
            $row = $stmt->fetchAll();
            
            $stmt2  = $this->db->prepare("INSERT INTO hojarutas_detalle(
            idhojarutas, idcliente, idsubproductos_semi,observacion, cantidad,producto)
                VALUES ( :p1, :p2,:p3, :p4,:p5, :p6) ");

                foreach($_P['idcliente'] as $i => $idcliente)
                {                    
                    $stmt2->bindParam(':p1',$id,PDO::PARAM_INT);                    
                    $stmt2->bindParam(':p2',$idcliente,PDO::PARAM_INT);
                    $stmt2->bindParam(':p3',$_P['idsubproductos_semi'][$i],PDO::PARAM_INT);
                    $stmt2->bindParam(':p4',$_P['observacion'][$i],PDO::PARAM_STR);
                    $stmt2->bindParam(':p5',$_P['cantidad'][$i],PDO::PARAM_INT);
                    $stmt2->bindParam(':p6',$_P['producto'][$i],PDO::PARAM_INT);

                    $stmt2->execute();             

                }

            $this->db->commit();            
            return array('1','Bien!',$idhojarutas);

        }
        catch(PDOException $e) 
            {
                $this->db->rollBack();
                return array('2',$e->getMessage().$str,'');
            } 
        
    }
    
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM hojarutas WHERE idhojarutas = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
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
                h.idhojarutas,
                z.descripcion AS zona,
                r.descripcion AS ruta,
                substr(cast(h.fechareg as text),9,2)||'/'||substr(cast(h.fechareg as text),6,2)||'/'||substr(cast(h.fechareg as text),1,4) AS fechareg,
                p.nombres || ' ' || p.apellidos AS nompersonal
                FROM
                hojarutas AS h
                INNER JOIN personal AS p ON p.idpersonal = h.idpersonal
                INNER JOIN zona AS z ON z.idzona = h.idzona
                INNER JOIN rutas AS r ON r.idrutas = h.idrutas 
                WHERE
                h.fechareg BETWEEN CAST(:p1 AS DATE) AND CAST(:p2 AS DATE)
                ORDER BY z.idzona ASC ";

            $stmt = $this->db->prepare($sql);
            //$stmt->bindParam(':id', $idpersonal , PDO::PARAM_INT);
            $stmt->bindParam(':p1', $fechai , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $fechaf, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetchAll();

        }else
            {
                $sql="SELECT
                    h.idhojarutas,
                    z.descripcion AS zona,
                    r.descripcion AS ruta,
                    substr(cast(h.fechareg as text),9,2)||'/'||substr(cast(h.fechareg as text),6,2)||'/'||substr(cast(h.fechareg as text),1,4) AS fechareg,
                    p.nombres || ' ' || p.apellidos AS nompersonal
                    FROM
                    hojarutas AS h
                    INNER JOIN personal AS p ON p.idpersonal = h.idpersonal
                    INNER JOIN zona AS z ON z.idzona = h.idzona
                    INNER JOIN rutas AS r ON r.idrutas = h.idrutas 
                    WHERE
                    h.fechareg BETWEEN CAST(:p1 AS DATE) AND CAST(:p2 AS DATE) AND h.idpersonal = :id  
                    ORDER BY z.idzona ASC ";

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
            dh.idcliente,
            dh.idsubproductos_semi,
            dh.producto,
            c.dni,
            c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno as cliente,
            c.direccion,
            c.telefono,
            dh.cantidad,
            dh.observacion

            FROM
            public.hojarutas_detalle AS dh
            INNER JOIN public.cliente AS c ON c.idcliente = dh.idcliente
            LEFT JOIN produccion.subproductos_semi AS sps ON sps.idsubproductos_semi = dh.idsubproductos_semi
            LEFT JOIN produccion.productos_semi AS sp ON sp.idproductos_semi = sps.idproductos_semi
            WHERE dh.idhojarutas = :id    
            ORDER BY dh.idsubproductos_semi ");

        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>