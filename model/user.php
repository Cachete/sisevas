<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Main.php");
class User extends Main {
    function Start() 
    {
        $statement = $this->db->prepare("SELECT personal.dni,
                                personal.idperfil,
                                personal.nombres||' '||personal.apellidos as nombres,
                                p.descripcion as perfil,
                                personal.usuario as login,
                                c.descripcion as oficina,
                                c.idconsultorio as idoficina,
                                s.descripcion as sede,
                                s.idsede as idsucursal,
                                '' as turno,
                                personal.idpersonal
                            FROM personal 
                                inner join seguridad.perfil as p on personal.idperfil = p.idperfil 
                                inner join consultorio as c on c.idconsultorio = personal.idarea
                                inner join seguridad.sedes as s on s.idsede = c.idsede 
                            WHERE personal.usuario = :user AND personal.clave = :password ");
        $statement->bindParam (":user", $_POST['usuario'] , PDO::PARAM_STR);
        $statement->bindParam (":password", $_POST['password'] , PDO::PARAM_STR);
        
        $statement->execute();                
        $obj = $statement->fetchObject();
        return array('flag'=>$statement->rowCount() , 'obj'=>$obj );
    }
    function getUser($id)
    {
        $stmt = $this->db->prepare("select idempleado,concat(empleado.nombres,' ',empleado.apellidos) as nombres 
                                    from empleado where idempleado = ? ");
        $stmt->bindParam(1,$id,PDO::PARAM_STR);
        $stmt->execute();
        $obj = $stmt->fetchObject();
        return $obj;
        
    }
    function VeriUser($pw,$id)
    {
        if($id==$_SESSION['idempleado'])
        {
            $stmt = $this->db->prepare("select count(idempleado) as num from empleado where idempleado = ? and clave = ? ");
            $stmt->bindParam(1,$id,PDO::PARAM_STR);
            $stmt->bindParam(2,$pw,PDO::PARAM_STR);
            $stmt->execute();
            $obj = $stmt->fetchObject();
            return $obj->num;
        }
        else {
            return 0;
        }
    }
    function save_change_passw($pw,$id)
    {
       if($id==$_SESSION['idempleado'])
        {
           $stmt = $this->db->prepare("update empleado set clave = ? where idempleado = ?");
           $stmt->bindParam(1,$pw,PDO::PARAM_STR);
           $stmt->bindParam(2,$id,PDO::PARAM_STR);
           $r = $stmt->execute();
           return $r;
        } 
        else 
        {
           return false;
        }
    }
    
    function index($query , $p ,$c) {
        $sql = "SELECT empleado.idempleado,                        
                       concat(empleado.nombre,' ',empleado.apellidos) as nombres,
                       empleado.aleas,
                       concat(o.descripcion,' (',s.descripcion,') '),
                       case empleado.estado when 1 then '<p style=\"color:green\">ACTIVO</p>' else '<p style=\"color:red\">INACTIVO</p>' end                       
                    FROM empleado inner join perfil on empleado.idperfil = perfil.idperfil
                            inner join tipo_empleado as te on te.idtipo_empleado = empleado.idtipo_empleado
                            inner join oficina as o on o.idoficina = empleado.idoficina
                            inner join sucursal as s on s.idsucursal = o.idsucursal
             where {$c} like :query
             order by empleado.nombre, empleado.apellidos";
             
        $param = array(array('key'=>':query' , 'value'=>"%$query%" , 'type'=>'STR' ));
        $data['total'] = $this->getTotal( $sql, $param );
        $data['rows'] =  $this->getRow($sql, $param , $p );
        $data['rowspag'] =  $this->getRowPag($data['total'], $p );
        return $data;
    }
    
    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM empleado WHERE idempleado = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    function insert($_P ) 
    {

        $stmt = $this->db->prepare('insert into empleado (  idempleado,
                                                            idtipo_empleado,
                                                            idoficina,                                                            
                                                            idperfil,
                                                            nombre,
                                                            apellidos,
                                                            aleas,
                                                            ruc,
                                                            fecha_nacimiento,
                                                            estado,
                                                            login,
                                                            password,
                                                            celular,
                                                            fecha_registro)
                                    values(:p1,:p2,:p3,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15)');
        $stmt->bindParam(':p1', $_P['idempleado'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idtipo_empleado'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idoficina'] , PDO::PARAM_INT);
        //$stmt->bindParam(':p4', $_P['idgrupo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['idperfil'] , PDO::PARAM_INT);
        $stmt->bindParam(':p6', $_P['nombre'] , PDO::PARAM_STR);
        $stmt->bindParam(':p7', $_P['apellidos'] , PDO::PARAM_STR);
        $stmt->bindParam(':p8', $_P['aleas'] , PDO::PARAM_STR);
        $stmt->bindParam(':p9', $_P['ruc'] , PDO::PARAM_STR);        
        $stmt->bindParam(':p10', $this->fdate($_P['fecha_nacimiento'],'EN') , PDO::PARAM_STR);
        $stmt->bindParam(':p11', $_P['activo'] , PDO::PARAM_BOOL);
        $stmt->bindParam(':p12', $_P['login'] , PDO::PARAM_STR);
        $stmt->bindParam(':p13', $_P['password'] , PDO::PARAM_STR);
        $stmt->bindParam(':p14', $_P['celular'] , PDO::PARAM_STR);
        $stmt->bindParam(':p15', $this->fdate($_P['fecha_registro'],'EN') , PDO::PARAM_STR);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    function update($_P ) 
    {        
        $stmt = $this->db->prepare('UPDATE empleado
                                   SET  idtipo_empleado=:p2,
                                        idoficina=:p3,
                                        
                                        idperfil=:p5,
                                        nombre=:p6,
                                        apellidos=:p7,
                                        aleas=:p8,
                                        ruc=:p9,
                                        fecha_nacimiento=:p10,
                                        estado=:p11,
                                        login=:p12,
                                        password=:p13,
                                        celular=:p14,
                                        fecha_registro=:p15
                                 WHERE idempleado = :p1');       
        $stmt->bindParam(':p1', $_P['idempleado'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idtipo_empleado'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['idoficina'] , PDO::PARAM_INT);
        //$stmt->bindParam(':p4', $_P['idgrupo'] , PDO::PARAM_INT);
        $stmt->bindParam(':p5', $_P['idperfil'] , PDO::PARAM_INT);
        $stmt->bindParam(':p6', $_P['nombre'] , PDO::PARAM_STR);
        $stmt->bindParam(':p7', $_P['apellidos'] , PDO::PARAM_STR);
        $stmt->bindParam(':p8', $_P['aleas'] , PDO::PARAM_STR);
        $stmt->bindParam(':p9', $_P['ruc'] , PDO::PARAM_STR);        
        $stmt->bindParam(':p10', $this->fdate($_P['fecha_nacimiento'],'EN') , PDO::PARAM_STR);
        $stmt->bindParam(':p11', $_P['activo'] , PDO::PARAM_BOOL);
        $stmt->bindParam(':p12', $_P['login'] , PDO::PARAM_STR);
        $stmt->bindParam(':p13', $_P['password'] , PDO::PARAM_STR);
        $stmt->bindParam(':p14', $_P['celular'] , PDO::PARAM_STR);
        $stmt->bindParam(':p15', $this->fdate($_P['fecha_registro'],'EN') , PDO::PARAM_STR);

        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
//    function delete($_P ) {
//        $stmt = $this->db->prepare("DELETE FROM perfil WHERE idperfil = :p1");
//        $stmt->bindParam(':p1', $_P['idperfil'] , PDO::PARAM_STR);
//        $p1 = $stmt->execute();
//        $p2 = $stmt->errorInfo();
//        return array($p1 , $p2[2]);
//    }
    function getchofer($query,$field)
    {
        $query = "%".$query."%";
        $statement = $this->db->prepare("SELECT idempleado, 
                                                concat(nombre,' ',apellidos) as nombre,
                                                aleas
                                         FROM empleado
                                         WHERE {$field} like :query and idtipo_empleado = 2
                                         limit 10");
        $statement->bindParam (":query", $query , PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
?>