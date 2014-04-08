<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
       
?>
<div style="padding:10px 20px; min-width:630px; min-height:450px;">
<form id="frm_envio" >
        <input type="hidden" name="controller" value="Envio" />
        <input type="hidden" name="action" value="save" />
        
        <input type="hidden" id="idtramite" name="idtramite" value="<?php echo $obj->idtramite; ?>" />
                
        <label for="tipodoc" class="labels">Tipo documento:</label>
        <?php echo $tipodoc; ?>
        <input type="text" id="correlativo" name="correlativo" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php echo $obj->codigo; ?>" readonly />
        <!-- <a id="newLine" href="javascript:"><span class="box-boton">&nbsp;</span></a> 
        <div id="correlativo"></div>-->
        <br/>
        <hr />
        <br/>
        <div id="load_formato">
            
        </div>

        
</form>
</div>
