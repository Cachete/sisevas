<form id="frmpermisos" name="frmpermisos" action="">
   <input type="hidden" value="<?php echo $idperfil;?>" name="idperfil" id="idperfil" />
   <table border="0" cellpadding="2" cellspacing="0" style="width:60%; font-size:13px">
<?php
    
    foreach($mod as $m)
    {
        $c = $c+1;
        $check = "";
        if(strlen(trim($m['perfil']))!=0)
            {$check = "checked";}
        ?>
        <tr>
            <td width="5%" align="right"><?php echo str_pad($c , 2 , "0", 0); ?>.</td>
            <td align="center" width="5%"><input type="checkbox" name="m<?php echo $m['idmodulo'] ?>" id="m<?php echo $m['idmodulo'] ?>" value="<?php echo $m['idmodulo'] ?>" <?php echo $check;?>/></td>
            <td colspan="2" align="left"><b><?php echo $m['descripcion'] ?></b></td>
        </tr>
        <?php
        $d = 0;
        foreach($m['hijos'] as $sm)
        {
            $d = $d+1;
            $check = "";
            if(strlen(trim($sm['perfil']))!=0)
                {$check = "checked";}
            ?>
            <tr>
                  <td>&nbsp;</td>
                  <td align="center" width="5%"><?php echo $c.".".$d." "; ?></td>
                  <td align="center" width="5%"><input type="checkbox" name="m<?php echo $sm['idmodulo'] ?>" id="m<?php echo $sm['idmodulo'] ?>" value="<?php echo $sm['idmodulo']; ?>" <?php echo $check; ?>/></td>
                  <td align="left"><?php echo $sm['descripcion'] ?></td>
            </tr>
            <?php
        }
    }
?>
   </table>
</form>