<?php  
    include("../lib/helpers.php"); 
    include("../view/header_form.php");
    
?>
<div class="div_container">
<script type="text/javascript" src="../web/js/app/evt_form_consultas.js"></script>

<div style="padding:10px 20px">    
    
    <div class="contain" style="margin-top: 10px; width: 560px"> 
        
        <form id="frm_rptVentas">
            <center>
                <h6 class="ui-widget-header" style="width: 560px">Reporte de ventas</h6>
            </center>
            <input type="hidden" name="controller" value="Consultas" />
            <input type="hidden" name="action" value="printproformas" />

            <div id="ventas">
                <label for="fechad" class="labels">Fecha Desde</label>
                <input type="text" name="fechad" id="fechad" class="ui-widget-content ui-corner-all text" value="<?php echo date('d/m/Y'); ?>" size="10" />
                <br/>
                <label for="fechah" class="labels">Fecha Hasta</label>
                <input type="text" name="fechah" id="fechah" class="ui-widget-content ui-corner-all text" value="<?php echo date('d/m/Y'); ?>" size="10" />
                <br/>
                <label for="idpersonal" class="labels">Personal</label>
                <?php echo $Personal; ?>
                <br/>
                <div  style="clear: both; padding: 10px; width: auto;text-align: center">
                    <!-- <a href="#" id="print_rpt" class="button">IMPRIMIR</a> -->
                    <a href="#" id="reporte_vent" class="button">GENERAR</a>
                    <a href="index.php" class="button">ATRAS</a>
                </div>  
            </div>      
        </form>
    </div>
</div>

<div id="load_resultado">    
</div>

</div>