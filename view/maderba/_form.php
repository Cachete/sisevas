<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>

   
<form id="frm_maderba" >
    <input type="hidden" name="controller" value="Maderba" />

    <input type="hidden" name="action" value="save" />
    
        <label for="idmaderba" class="labels">Codigo:</label>
        <input id="idmaderba" name="idmaderba" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->idmaderba; ?>" readonly />
                
        <label for="descripcion" class="labels">Descripcion:</label>
        <input id="descripcion" maxlength="100" name="descripcion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
        <br>
        
        <label for="espesor" class="labels">Espesor:</label>
        <input id="espesor" maxlength="100" name="espesor" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->espesor; ?>" />
        
        <label for="espesor" class="labels">Linea:</label>
        <?php echo $idlinea; ?>
        <br>

        <label for="estado" class="labels">Activo:</label>
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
