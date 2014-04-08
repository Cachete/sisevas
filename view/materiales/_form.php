<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
       
?>
<div style="padding:10px 20px">
<form id="frm_materiales" >
    <input type="hidden" name="controller" value="Materiales" />
    <input type="hidden" name="action" value="save" /> 

    <label for="idmateriales" class="labels">Codigo:</label>
    <input id="idmateriales" name="idmateriales" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->idmateriales; ?>" readonly />
      
    <label for="descripcion" class="labels">Descripcion:</label>
    <input type="text" id="descripcion" maxlength="100" name="descripcion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
    <br/>
    
    <label for="idunidad_medida" class="labels">Unidad medida:</label>
    <?php echo $idunidad_medida; ?>
   
    <label for="stock" class="labels">stock:</label>
    <input type="text" id="stock" maxlength="100" name="stock" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->stock; ?>" />
    <br/>

    <label for="estado" class="labels">Estado:</label>
    <div id="estados" style="display:inline">
        <?php 
            $rep=1;                  
            if($obj->estado==1 || $obj->estado==0)
            {
                $rep= $obj->estado;
            }
                         
            activo('activo',$rep);
        ?>
    </div>
</form>
</div>