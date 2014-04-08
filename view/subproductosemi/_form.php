<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>

<form id="frm_SubProductoSemi" >
    <input type="hidden" name="controller" value="SubProductoSemi" />
    <input type="hidden" name="action" value="save" />    
    <input type="hidden" id="idsubproductos_semi" name="idsubproductos_semi" value="<?php echo $obj->idsubproductos_semi; ?>" />
    
    <div id="tabs">
        <ul style="background:#DADADA !important; border:0 !important">
            <li><a href="#tabs-1">Datos del Producto</a></li>
            <li><a href="#tabs-2">Precios del Prodcutos</a></li>
            
        </ul>   
        <div id="tabs-1">
            <label for="productos_semi" class="labels">Producto Semi</label>
            <?php echo $productos_semi; ?>
            <br/>
            <label for="descripcion" class="labels">Descripcion:</label>
            <input id="descripcion" maxlength="150" name="descripcion" value="<?php echo $obj->descripcion; ?>" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 480px; text-align: left;" />
            <br/>
            
            <label for ="factores" class="labels">Factor</label>
            <input id="factor" name="factor" value="<?php echo $obj->factor; ?>" onkeypress="return permite(event, 'num_car')" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />
            
            <label for ="unidad_med" class="labeles">Unidad de medida:</label>
            <?php echo $UnidadMedida; ?>
            <br />
            
            <label for="obs" class="labels">Observaciones</label>
            <input id="obs" name="obs" value="<?php echo $obj->observacion; ?>" onkeypress="return permite(event, 'num_car')" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" />
            
            <label for="estado" class="labels">Activo:</label>
            <div id="estados" style="display:inline">
                <?php                   
                    if($obj->estado==1 || $obj->estado==0)
                    {
                        if($obj->estado==1){$rep=1;}
                        else {$rep=0;}
                    }
                    else {$rep = 1;}                    
                        activo('activo',$rep);
                ?>
            </div>  

        </div>
        <div id="tabs-2">
            <div id="table_precios">
                <label for="valorv" class="labels">Valor Venta (S/.):</label>
                <input id="valorventa" name="valorventa" value="<?php echo $obj->valorventa; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />

                <label for="valorv" class="labeles">Dscto Compra (S/.):</label>
                <input id="dsctocompra" name="dsctocompra" value="<?php echo $obj->dsctocompra; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />
                <br />

                <label for="fletes" class="labels">Flete (S/.):</label>
                <input id="flete" name="flete" value="<?php echo $obj->dsctocompra; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />

                <label for="precioc" class="labeles">Precio Compra (S/.):</label>
                <input id="preciocompra" name="preciocompra" value="<?php echo $obj->preciocompra; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />
                <br />

                <label for="impuesto" class="labels">Impuesto (S/.):</label>
                <input id="impuesto" name="impuesto" value="<?php echo $obj->impuesto; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />

                <label for="impuesto" class="labeles">Costo Neto (S/.):</label>
                <input id="costoneto" name="costoneto" value="<?php echo $obj->costoneto; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />
                <br />

                <label for="utilidad" class="labels">Util. Neta(%):</label>
                <input id="utilidadneta" name="utilidadneta" value="<?php echo $obj->utilidadneta; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />

                <label for="psugerido" class="labeles">Precio Sugerido (S/.):</label>
                <input id="preciosasugerido" name="preciosasugerido" value="<?php echo $obj->preciosasugerido; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />
                <br />

                <label for="dcstovent" class="labels">Dscto Venta (S/.):</label>
                <input id="dcstoventa" name="dcstoventa" value="<?php echo $obj->dcstoventa; ?>" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" />

                <label for="precio" class="labeles">Precio Venta (S/.):</label>
                <input id="precio" maxlength="100" name="precio" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php echo $obj->precio; ?>" />

            </div>
            
        </div>
    </div>
        


</form>
