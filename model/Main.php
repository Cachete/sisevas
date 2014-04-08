<?php
require_once '../lib/Spdo.php';
date_default_timezone_set('America/Lima');
class Main extends Spdo {
    protected $db;
    public $rows = 3;
    protected $pag = 5;
    protected $exec;
    public $filtros;

    public function __construct()
    {
        $this->db = Spdo::singleton();
        $this->db->query('SET NAMES UTF8');
    }
    public function IdlastInsert($table,$id)
    {
        $stmt = $this->db->prepare("select max(".$id.") as codigo from ".$table);
        $stmt->execute();
        $r = $stmt->fetchObject();
        return $r->codigo;
    }
    public function execQuery($page,$limit,$sidx,$sord,$filtro,$query,$cols,$sql)
    {
        $offset = ($page-1)*$limit;
        $query = "%".ltrim($query)."%";

        $to = 0;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $c = $stmt->rowCount();    
        $to = ceil($c/$limit);        

        if($filtro!="") 
        {
            if(stripos($sql,"where ")!==false||stripos($sql,"WHERE ")!==false) $sql .= " and ";
            else $sql .= " WHERE ";
            $sql .= " cast(".$filtro." as varchar) ilike :query ";
        }
        $sql .= " order by {$sidx} {$sord}
                 limit {$limit}
                 offset  {$offset} ";
        //echo $sql;
        $stmt = $this->db->prepare($sql);
        if($filtro!="") $stmt->bindParam(':query',$query,PDO::PARAM_STR);
        $stmt->execute();

        $responce = new stdClass();
        $responce->records = $stmt->rowCount();
        $responce->page = $page;
        if($to==0) $responce->total = "1";
            else $responce->total = $to;

        $i = 0;

        $cont = count($cols);
        foreach($stmt->fetchAll() as $i => $row)
        {
            $responce->rows[$i]['id']=$row[0];
            for($j=0;$j<$cont;$j++)
            {
                $responce->rows[$i]['cell'][] = $row[$j];
            }
            $i ++;
        }
        return $responce;
    }
    function getEstado($tabla,$campo,$id)
    {
        $sql = "SELECT estado from {$tabla} where {$campo} = {$id}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $r = $stmt->fetchObject();
        return $r->estado;
    }

    function more_options($name_controller)
    {
        $sql = "select idpadre from modulo where controlador = :name ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name_controller , PDO::PARAM_STR);
        $stmt->execute();
        $r = $stmt->fetchObject();
        $idpadre = $r->idpadre;
        
        $sql = "select  
                        m.descripcion AS descripcion,
                        m.url AS url,        
                        m.controlador AS controlador,
                        m.accion AS accion
                from    (modulo m join permiso p on((m.idmodulo = p.idmodulo))) 
                where ((m.estado = 1) and (p.acceder = 1) and (m.backend = 1)) 
                and idpadre = {$idpadre}  and p.idperfil = ".$_SESSION['id_perfil']." 
                order by m.descripcion";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();        
        return $stmt->fetchAll();
    }
    function getList() {

        if(count($this->filtros)>0)
        {
            $sql = "SELECT * FROM {$this->table} ";
            $c = 0;
            foreach($this->filtros as $f => $v)
                {

                    if($c==0){ $sql .= " WHERE cast({$f} as nchar) like '%{$v}%'"; }
                        else { $sql .= " AND cast({$f} as nchar) like '%{$v}%'"; }
                    $c ++;
                }              
            $sql .= " order by 1 ";
            $sth = $this->db->prepare($sql);
        }
        else 
        {
            $sth = $this->db->prepare("SELECT * FROM {$this->table} order by 1");
        }
        $sth->execute();
        return $sth->fetchAll();
    }
    function getAlmacenes($idsucursal)
    {
        $sth = $this->db->prepare("SELECT idalmacen,descripcion 
                                    FROM produccion.almacenes
                                    where idsucursal = ".$idsucursal."
                                   order by 1");
        $sth->execute();
        $data = array();
        foreach ($sth->fetchAll() as $key => $value) {
            $data[] = array($value['idalmacen'],$value['descripcion']);
        }
        return $data;
    }
    public function ffecha($fecha){
        $nfecha = explode("/",$fecha);
        return $nfecha[2]."-".$nfecha[1]."-".$nfecha[0];
    }
     public function getfecha($fecha){
         $nfecha = explode("-",$fecha);
        return $nfecha[2]."/".$nfecha[1]."/".$nfecha[0];
    }
    public function getTotal( $sql , $param ) 
    {
        $statement = $this->db->prepare($sql);
        foreach ($param as $key => $value) {
            switch ($value['type']) {
                case 'STR':
                    $statement->bindParam ($value['key'], $value['value'] , PDO::PARAM_STR);
                    break;
                default:
                    $statement->bindParam ($value['key'], $value['value'] , PDO::PARAM_INT);
                    break;
            }
        }
        $statement->execute();
        return $statement->rowCount();
    }
    public function getRow( $sql , $param , $p  ) 
     {
        $p = (int)$p;        
        $p = $this->rows*($p-1);        
        $sql = $sql . " LIMIT {$this->rows} OFFSET {$p} ";                
        $statement = $this->db->prepare($sql);
        foreach ($param as $key => $value) {
            switch ($value['type']) {
                case 'STR':
                    $statement->bindParam ($value['key'], $value['value'] , PDO::PARAM_STR);
                    break;
                default:
                    $statement->bindParam ($value['key'], $value['value'] , PDO::PARAM_INT);
                    break;
            }
        }
        $statement->execute();        
        return $statement->fetchAll();
    }
    public function getRowPag ( $total_rows , $vp ){
          $data = array();
          if (ceil($total_rows/$this->rows) <= $this->pag) {
              for ($x = 1 ; $x <= ceil($total_rows/$this->rows); $x++) {
                     if ($x == $vp ) {
                            $data[] = array('active'=>1,'type'=>1, 'value'=>$x);
                        } else {
                            $data[] = array('active'=>0,'type'=>1 , 'next'=> 0, 'value'=>$x);
                        }
              }
          } else {
          $flag = TRUE;
          if(ceil($total_rows/$this->rows) % $this->pag != 0) {
                for  ($j = ceil($total_rows/$this->rows); $j >= $this->pag ; $j-- ){
                          if ($j % $this->pag == 0 ){
                              if ($vp > $j ) {
                                  $flag = FALSE;
                                for ($x = $j+1 ; $x <= ceil($total_rows/$this->rows); $x++) {
                                        if ($x == $j+1  ) {
                                            $data[] = array( 'active'=>0, 'type'=>2, 'value'=>$x-1 );

                                        }
                                        if ($x == $vp ) {
                                            $data[] = array( 'active'=>1, 'type'=>1, 'value'=>$x );
                                        } else {
                                            $data[] = array( 'active'=>0, 'type'=>1, 'value'=>$x );
                                        }
                                }
                                  break;
                              }
                              else {

                               break;
                                }
                          }
                }
                if ($flag){
                              for ($x = $vp ; $x <= ceil($total_rows/$this->rows); $x++) {
                                    if (( $x % $this->pag ) == 0 ) {
                                        if ($x - $this->pag <= 0) {
                                            $z = 1;
                                        }
                                        else {
                                            $z = ($x - $this->pag)+1;
                                        }
                                        for ($y = $z; $y <= ($x); $y++) {
                                            if ($y > $this->pag && $y == $z  ) {
                                                $data[] = array( 'active'=>0, 'type'=>2, 'value'=>$y-1 );
                                            }
                                            if ($y == $vp )  {
                                                $data[] = array( 'active'=>1, 'type'=>1, 'value'=>$y );
                                            } else {
                                                $data[] = array( 'active'=>0, 'type'=>1, 'value'=>$y );
                                            }
                                            if ($y == $x && $y != ceil($total_rows/$this->rows)  ) {
                                                $data[] = array( 'active'=>0, 'type'=>3 , 'value'=>$y+1 );
                                            }
                                        }
                                        break;
                                    }
                              }
                }


          }else{
                  for ($x = $vp ; $x <= ceil($total_rows/$this->rows); $x++) {
                                    if (( $x % $this->pag ) == 0 ) {
                                        if ($x - $this->pag <= 0) {
                                            $z = 1;
                                        }
                                        else {
                                            $z = ($x - $this->pag)+1;
                                        }
                                        for ($y = $z; $y <= ($x); $y++) {
                                            if ($y > $this->pag && $y == $z  ) {
                                                $data[] = array( 'active'=>0, 'type'=>2, 'value'=>$y-1 );
                                            }
                                            if ($y == $vp )  {
                                                $data[] = array( 'active'=>1, 'type'=>1, 'value'=>$y );
                                            } else {
                                                $data[] = array( 'active'=>0, 'type'=>1, 'value'=>$y );
                                            }
                                            if ($y == $x && $y != ceil($total_rows/$this->rows)  ) {
                                                $data[] = array( 'active'=>0, 'type'=>3 , 'value'=>$y+1 );
                                            }
                                        }
                                        break;
                                    }
                              }
          }
          }
        return $data;
    }
    
    public function getnr()
    {
        return $this->rows;
    }
    public function fdate($fecha,$format)
    {
        $f = preg_split('/\/|-/', $fecha);   
        $c = count($f);
        if($c==3)
        {
        $n = strlen($f[0]);
        switch ($format) {
            case 'ES':                
                if($n>2)
                {
                    return $f[2]."/".$f[1]."/".$f[0];
                }
                else 
                {
                    return $fecha;
                }
                break;
            case 'EN':                
                if($n>2)
                {
                    return $f[0]."-".$f[1]."-".$f[2];
                }
                else 
                {
                    return $f[2]."-".$f[1]."-".$f[0];
                }
                break;                
            default:
                # code...
                return $fecha;
                break;
        }
       }
       else {
           return $fecha;
       }
    }
       
    
    /*
     * //Funciones para validacion de datos    
    */
    
    //Valida si la cadena es una Fecha: 
        public function isfecha($date)
        {
            $result = false;
            if (preg_match('%^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$%', $string)) 
            {
                $result = true;
            }
            return $result;
        }
        
        public function isDNI($dni)
        {
            $flag = false;
            $patron = "/^[0-9]{8,8}$/";
             if(preg_match($patron, $dni))
             {
                $flag = true;
             }
             return $flag;
        }
        public function isRUC($ruc)
        {
            $flag = false;
            $patron = "/^[0-9]{11,11}$/";
             if(preg_match($patron, $ruc))
             {
                $flag = true;
             }
             return $flag;
        }
        public function isNum($num)
        {
            $num = (float)$num;
            return $num;
        }
        public function isText($text)
        {
            $item = array(" DELETE "," FROM "," SELECT "," DROP "," SET "," UPDATE ");
            $text = strtoupper($text);
            foreach($item as $i)
            {
                $text = str_replace($i, "", $text);
            }
            return $text;
        }
        public function isEmail ($email)
        {
            $r = false;
            if($email!="")
            {
               if( preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
                {
                    $r = true;
                }   
            }
            else 
            {
                $r = true;
            }
           return $r;
        }
    
}
?>