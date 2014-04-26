<?php
    $Hora = date('H:i:s');
    $fecha = date('Y-m-d');
?>

<script type="text/javascript" src="../web/js/app/evt_form_envio.js"></script>

<script type="text/javascript">
    $(function()
    {
        //
        $("#dni").autocomplete({
            minLength: 0,
            source: 'index.php?controller=clientes&action=get&tipo=1',
            focus: function(event, ui)
            {
                $("#dni").val(ui.item.dni);
                return false;
            },
            select: function(event, ui)
            {
                $("#idremitente").val(ui.item.idpaciente);
                $("#dni").val(ui.item.dni);
                $("#nombres").val(ui.item.nombres);
                $("#apellidopat").val(ui.item.apepaterno);
                $("#apellidomat").val(ui.item.apematerno);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a>" + item.dni + " - " + item.nompaciente + "</a>")
                    .appendTo(ul);
        };

    });
</script>

<div>	
    <div id="tabs">
        <ul style="background:#DADADA !important; border:0 !important">
            <li><a href="#tabs-1">Información Del CLiente</a></li>
            <li><a href="#tabs-2">Información Del Problema</a></li>
<!--            <li><a href="#tabs-3">Resultados</a></li>-->
        </ul>
        <div id="tabs-1">

            <fieldset>
                <label for="nombres" class="labels">Cliente:</label>
                <input type="text" id="nombres" maxlength="150" name="nombres" value="<?php echo $obj->nombres; ?>" onkeypress="return permite(event, 'car');" class="text ui-widget-content ui-corner-all" style=" width: 150px; text-align: left;" placeholder="Nombres" />
                <input type="text" id="apellidopat" maxlength="150" name="apellidopat" value="<?php echo $obj->apellidopat; ?>" onkeypress="return permite(event, 'car');" class="text ui-widget-content ui-corner-all" style=" width: 150px; text-align: left;" placeholder="Ap. Paterno" />
                <input type="text" id="apellidomat" maxlength="150" name="apellidomat" value="<?php echo $obj->apellidomat; ?>" onkeypress="return permite(event, 'car');" class="text ui-widget-content ui-corner-all" style=" width: 150px; text-align: left;" placeholder="Ap. Materno" />

                <input type="hidden" id="idremitente" name="idremitente"  value="<?php echo $obj->idremitente; ?>" />
                <input type="hidden" id="fechainicio" name="fechainicio"  value="<?php if ($obj->fechainicio == '') echo date('d/m/Y');
else echo fdate($obj->fechainicio, 'ES'); ?>" />
                <input type="hidden" id="horainicio" name="horainicio"  value="<?php if ($obj->horainicio == '') echo date('H:i:s');
else echo $obj->horainicio; ?>" />
                <br />

                <label for="direccion" class="labels">DNI:</label>
                <input type="text" id="dni" maxlength="150" name="dni" placeholder="DNI" value="<?php echo $obj->dni; ?>" onkeypress="return permite(event, 'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />

                <label for="direccion" class="labels">Domicilio:</label>
                <input type="text" id="direccion" maxlength="150" name="direccion" placeholder="Direccion" value="<?php echo $obj->direccion; ?>" onkeypress="return permite(event, 'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />
                <br />

                <label for="telefonos" class="labels">Telefonos:</label>
                <input type="text" id="telefonos" maxlength="150" name="telefonos" value="<?php echo $obj->telefonos; ?>" placeholder="Telefono" onkeypress="return permite(event, 'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;"  />

                <label for="asunto" class="labels">Celular:</label>
                <input type="text" id="celular" maxlength="150" name="celular" value="<?php echo $obj->celular; ?>" placeholder="Celular" onkeypress="return permite(event, 'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />

            </fieldset>

        </div>
        <div id="tabs-2">

            <fieldset>
                <legend>Descripción del problema</legend>
                <textarea name="problema" id="problema" style="width: 80%; margin-left: 30px;" class="text ui-widget-content ui-corner-all" cols="20" rows="4" ></textarea>
                <br />

                <label for="destinatario" class="labels">Tipo :</label>
<?php echo $tipoproblema; ?>
                <br />

                <label for="destinatario" class="labels">Area o Servicio:</label>
                <select name="idareai" id="idareai" class="ui-widget-content ui-corner-all text" style="width:150px">
                    <option value="">Seleccione....</option>
                </select>
            </fieldset>

        </div>
<!--        <div id="tabs-3">

            <fieldset>
                <legend>Resultados de la Investigación Realizada </legend>

                <textarea name="resultados" id="resultados" style="width: 80%; margin-left: 30px;" class="text ui-widget-content ui-corner-all" cols="20" rows="4" ></textarea>
                <br />

                <label for="destinatario" class="labeless">Resp. de Investigación</label>
<?php echo $personal; ?>
                <br />

                <label class="labeless">Estado</label>
<?php echo $cierre; ?>
            </fieldset>

        </div>-->

    </div>

</div>