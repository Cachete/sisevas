<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>

   
<form id="frm_tpdocumento" >
    <input type="hidden" name="controller" value="Tipodocumento" />

    <input type="hidden" name="action" value="save" />
    
        <input type="hidden" id="idtipo_documento" name="idtipo_documento" value="<?php echo $obj->idtipo_documento; ?>" />
        
        <label for="descripcion" class="labels">Descripcion:</label>
        <input id="descripcion" maxlength="100" name="descripcion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
        

        <label for="abreviado" class="labels">Abriatura:</label>
        <input id="abreviado" maxlength="100" name="abreviado" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->abreviado; ?>" />
        <br />

        <label for="descripcion" class="labels">Plantilla:</label><br />
        <textarea name="plantilla" id="plantilla" style="width: 80%; margin-left:16%;" class="text ui-widget-content ui-corner-all" cols="20" rows="10" ><?php echo $obj->plantilla; ?></textarea>
        <br />

        <label for="estado" class="labels">Estado:</label>
        <div id="estados" style="display:inline">
        <?php                   
            if($obj->estado==1 || $obj->estado==0)
            {
                if($obj->estado==1){$rep=1;}
                else {$rep=0;}
            }
            else {$rep = 1;}                    
                activo('activo',$rep);
        ?>
    </div>     

</form>
