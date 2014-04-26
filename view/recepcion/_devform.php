<?php  
    include("../lib/helpers.php"); 
    include("../view/header_form.php");       
?>


<div style="padding:10px 20px; min-width:630px; min-height:450px;">
<form id="frm_recepcion" >

    <input type="hidden" name="controller" value="Recepcion" />
    <input type="hidden" name="action" value="save" />
    
    <input type="hidden" id="idtramite" name="idtramite" value="<?php echo $obj->idtramite; ?>" />
            
    <label for="tipodoc" class="labels">Tipo documento:</label>
    <?php echo $tipodoc; ?>
    
    <input type="text" id="correlativo" name="correlativo" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php echo $obj->codigo; ?>" readonly />
    
    <br/>
    <hr />
    <br/>
    <?php
    
        if ($obj->idtipo_documento== 1) {
        ?>
            <label for="fecha" class="labels">Fecha Emision:</label>
            <input type="text" name="fechainicio" id="fechainicio" class="ui-widget-content ui-corner-all text" value="<?php if($obj->fechainicio!=""){echo fdate($obj->fechainicio,'ES');} else {echo date('d/m/Y');} ?>" style="width:70px; text-align:center" readonly />
            <input type="hidden" name="horainicio" value="<?php echo $Hora_server; ?>" />
            <br />
            
            <label for="destinatario" class="labels">Remitente:</label>
            <?php echo $remitente; ?>
            <br/>

            <label for="asunto" class="labels">Asunto:</label>
            <input type="text" id="asunto" maxlength="150" name="asunto" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 230px; text-align: left;" value="<?php echo $obj->asunto; ?>" />
            <br />

            <label for="descripcion" class="labels">Descripcion:</label><br />
            <textarea name="problema" id="problema" style="width: 80%; margin-left:16%;" class="text ui-widget-content ui-corner-all" cols="20" rows="2" ><?php echo $obj->problema; ?></textarea>
            <br />

            <label for="docref" class="labels">Doc. Referenc.:</label>
            <input type="text" id="docref" maxlength="100" name="docref" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 230px; text-align: left;" value="<?php echo $obj->docref; ?>" />
            <br />

            <label for="destinatario" class="labels">Destinatario:</label>
            <?php echo $personal; ?>

        <?php
        }
    ?>
    
    <?php
        if ($obj->idtipo_documento== 2 || $obj->idtipo_documento== 3) {
        ?>
        <div id="tabs">
            <ul style="background:#DADADA !important; border:0 !important">
                <li><a href="#tabs-1">Información Del CLiente</a></li>
                <li><a href="#tabs-2">Información Del Problema</a></li>
                <li><a href="#tabs-3">Resultados</a></li>
            </ul>
            <div id="tabs-1">

                <fieldset>
                    <label for="nombres" class="labels">Cliente:</label>
                    <input type="text" id="nombres" maxlength="150" name="nombres" value="<?php echo $obj->nombres; ?>" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 150px; text-align: left;" placeholder="Nombres" />
                    <input type="text" id="apellidopat" maxlength="150" name="apellidopat" value="<?php echo $obj->appat; ?>" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 150px; text-align: left;" placeholder="Ap. Paterno" />
                    <input type="text" id="apellidomat" maxlength="150" name="apellidomat" value="<?php echo $obj->appat; ?>" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 150px; text-align: left;" placeholder="Ap. Materno" />
                    <br />

                    <label for="direccion" class="labels">DNI:</label>
                    <input type="text" id="dni" maxlength="150" name="dni" placeholder="DNI" value="<?php echo $obj->nrodocumento; ?>" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />

                    <label for="direccion" class="labels">Domicilio:</label>
                    <input type="text" id="direccion" maxlength="150" name="direccion" placeholder="Direccion" value="<?php echo $obj->direccion; ?>" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />
                    <br />

                    <label for="telefonos" class="labels">Telefonos:</label>
                    <input type="text" id="telefonos" maxlength="150" name="telefonos" value="<?php echo $obj->telefono; ?>" placeholder="Telefono" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;"  />

                    <label for="asunto" class="labels">Celular:</label>
                    <input type="text" id="celular" maxlength="150" name="celular" value="<?php echo $obj->celular; ?>" placeholder="Celular" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />

                </fieldset>

            </div>
            <div id="tabs-2">

                <fieldset>
                    <legend>Descripción del problema</legend>
                    <textarea name="problema" id="problema" style="width: 80%; margin-left: 30px;" class="text ui-widget-content ui-corner-all" cols="20" rows="4" ><?php echo $obj->problema; ?></textarea>
                    <br />

                    <label for="destinatario" class="labels">Tipo :</label>
                    <?php echo $tipoproblema; ?>
                    <br />

                    <label for="destinatario" class="labels">Area o Servicio:</label>
                    <!-- <select name="idareai" id="idareai" class="ui-widget-content ui-corner-all text" style="width:150px">
                        <option value="">Seleccione....</option>
                    </select> -->
                    <?php echo $idareai; ?>
                </fieldset>

            </div>

            <div id="tabs-3">

                <fieldset>
                    <legend>Resultados de la Investigación Realizada </legend>

                    <textarea name="resultados" id="resultados" style="width: 80%; margin-left: 30px;" class="text ui-widget-content ui-corner-all" cols="20" rows="4" ><?php echo $obj->resultados; ?></textarea>
                    <br />

                    <label for="destinatario" class="labeless">Resp. de Investigación</label>
                        <?php echo $personal; ?>
                    <br />

                    <label class="labeless">Estado</label>
                        <?php echo $cierre; ?>
                </fieldset>

            </div>
        </div>
        
        <?php
        }
    ?>
</form>
</div>
