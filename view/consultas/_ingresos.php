<?php  
    include("../lib/helpers.php"); 
    include("../view/header_form.php");       
?>
<script type="text/javascript" src="../web/js/app/evt_form_consultas.js"></script>

<div style="padding:10px 20px">    
    
    <div class="contain" style="margin-top: 10px; width: 560px"> 
        
        <form id="frm_rptHojar">
            <center>
                <h6 class="ui-widget-header" style="width: 560px">Reporte de Ingresos</h6>
            </center>
            <input type="hidden" name="controller" value="Consultas" />
            <input type="hidden" name="action" value="printhojaruta" />
            <div id="ingreso">
                <label for="fechad" class="labels">Fecha Desde</label>
                <input type="text" name="fechad" id="fechad" class="ui-widget-content ui-corner-all text" value="<?php echo date('d/m/Y'); ?>" size="10" />
                <br/>
                <label for="fechah" class="labels">Fecha Hasta</label>
                <input type="text" name="fechah" id="fechah" class="ui-widget-content ui-corner-all text" value="<?php echo date('d/m/Y'); ?>" size="10" />
                <br/>
                <!-- <label for="idpersonal" class="labels">Personal</label>
                <?php echo $Personal; ?> -->
                
                <div  style="clear: both; padding: 10px; width: auto;text-align: center">
                    <!-- <a href="#" id="print_rpthr" class="button">IMPRIMIR</a> -->
                    <a href="#" id="reporte_ing" class="button">REPORTE</a>
                    <a href="index.php" class="button">ATRAS</a>
                </div>
            </div>      
        </form>
    </div>
</div>
<div id="load_resultado">    
</div>