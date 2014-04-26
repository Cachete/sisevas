<?php
include_once("Main.php");
class Pacientes extends Main
{    
    function indexGrid($page,$limit,$sidx,$sord,$filtro,$query,$cols)
    {
        $sql = "SELECT
        p.idpaciente,
        p.nrodocumento,
        p.nombres||' '||p.appat||' '||p.apmat,
        p.direccion,
        p.telefono,
        p.centtrabajo,
        o.descripcion AS ocupacion,
        u.descripcion AS origen
        FROM
        public.paciente AS p
        LEFT JOIN public.ubigeo AS u ON u.idubigeo = p.idubigeo
        LEFT JOIN public.ocupacion AS o ON o.idocupacion = p.idocupacion
        LEFT JOIN public.estado_civil AS e ON e.idestado_civil = p.idestado_civil ";

        return $this->execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql);
    }
    
    function edit($id)
    {   
        $sql="SELECT
            p.idpaciente,
            p.nrodocumento,
            p.nombres,
            p.appat,
            p.apmat,
            p.fechanac,
            p.sexo,
            p.direccion,
            p.email,
            td.descripcion,
            gs.descripcion,
            e.descripcion,
            p.nrohijos,
            p.centtrabajo,
            u.descripcion,
            p.sector,
            p.estado,
            p.idtipo_documento,
            p.idgrupo_sanguineo,
            p.idestado_civil,
            p.idocupacion,
            p.idgradinstruccion,
            o.descripcion,
            gi.descripcion,
            p.asegurado,
            p.foto,
            p.idubigeo,
            p.nrohistoria_clinica,
            p.fechareg_hc,
            p.idtipo_llegada,
            p.usereg,
            p.telefono,
            p.celular
        FROM
            public.paciente AS p
            INNER JOIN public.documento_identidad AS td ON td.iddocumento_identidad = p.idtipo_documento
            INNER JOIN public.ubigeo AS u ON u.idubigeo = p.idubigeo
            INNER JOIN public.grupo_sanguineo AS gs ON gs.idgrupo_sanguineo = p.idgrupo_sanguineo
            INNER JOIN public.ocupacion AS o ON o.idocupacion = p.idocupacion
            INNER JOIN public.grado_instruccion AS gi ON gi.idgradinstruccion = p.idgradinstruccion
            INNER JOIN public.estado_civil AS e ON e.idestado_civil = p.idestado_civil
        
        WHERE p.idpaciente = :id ";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    
    function insert($_P ) 
    {        
        $idconyugue=$_P['idpareja'];
        
        
            $idconyugue=0;
            $stmt = $this->db->prepare("INSERT INTO cliente(
            dni, idtipocliente, apepaterno, apematerno, nombres, fechanac, idubigeo, direccion, sector, 
            telefono, idestado_civil, ocupacion, profesion, trabajo, dirtrabajo, cargo, antitrab, teltrab, 
            ingreso, idtipovivienda, rlegal, nropartida, idconyugue, sexo, estado,idgradinstruccion,carga_familiar,referencia_ubic)
            values(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,
                    :p18,:p19,:p20,:p21,:p22,:p23,:p24,:p25,:p26,:p27,:p28)");
     
            $stmt->bindParam(':p1', $_P['dni'] , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $_P['idtipocliente'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['apepaterno'] , PDO::PARAM_STR);        
            $stmt->bindParam(':p4', $_P['apematerno'] , PDO::PARAM_STR);
            $stmt->bindParam(':p5', $_P['nombres'] , PDO::PARAM_STR);
            $stmt->bindParam(':p6', $_P['fechanac'] , PDO::PARAM_STR);
            $stmt->bindParam(':p7', $_P['iddistrito'] , PDO::PARAM_STR);
            $stmt->bindParam(':p8', $_P['direccion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p9', $_P['sector'] , PDO::PARAM_STR);
            $stmt->bindParam(':p10', $_P['telefono'] , PDO::PARAM_STR);
            $stmt->bindParam(':p11', $_P['idestado_civil'] , PDO::PARAM_STR);
            $stmt->bindParam(':p12', $_P['ocupacion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p13', $_P['profesion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p14', $_P['trabajo'] , PDO::PARAM_STR);        
            $stmt->bindParam(':p15', $_P['dirtrabajo'] , PDO::PARAM_STR);
            $stmt->bindParam(':p16', $_P['cargo'] , PDO::PARAM_STR);
            $stmt->bindParam(':p17', $_P['antitrab'] , PDO::PARAM_STR);
            $stmt->bindParam(':p18', $_P['teltrab'] , PDO::PARAM_STR);
            $stmt->bindParam(':p19', $_P['ingreso'] , PDO::PARAM_INT);
            $stmt->bindParam(':p20', $_P['idtipovivienda'] , PDO::PARAM_INT);
            $stmt->bindParam(':p21', $_P['rlegal'] , PDO::PARAM_STR);
            $stmt->bindParam(':p22', $_P['nropartida'] , PDO::PARAM_INT);
            $stmt->bindParam(':p23', $idconyugue, PDO::PARAM_INT);
            $stmt->bindParam(':p24', $_P['sexo'] , PDO::PARAM_STR);
            $stmt->bindParam(':p25', $_P['activo'] , PDO::PARAM_INT);
            $stmt->bindParam(':p26', $_P['idgradinstruccion'] , PDO::PARAM_INT);
            $stmt->bindParam(':p27', $_P['carga_familiar'] , PDO::PARAM_STR);
            $stmt->bindParam(':p28', $_P['referencia_ubic'] , PDO::PARAM_STR);       

            $p1 = $stmt->execute();
            $p2 = $stmt->errorInfo();
            return array($p1 , $p2[2]);
    }
    
    function update($_P ) 
    {
        $idconyugue=$_P['idpareja'];
        //echo $idconyugue;
        if($idconyugue=='')
        {
            $idconyugue=0;
            $sql = "UPDATE cliente
                SET 
                    dni=:p1, idtipocliente=:p2, 
                    apepaterno=:p3, apematerno=:p4, 
                    nombres=:p5, fechanac=:p6, 
                    idubigeo=:p7, direccion=:p8, 
                    sector=:p9, telefono=:p10, 
                    idestado_civil=:p11, ocupacion=:p12, 
                    profesion=:p13, trabajo=:p14, dirtrabajo=:p15, 
                    cargo=:p16, antitrab=:p17, teltrab=:p18, 
                    ingreso=:p19, idtipovivienda=:p20, 
                    rlegal=:p21, nropartida=:p22, 
                    idconyugue=:p23, sexo=:p24, 
                    estado=:p25,idgradinstruccion=:p26,
                    carga_familiar=:p27,referencia_ubic=:p28
                
                WHERE   idcliente = :idcliente ";
        
            $stmt = $this->db->prepare($sql);
                
            $stmt->bindParam(':p1', $_P['dni'] , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $_P['idtipocliente'] , PDO::PARAM_INT);
            $stmt->bindParam(':p3', $_P['apepaterno'] , PDO::PARAM_STR);        
            $stmt->bindParam(':p4', $_P['apematerno'] , PDO::PARAM_STR);
            $stmt->bindParam(':p5', $_P['nombres'] , PDO::PARAM_STR);
            $stmt->bindParam(':p6', $_P['fechanac'] , PDO::PARAM_STR);
            $stmt->bindParam(':p7', $_P['iddistrito'] , PDO::PARAM_STR);
            $stmt->bindParam(':p8', $_P['direccion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p9', $_P['sector'] , PDO::PARAM_STR);
            $stmt->bindParam(':p10', $_P['telefono'] , PDO::PARAM_STR);
            $stmt->bindParam(':p11', $_P['idestado_civil'] , PDO::PARAM_STR);
            $stmt->bindParam(':p12', $_P['ocupacion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p13', $_P['profesion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p14', $_P['trabajo'] , PDO::PARAM_STR);        
            $stmt->bindParam(':p15', $_P['dirtrabajo'] , PDO::PARAM_STR);
            $stmt->bindParam(':p16', $_P['cargo'] , PDO::PARAM_STR);
            $stmt->bindParam(':p17', $_P['antitrab'] , PDO::PARAM_STR);
            $stmt->bindParam(':p18', $_P['teltrab'] , PDO::PARAM_STR);
            $stmt->bindParam(':p19', $_P['ingreso'] , PDO::PARAM_INT);
            $stmt->bindParam(':p20', $_P['idtipovivienda'] , PDO::PARAM_INT);
            $stmt->bindParam(':p21', $_P['rlegal'] , PDO::PARAM_STR);
            $stmt->bindParam(':p22', $_P['nropartida'] , PDO::PARAM_INT);
            $stmt->bindParam(':p23', $idconyugue, PDO::PARAM_INT);
            $stmt->bindParam(':p24', $_P['sexo'] , PDO::PARAM_STR);
            $stmt->bindParam(':p25', $_P['activo'] , PDO::PARAM_INT);
            $stmt->bindParam(':p26', $_P['idgradinstruccion'] , PDO::PARAM_INT);
            $stmt->bindParam(':p27', $_P['carga_familiar'] , PDO::PARAM_STR);
            $stmt->bindParam(':p28', $_P['referencia_ubic'] , PDO::PARAM_STR);

            $stmt->bindParam(':idcliente', $_P['idcliente'] , PDO::PARAM_INT);
            
        }else
            {
                $sql = "UPDATE cliente
                SET 
                    dni=:p1, idtipocliente=:p2, 
                    apepaterno=:p3, apematerno=:p4, 
                    nombres=:p5, fechanac=:p6, 
                    idubigeo=:p7, direccion=:p8, 
                    sector=:p9, telefono=:p10, 
                    idestado_civil=:p11, ocupacion=:p12, 
                    profesion=:p13, trabajo=:p14, dirtrabajo=:p15, 
                    cargo=:p16, antitrab=:p17, teltrab=:p18, 
                    ingreso=:p19, idtipovivienda=:p20, 
                    rlegal=:p21, nropartida=:p22, 
                    idconyugue=:p23, sexo=:p24, 
                    estado=:p25,idgradinstruccion=:p26,
                    carga_familiar=:p27,referencia_ubic=:p28
                
                WHERE   idcliente = :idcliente ";
        
                $stmt = $this->db->prepare($sql);

                    $stmt->bindParam(':p1', $_P['dni'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p2', $_P['idtipocliente'] , PDO::PARAM_INT);
                    $stmt->bindParam(':p3', $_P['apepaterno'] , PDO::PARAM_STR);        
                    $stmt->bindParam(':p4', $_P['apematerno'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p5', $_P['nombres'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p6', $_P['fechanac'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p7', $_P['iddistrito'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p8', $_P['direccion'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p9', $_P['sector'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p10', $_P['telefono'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p11', $_P['idestado_civil'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p12', $_P['ocupacion'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p13', $_P['profesion'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p14', $_P['trabajo'] , PDO::PARAM_STR);        
                    $stmt->bindParam(':p15', $_P['dirtrabajo'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p16', $_P['cargo'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p17', $_P['antitrab'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p18', $_P['teltrab'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p19', $_P['ingreso'] , PDO::PARAM_INT);
                    $stmt->bindParam(':p20', $_P['idtipovivienda'] , PDO::PARAM_INT);
                    $stmt->bindParam(':p21', $_P['rlegal'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p22', $_P['nropartida'] , PDO::PARAM_INT);
                    $stmt->bindParam(':p23', $idconyugue, PDO::PARAM_INT);
                    $stmt->bindParam(':p24', $_P['sexo'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p25', $_P['activo'] , PDO::PARAM_INT);
                    $stmt->bindParam(':p26', $_P['idgradinstruccion'] , PDO::PARAM_INT);
                    $stmt->bindParam(':p27', $_P['carga_familiar'] , PDO::PARAM_STR);
                    $stmt->bindParam(':p28', $_P['referencia_ubic'] , PDO::PARAM_STR);

                    $stmt->bindParam(':idcliente', $_P['idcliente'] , PDO::PARAM_INT);
            }
        
        $p1 = $stmt->execute();
        //print_r($p1);
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    
    function delete($p) 
    {
        $stmt = $this->db->prepare("DELETE FROM cliente WHERE idcliente = :p1");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }

    function get($query,$field)
    {
        $query = "%".$query."%";
        $sql="SELECT
            p.idpaciente,
            p.nrodocumento,
            p.nombres,
            p.appat,
            p.apmat,
            p.nombres || ' ' || p.appat || ' ' || p.apmat AS nompaciente,
            p.direccion,
            p.telefono,
            p.celular
            FROM
            paciente AS p

            WHERE {$field} ilike :query and p.nrodocumento <> ''
            limit 10";
            //echo $sql;
        $statement = $this->db->prepare($sql);

        $statement->bindParam (":query", $query , PDO::PARAM_STR);
        $statement->execute();
        //print_r($statement);
        return $statement->fetchAll();
    }

    function getProf($query,$field)
    {
        $query = "%".$query."%";
        $sql="SELECT
            c.idcliente,
            c.dni,
            c.idtipocliente,
            c.nombres,
            c.apematerno,
            c.apepaterno,
            c.nombres || ' ' || c.apepaterno || ' ' || c.apematerno AS nomcliente,
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
            c.carga_familiar,
            c.ingreso,
            c.idconyugue,
            con.dni AS con_dni,
            con.nombres || ' ' || con.apepaterno || ' ' || con.apematerno AS nomconyugue,
            con.ocupacion AS con_ocupacion,
            con.trabajo AS con_trabajo,
            con.dirtrabajo AS con_dirtrabajo,
            con.cargo AS con_cargo,
            con.ingreso AS con_ingreso,
            con.teltrab AS con_teltrab,
            p.idproforma

            FROM
            cliente AS c
            LEFT JOIN cliente AS con ON con.idcliente = c.idconyugue
            INNER JOIN facturacion.proforma AS p ON c.idcliente = p.idcliente

            WHERE {$field} ilike :query and c.dni <> '' and p.estado=0
            limit 10";
            //echo $sql;
        $statement = $this->db->prepare($sql);

        $statement->bindParam (":query", $query , PDO::PARAM_STR);
        $statement->execute();
        //print_r($statement);
        return $statement->fetchAll();
    }
    
}

?>