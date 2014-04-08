<?php
	$Hora_server = date('H:i:s');
?>

<script type="text/javascript" src="../web/js/app/evt_form_envio.js"></script>

<div>
	<label for="fecha" class="labels">Fecha Emision:</label>
    <input type="text" name="fechainicio" id="fechainicio" class="ui-widget-content ui-corner-all text" value="<?php if($obj->fechainicio!=""){echo fdate($obj->fechainicio,'ES');} else {echo date('d/m/Y');} ?>" style="width:70px; text-align:center" readonly />
    <input type="hidden" name="horainicio" value="<?php echo $Hora_server; ?>" />
    <br />

    <!-- <label for="destinatario" class="labels">Remitente:</label>
	<?php echo $consultorio; ?>
	<br/> -->

	<label for="asunto" class="labels">Asunto:</label>
	<input type="text" id="asunto" maxlength="150" name="asunto" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 230px; text-align: left;" value="<?php echo $obj->asunto; ?>" />
	<br />

	<label for="descripcion" class="labels">Descripcion:</label><br />
	<textarea name="problema" id="problema" style="width: 80%; margin-left:16%;" class="text ui-widget-content ui-corner-all" cols="20" rows="2" ></textarea>
	<br />

	<label for="docref" class="labels">Doc. Referenc.:</label>
	<input type="text" id="docref" maxlength="100" name="docref" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 230px; text-align: left;" value="<?php echo $obj->docref; ?>" />
	<br />

	<label for="destinatario" class="labels">Destinatario:</label>
	<?php echo $personal; ?>

</div>