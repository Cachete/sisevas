<form id="frmpermisos" name="frmpermisos" action="">
<input type="hidden" value="<?php echo $idperfil;?>" name="idperfil" id="idperfil" />
<div>
<ul>
<?php    
    foreach($mod as $m)
    {
        
        $check = "";
        if(strlen(trim($m['perfil']))!=0)
            {$check = "checked";}
        ?>        
            <li>
                <input type="checkbox" name="m<?php echo $m['idmodulo'] ?>" id="m<?php echo $m['idmodulo'] ?>" value="<?php echo $m['idmodulo'] ?>" <?php echo $check;?>/>
                <label><b><?php echo $m['descripcion'] ?></b></label>
                <?php echo SubMenus($m['hijos']); ?>
            </li>        
       <?php 
    }
   ?>   
</ul>    
</div>
</form>
<?php  
 function SubMenus($mod)
 {
     $n = count($mod);
     if($n>0)
     {
     $html = "";
     $html .= '<div style="margin-left:20px;"><ul>';
     foreach($mod as $m)
     {
         $check = "";
         if(strlen(trim($m['perfil']))!=0)
            {$check = "checked";}
         
         $html .= '<li>';
         $html .= '<input type="checkbox" name="m'.$m['idmodulo'].'" id="m'.$m['idmodulo'].'" value="'.$m['idmodulo'].'" '.$check.'/><label style="margin-left:5px;"><b>'.$m["descripcion"].'</b></label>';
         $html .= SubMenus($m['hijos']);
         $html .= '</li>';
         
     }
     $html .= '</ul></div>';
     return $html;
     }
 }
?>