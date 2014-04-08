<?php
function link_to($txt,$url)
{
    
}
function input_tag($value,$name,$id=null,$readonly=false,$disabled=false,$style=null)
{
    if($id==null)
    {
        $id = $name;
    }
    
    $css = " style= ' ";
    if($style!=null)
    {
        $n = count($style);
        if($n>0)
        {            
            foreach($style as $key => $val)
            {
                $css .= " {$key} : {$val}; ";
            }            
        }
    }
    $css .= "'";
    
    if($readonly)
    $html = "<input type='text' name='{$name}' id='{$id}' class='ui-widget-content ui-corner-all text' {$css} value='{$val}' />";
    return $html;
}
function activo($n,$select="")
{  
  $check = ""; 
  $items = array("Si","No");
  $radios =  '<div style="display:inline" id="div_'.$n.'" >';
  foreach($items as $key => $val)
  {
      if((1-$key)==$select){$check="checked";}
        else {$check="";}
      $radios .= '<input type="radio" id="r'.$n.(1-$key).'" name="'.$n.'" value="'.(1-$key).'" '.$check.' /><label for="r'.$n.(1-$key).'">'.$val.'</label>';
  }
  $radios .= '</div>';
  echo $radios;
}

//Fecha 
function ffecha($fecha)
{
    if(strlen($fecha)>0){
    $nfecha = explode("-",$fecha);
    return $nfecha[2]."/".$nfecha[1]."/".$nfecha[0];
    }
    return "";
}

function dameURL()
{
    $url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
    return $url;
}

$meses = array('1'=>'Enero',
                '2'=>'Febrero',
                '3'=>'Marzo',
                '4'=>'Abril',
                '5'=>'Mayo',
                '6'=>'Junio',
                '7'=>'Julio',
                '8'=>'Agosto',
                '9'=>'Setiembre',
                '10'=>'Octubre',
                '11'=>'Noviembre',
                '12'=>'Diciembre');

function select($name,$id,$items,$key)
{
    $html = "<select name='".$name."' id='".$id."' class='ui-widget-content ui-corner-all text'>";
    $html .= "<option value=''>::Seleccione::</option>";
    foreach($items as $k => $i)
    {
        $selected = "";
        if($k==$key)
        {
            $selected = "selected";
        }
        $html .= "<option value='".$k."' ".$selected.">".$i."</option>";
    }
    $html .= "</select>";
    return $html;
}
 function fdate($fecha,$format)
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
?>