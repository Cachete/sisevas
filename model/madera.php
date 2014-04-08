<?php
include_once("Main.php");
class Madera extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT  p.idproducto,
                    p.descripcion,
                    u.simbolo,
                    p.precio_u,
                    p.stock,
                    case p.estado when 1 then 'ACTIVO' else 'INACTIVO' end ,
                    p.idtipoproducto,
                    p.idunidad_medida
                from produccion.producto as p 
                    inner join produccion.maderba as m on p.idmaderba = m.idmaderba
                    inner join unidad_medida as u on u.idunidad_medida = p.idunidad_medida
                where p.idtipoproducto = 1  ";
        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }

    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM produccion.producto WHERE idproducto = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {
        $tipopro=1;
        $idmaderb= 1;
        $stmt = $this->db->prepare("INSERT into produccion.producto(tipoproducto,descripcion,idmaderba,
                                    precio_u,idunidad_medida,stock,estado)
                                    values(:p1,:p2,:p3,:p5,:p6,:p7)");
              
        $stmt->bindParam(':p1', $tipopro , PDO::PARAM_INT);
        $stmt->bindParam(':p2', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $idmaderb , PDO::PARAM_INT);        
        $stmt->bindParam(':p4', $_P['precio_u'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['idunidad_medida'] , PDO::PARAM_INT);
        $stmt->bindParam(':p6', $_P['stock'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['activo'] , PDO::PARAM_INT);
        
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        
        return array($p1 , $p2[2]);
        
    }
    function update($_P ) 
    {
        $sql = "UPDATE produccion.producto 
                    set     
                        descripcion=:p2,
                        precio_u=:p3,
                        idunidad_medida=:p5,
                        stock=:p6,
                        estado=:p7

                WHERE idproducto = :idproducto";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':p2', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p3', $_P['precio_u'] , PDO::PARAM_INT);        
        $stmt->bindParam(':p5', $_P['idunidad_medida'] , PDO::PARAM_INT);
        $stmt->bindParam(':p6', $_P['stock'] , PDO::PARAM_INT);
        $stmt->bindParam(':p7', $_P['activo'] , PDO::PARAM_INT);

        $stmt->bindParam(':idproducto', $_P['idproducto'] , PDO::PARAM_INT);            
            
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM produccion.producto WHERE idproducto = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function getPrice($id)
    {
        $stmt = $this->db->prepare("SELECT precio_u from produccion.producto WHERE idproducto = :p1");
        $stmt->bindParam(':p1', $id, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetchObject();
        return $r->precio_u;
    }

    function getStock($id,$a)
    {
        $sql = "SELECT t.idm,t.c,t.item
                from (
                SELECT max(idmovimiento) as idm ,ctotal_current as c, item
                FROM movimientosdetalle
                where idtipoproducto = 1 and idproducto = :idp and idalmacen = :ida 
                group by ctotal_current,item,idmovimiento
                order by idmovimiento desc
                limit 1) as t
                order by t.item desc";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idp',$id,PDO::PARAM_INT);
        $stmt->bindParam(':ida',$a,PDO::PARAM_INT); 
        $stmt->execute();
        $row = $stmt->fetchObject();
        if($row->c>0)
            return $row->c;
        else return '0.000';
    }
}
?>