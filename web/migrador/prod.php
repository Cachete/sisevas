<?php
$Servidor = "localhost";	
$Puerto = "5432";
$Usuario = "postgres";
$Password = "12345678";
$Base = "bdromero";
$conectar = pg_connect("host=$Servidor port=$Puerto password=$Password user=$Usuario dbname=$Base");
ini_set ( "memory_limit" , "500M" );
include_once("../../model/Main.php");
include_once('../../model/produccion.php');
if(isset($_POST['exportar']))
{
   function dl_file($file)
    {
        if (!is_file($file)) { die("<b>404 File not found!</b>"); }
        $len = filesize($file);
        $filename = basename($file);
        $file_extension = strtolower(substr(strrchr($filename,"."),1));
        $ctype="application/force-download";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: $ctype");
        $header="Content-Disposition: attachment; filename=".$filename.";";
        header($header );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$len);
        @readfile($file);
        exit;
    }
    
$allow_url_override = 1;
if(!$allow_url_override || !isset($file_to_include))
{
    $file_to_include = $_FILES["archivo"]["tmp_name"];
}
if(!$allow_url_override || !isset($max_rows))
{
    $max_rows = 0; //USE 0 for no max
}
if(!$allow_url_override || !isset($max_cols))
{
    $max_cols = 5; //USE 0 for no max
}
if(!$allow_url_override || !isset($debug))
{
    $debug = 0;  //1 for on 0 for off
}
if(!$allow_url_override || !isset($force_nobr))
{
    $force_nobr = 1;  //Force the info in cells not to wrap unless stated explicitly (newline)
}
require_once("Excel/reader.php");
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($file_to_include);
error_reporting(E_ALL ^ E_NOTICE);
function clearText($text,$max,$min,$texpad='0')
{
    $text = trim($text);
    if($min>0)
    {
        $text = str_pad($text, $min,$texpad, 0);
    }    
    $text = substr($text, 0, $max);
    return $text;
}
function Num($num,$decimales)
{    
    if(trim($num)=="")
        $n = 0;
    else 
        $n = $num;
    return number_format($n,$decimales,'.','');    
}
$anio = $_POST['anio'];
$mes = str_pad($_POST['mes'], 2,'0',0);
$sql = "";
function clear($text)
{
    $t = str_replace(" ","", $text);
    return $t;
}

$objprod = new Produccion();

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


for($i=3;$i<=$data->sheets[0]['numRows'];$i++)
{
    /*
    prod = {
-                    item          : 0,                    
-                    idsps         : array()
-                    descripcion   : array(), //
-                    cantidad      : array(),                    
-                    estado        : array(),
-                }
     * 
     */
    
    $sql = "INSERT INTO produccion.produccion_detalle(
            idproduccion, idsubproductos_semi, cantidad, 
            stock, estado, ctotal, idalmacen, item, totalp)
            VALUES (%idp%, ".$data->sheets[0]['cells'][$i][8].", ".$data->sheets[0]['cells'][$i][4].", 
            0, 1, ".$data->sheets[0]['cells'][$i][4].", 3, ?, ?);";
        $sql .= $anio.$mes."00|";        
        $correlativo = $data->sheets[0]['cells'][$i][2];
        $codplan = "01";
        $codcta = $data->sheets[0]['cells'][$i][6];
        $fecha = $data->sheets[0]['cells'][$i][3];
        $glosa = $data->sheets[0]['cells'][$i][4];
        $debe = Num($data->sheets[0]['cells'][$i][8],2);
        $haber = Num($data->sheets[0]['cells'][$i][9],2);
        $estado = 1;
        $sql .= $correlativo."|".$codplan."|".$codcta."|".$fecha."|".$glosa."|".$debe."|".$haber."|".$estado."|".PHP_EOL;
      
}

$ruc = "migrador";

//$ruc = "20104050337";
//$ruc = "20494230985";
//$ruc = "20531516045";
$str = "_";
$nombre_archivo = 'LE'.$ruc.$anio.$mes.$str.'.txt';

$contenido = $sql;
fopen($nombre_archivo, 'w');

if (is_writable($nombre_archivo)) {
   if (!$gestor = fopen($nombre_archivo, 'a')) {
         echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
   }

   if (fwrite($gestor, $contenido) === FALSE) {
       echo "No se puede escribir al archivo ($nombre_archivo)";
       exit;
   }

   fclose($gestor);
   dl_file($nombre_archivo);

} else {
   echo "No se puede escribir sobre el archivo $nombre_archivo";
}

}

?>

<h1>MiGRADOR</h1>
<form name="frm" id="frm" action="<?=$PHP_SELF?>" method="POST" enctype="multipart/form-data">    
    <select name="idalmacen" id="idalmacen">
        <?php 
        $sql = "SELECT * from produccion.almacenes";
        $q = pg_query($sql);
        while($r = pg_fetch_array($q))
        {
            ?>
            <option value="<?php echo $r[0] ?>"><?php echo $r[1] ?></option>
            <?php
        }
        ?>
        
    </select>
    <input type="file" name="archivo" id="archivo" value="" /><input type="submit" name="exportar" id="exportar" value="exportar" />
</form>
<?php 
class Produccion extends Main
{ 
    
}
?>