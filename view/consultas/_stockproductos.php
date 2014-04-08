<?php  
    include("../lib/helpers.php"); 
    include("../view/header_form.php");
    
?>
<script type="text/javascript" src="../web/js/app/evt_form_consultas.js"></script>

<div style="padding:10px 20px">    
    
    <div class="contain" style="margin-top: 10px; width: 560px"> 
        
        <form id="frm_rptStock">
            <center>
                <h6 class="ui-widget-header" style="width: 560px">Reporte de Stock de Productos</h6>
            </center>
            <input type="hidden" name="controller" value="Consultas" />
            <input type="hidden" name="action" value="printproformas" />

            <div id="stock">
                <br/>
                <label for="idpersonal" class="labels">Almacen</label>
                <?php echo $almacen; ?>
                <br/>
                <div  style="clear: both; padding: 10px; width: auto;text-align: center">                    
                    <a href="#" id="reporte_stock" class="button">GENERAR</a>
                    <a href="index.php" class="button">ATRAS</a>
                </div>  
            </div>      
        </form>
    </div>
</div>

<div id="load_resultado">    
</div>