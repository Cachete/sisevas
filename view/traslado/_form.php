<?php  include("../lib/helpers.php");     ?>
<style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
    /* support: IE7 */
    *height: 1.7em;
    *top: 0.1em;
  }
  .custom-combobox-input {
    margin: 0;
    padding: 0.3em;
  }
  </style>
<div style="padding:10px 20px; width:850px">
<form id="frm-produccion" >
    <fieldset class="ui-corner-all" style="padding: 2px 10px 7px">
    <legend>Datos - <b><?php if($obj->fecha!="") echo fdate($obj->fecha,'ES'); else echo date('d/m/Y'); ?></b></legend>
        <input type="hidden" id="controller" name="controller" value="traslado" />
        <input type="hidden" id="action" name="action" value="save" />             
        <input type="hidden" id="idproduccion" name="idproduccion" class="text ui-widget-content ui-corner-all" style=" width: 50px; text-align: left;" value="<?php echo $obj->idproduccion; ?>" readonly />                                        

        <label for="idalmacen" class="labels">Almacen:</label>
        <?php echo $almacen; ?>
        <label for="idalmacend" class="labels">Almacen Destino:</label>
        <?php echo $almacend; ?>
        <br/>
        <label for="descripcion" class="labels">Observaciones:</label>
        <input type="text" name="descripcion" id="descripcion" class="ui-widget-content ui-corner-all text" style="width:635px" value="<?php echo $obj->descripcion; ?>" />
    </fieldset> 
    <fieldset id="box-melamina" class="ui-corner-all" style="padding: 2px 10px 7px; <?php echo $style; ?>">  
        <legend>Detalle de la venta</legend>                
        <div id="box-1"  >
            <label class="labels" for="producto">Producto: </label>            
            <div class="ui-widget" style="display:inline-block">
                <?php echo $subproductosemi; ?>
            </div>                    
            <span style="margin-left:50px;">                
                <label class="labels" for="stock" style="width:100px">Stock Disponible: </label>                    
                <input type="text" name="stock" id="stock" value="0.00" class="ui-widget-content ui-corner-all text text-num" readonly="readonly" />                
                <img id="load-stock" src="images/loader.gif" style="display:none" />
                <label class="labels" for="cantidad" style="width:70px">Cantidad: </label>                    
                <input type="text" name="cantidad" id="cantidad" value="0.00" class="ui-widget-content ui-corner-all text text-num" onkeypress="enter(event);return permite(event,'num');" />
                <a href="javascript:" id="btn-add-ma" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a>                         
                <input type="hidden" name="precio" id="precio" value="0.00" class="ui-widget-content ui-corner-all text text-num" />                    
            </span>
        </div>
    </fieldset>  
    <div id="div-detalle">
        <div>
            <table id="table-detalle-venta" class="ui-widget ui-widget-content" style="margin: 0 auto; width:100%" border="0" >
                <thead class="ui-widget ui-widget-content" >
                    <tr class="ui-widget-header" style="height: 23px">          
                        <th align="center" width="50">Item</th>
                        <th>Producto</th>                        
                        <th align="center" width="80">Cantidad</th>                         
                        <?php if($obj->idmovimiento==""){ ?>                                
                        <th width="40px" align="center"><p style="font-size:8px">QUITAR</p></th>
                        <?php } ?>
                    </tr>
                </thead>  
                <tbody>                                             
                </tbody>
            </table>
        </div>
    </div>    
</form>
</div>

<div id="dialogConf">

</div>
<div id="box-add-mp">
    
</div>