<?php 
       include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>
<div style="padding:10px 20px">
<form id="frm_aspectos" >
    <input type="hidden" name="controller" value="aspectos" />
    <input type="hidden" name="action" value="save" />   
    <input type="hidden" id="idaspecto" name="idaspecto" value="<?php echo $obj->idaspecto; ?>" />    
    <label for="idcompetencia" class="labels">Competencia:</label>
    <?php echo $competencias; ?>
    <br/>
    <label for="descripcion" class="labels">Descripcion:</label>
    <input type="text" id="descripcion" maxlength="100" name="descripcion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 400px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
    <br/> 
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
</div>