<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
       
?>
<div style="padding:10px 20px">
<form id="frm" >
    <input type="hidden" name="controller" value="Modulo" />
    <input type="hidden" name="action" value="save" />             
    
    <label for="idmodulo" class="labels">Codigo:</label>
    <input type="text" id="idmodulo" name="idmodulo" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->idmodulo; ?>" readonly />
    
    <label for="idpadre" class="labels">Padre:</label>
    <?php echo $ModulosPadres; ?>
    <br/>
    
    <label for="descripcion" class="labels">Descripcion:</label>
    <input type="text" id="descripcion" maxlength="100" name="descripcion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
                
        <label for="url" class="labels">URL:</label>
   		<input id="url" name="url" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->url; ?>"  />
        <br/>
        
        <label for="controlador" class="labels">Controlador:</label>
   		<input type="text" id="controlador" name="controlador" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->controlador; ?>" />
        
        <label for="accion" class="labels">Acci&oacute;n:</label>
   		<input type="text" id="accion" name="accion" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->accion; ?>" />
        <br/>
        
        <label for="orden" class="labels">Orden:</label>
   		<input id="orden" name="orden" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->orden; ?>" />
        
        <label for="attrid" class="labels">Attributo Id:</label> 
        <input id="attrid" name="attrid" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->attrid; ?>" />
        <br/>
        
        <label for="attrclass" class="labels">Attributo Clase:</label> 
        <input id="attrclass" name="attrclass" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->attrclass; ?>" />
        
        <label for="estado" class="labels">Activo:</label>
            <?php                   
                if($obj->estado==true || $obj->estado==false)
                        {
                         if($obj->estado==true){$rep=1;}
                            else {$rep=0;}
                        }
                 else {$rep = 1;}                    
                 activo('activo',$rep);
            ?>
</form>
</div>