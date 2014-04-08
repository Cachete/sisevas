<?php
include_once("Main.php");
class SubProductoSemi extends Main
{
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
            p.idsubproductos_semi,
            ps.descripcion,
            p.descripcion,
            p.precio,            
            case p.estado when 1 then 'ACTIVO' else 'INCANTIVO' end            

            FROM
            produccion.subproductos_semi AS p
            INNER JOIN produccion.productos_semi AS ps ON ps.idproductos_semi = p.idproductos_semi "; 

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM produccion.subproductos_semi WHERE idsubproductos_semi = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function insert($_P ) {
        $stmt = $this->db->prepare("INSERT INTO produccion.subproductos_semi 
                        (descripcion,idproductos_semi, estado, precio,idunidad_medida,factor, observacion) 
                        VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7)");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idproductos_semi'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['precio'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['idunidad_medida'] , PDO::PARAM_INT);
        $stmt->bindParam(':p6', $_P['factor'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['obs'] , PDO::PARAM_STR);
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function update($_P ) {
        $stmt = $this->db->prepare("UPDATE produccion.subproductos_semi 
                set descripcion = :p1,
                    idproductos_semi = :p2,
                    estado= :p3,
                    precio= :p4,
                    idunidad_medida= :p5,
                    factor= :p6,
                    observacion= :p7

                WHERE idsubproductos_semi = :idsubproductos_semi ");
        //print_r($stmt);
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idproductos_semi'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['activo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p4', $_P['precio'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['idunidad_medida'] , PDO::PARAM_INT);
        $stmt->bindParam(':p6', $_P['factor'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['obs'] , PDO::PARAM_STR);
        
        $stmt->bindParam(':idsubproductos_semi', $_P['idsubproductos_semi'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM produccion.subproductos_semi WHERE idsubproductos_semi = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function getList($idl=null)
    {
        $sql = "SELECT idsubproductos_semi, descripcion from produccion.subproductos_semi ";
        if($idl!=null)
        {
            $sql .= " WHERE idproductos_semi = {$idl} AND idsubproductos_semi <> 1 ";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll() as $r)
        {
            $data[] = array('idsubproductos_semi'=>$r[0],'descripcion'=>$r[1]);
        }
        return $data;
    }

    function get($query,$field)
    {
        $query = "%".$query."%";
        $statement = $this->db->prepare("SELECT
                                            sps.idsubproductos_semi,
                                            ps.descripcion || ' ' || sps.descripcion AS producto,
                                            sps.precio
                                            FROM
                                            produccion.productos_semi AS ps
                                            INNER JOIN produccion.subproductos_semi AS sps ON ps.idproductos_semi = sps.idproductos_semi
                                            
                                            WHERE {$field} ilike :query and ps.descripcion || ' ' || sps.descripcion <> ''
                                                    AND sps.idsubproductos_semi <> 1
                                            limit 10");
        $statement->bindParam (":query", $query , PDO::PARAM_STR);
        $statement->execute();
        //print_r($statement);
        return $statement->fetchAll();
    }

    function stock($idalmacenp,$idsps)
    {        
        $stmt4 = $this->db->prepare("SELECT t.idp,t.c,t.precio
                                    from (
                                    SELECT max(pd.idproduccion) as idp ,pd.ctotal as c, pd.item, spd.precio
                                    FROM produccion.produccion_detalle as pd
                                    inner join produccion.subproductos_semi as spd on spd.idsubproductos_semi = pd.idsubproductos_semi
                                    where pd.idalmacen = :idal and pd.idsubproductos_semi = :idsps
                                    group by pd.ctotal,pd.item,pd.idproduccion, spd.precio
                                    order by idproduccion desc
                                    limit 1
                                    ) as t
                                    order by t.item,t.c");
        $stmt4->bindParam(':idal',$idalmacenp,PDO::PARAM_INT);
        $stmt4->bindParam(':idsps',$idsps,PDO::PARAM_INT);
        $stmt4->execute();
        $row4 = $stmt4->fetchObject();   
        $n = $stmt4->rowCount();
        if($n>0)        
        {
            return array('stk'=>$row4->c,'price'=>$row4->precio);    
        }
        else 
        {
            $stmt = $this->db->prepare("SELECT precio from produccion.subproductos_semi 
                                        where idsubproductos_semi = :idsps");
            $stmt->bindParam(':idsps',$idsps,PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetchObject();   
            return array('stk'=>'0','price'=>$row->precio);
        }
        
    }
	
	function ViewResultado($id)
    {
                   
            $where='';
            //echo $id;
            if($id!=0)
            {
                $where=" WHERE idalmacen = '$id' ";                
            }

            $sqlal="SELECT idalmacen, descripcion from produccion.almacenes ".$where;
            $stmt = $this->db->prepare($sqlal);
            $stmt->execute();
            //return $stmt->fetchAll();
            $data= array();

            foreach ($stmt->fetchAll() as $f)
            {

                $sql="SELECT
                    a.descripcion AS almacen,
                    ps.descripcion || ' ' || sps.descripcion AS producto,
                    t2.ctotal
                    from 
                    produccion.produccion_detalle as t2 INNER JOIN (
                    SELECT max(pd.idproduccion_detalle) as iddd,pd.idsubproductos_semi, pd.idalmacen 
                    FROM produccion.produccion_detalle as pd
                    GROUP BY pd.idsubproductos_semi,pd.idalmacen ) as t3 on t2.idproduccion_detalle = t3.iddd

                    INNER JOIN produccion.subproductos_semi AS sps ON sps.idsubproductos_semi = t2.idsubproductos_semi
                    INNER JOIN produccion.productos_semi AS ps ON ps.idproductos_semi = sps.idproductos_semi
                    INNER JOIN produccion.produccion AS p ON p.idproduccion = t2.idproduccion
                    INNER JOIN produccion.almacenes AS a ON a.idalmacen = p.idalmacen 
                    WHERE
                    p.idalmacen = ". $f['idalmacen'];
                $stmt2 = $this->db->prepare($sql);                
                $stmt2->execute();
                
                $data[]= array(
                    'almacen' =>$f['descripcion'],
                    'detalle' =>$stmt2->fetchAll()
                    );
            }
            
            return $data;   

    }

}
?>