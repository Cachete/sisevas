<?php
	$Hora_server = date('H:i:s');
?>

<script type="text/javascript" src="../web/js/app/evt_form_envio.js"></script>

<div id="table-per"> 
	<label for="fecha" class="labels">Fecha Emision:</label>
    <input type="text" name="fechainicio" id="fechainicio" class="ui-widget-content ui-corner-all text" value="<?php if($obj->fechainicio!=""){echo fdate($obj->fechainicio,'ES');} else {echo date('d/m/Y');} ?>" style="width:70px; text-align:center" readonly />
    <input type="hidden" name="horainicio" value="<?php echo $Hora_server; ?>" />
    <br />

    <label for="destinatario" class="labels">Remitente:</label>
    <?php echo $remitente; ?>
    <br/>
    
    <input type="hidden" id="asunto" name="asunto" value="<?php echo $obj->asunto; ?>" />
    <br />

	<label for="descripcion" class="labels">Descripcion:</label><br />
	<textarea name="problema" id="problema" style="width: 80%; margin-left:16%;" class="text ui-widget-content ui-corner-all" cols="20" rows="8" ><?php echo $obj->plantilla; ?></textarea>	
	<br />
    <input type="hidden" id="docref" name="docref" value="<?php echo $obj->docref; ?>" />
    

	<label for="destinatario" class="labels">Destinatario:</label>
	<?php echo $personal; ?><input name="todos" id="todos" class="capacitacion" type="checkbox">&nbsp;Todos&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" id="addDetail" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar Destiantarios</a>
        



	<table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:440px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">
                <th align="center" width="200px">Destinatarios</th>
                <th width="20px">&nbsp;</th>
            </tr>
        </thead>  
        <tbody>
                          
        </tbody>
         <tfoot>
            <tr>               
                <td colspan="2">&nbsp;</td>
            </tr>
           
        </tfoot>
    </table>

</div>