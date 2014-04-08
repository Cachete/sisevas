<?php include("../lib/helpers.php"); ?>
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
<div style="padding:10px 20px; width:900px">
<form id="frm_ventas" >
    <div id="tabs">
      <ul style="background:#DADADA !important; border:0 !important">
        <li><a href="#tabs-1">Registro Ventas</a></li>
        <?php if($obj->idmovimiento==""){ ?>
        <li><a href="#tabs-2">Cronograma / Coutas</a></li>
        <li><a href="#tabs-3">Registro Pagos</a></li>
        <?php } ?>
      </ul>
      <div id="tabs-1">
        <?php if($obj->idmovimiento==""){ ?>
        <!-- <div class="ui-widget-content" style="text-align:right; background:#">
            <a href="#" style="color:green; font-weight:bold;">Agregar de Proforma: </a>
            <input type="text" name="nroproforma" id="nroproforma" class="ui-widget-content ui-corner-all text" placeholder="N° de Proforma" onkeypress="return permite(event,'num')" maxlength="10" />
            <a href="javascript:popup('index.php?controller=proforma&action=lista',870,350)" class="box-boton boton-search" title="buscar Proforma">&nbsp;</a>
            <a href="#" class="box-boton boton-ok" title="Cargar Datos"></a>            
        </div> -->
        <?php }  ?>
        <fieldset class="ui-corner-all" style="padding: 2px 10px 7px">
              <legend>Datos Generales - Fecha <?php echo date('d/m/Y'); ?></legend>
                <input type="hidden" name="controller" id="controller" value="ventas" />
                <input type="hidden" name="action" id="action" value="save" />       
                <input type="hidden" name="idventa" id="idventa" value="<?php echo $obj->idmovimiento; ?>" />       
                <label class="labels" for="idalamacen">Almacen: </label>      
                <?php echo $Almacen; ?>   
                <!-- <label class="labels" for="fecha">Fecha: </label>      
                <input type="text" name="Fecha" id="Fecha" value="<?php echo date('d/m/Y') ?>" class="ui-widget-content ui-corner-all text text-date"  disabled="" />  -->        
                <label class="labels" for="idtipopago">Tipo de Venta: </label>      
                <?php echo $tipopago; ?>  
                <label for="igv" class="labels" style="width:80px;">Afecto IGV:</label>
                <?php $ck = ""; if($obj->afectoigv==1) $ck = "checked"; ?>
                <input type="checkbox" name="aigv" id="aigv" value="1" <?php echo $ck; ?> /> 18%
                <input type="hidden" name="igv_val" id="igv_val" value="<?php if($obj->porcentajeigv!="") echo $obj->porcentajeigv; else echo "18"; ?>" />      
                <br/>
                <label class="labels" for="idtipodocumento">Documento: </label>      
                <?php echo $tipodocumento; ?>
                <label class="labels">N&deg;</label>
                <input name="serie" value="<?php echo $obj->documentoserie; ?>" id="serie" title="Serie" type="text" class="ui-widget-content ui-corner-all text" style="width:40px;"  />-
                <input name="numero" value="<?php echo $obj->documentonumero; ?>" id="numero" title="N&uacute;mero" type="text" class="ui-widget-content ui-corner-all text" style="width:70px;"  />
                <label class="labels">Fecha Emision: </label>
                <input type="text" name="fechaemision" id="fechaemision" value="<?php echo date('d/m/Y') ?>" class="ui-widget-content ui-corner-all text text-date" />
                <br/>
                <label class="labels">Cliente: </label>
                <input name="idcliente" type="hidden" id="idcliente" value="<?php echo $obj->idcliente; ?>" />  
                <input type="text" name="ruc" id="ruc" value="<?php echo $obj->dni; ?>" class="ui-widget-content ui-corner-all text" maxlength="11" size="11"  placeholder="DNI / RUC" />
                <input type="text" id="cliente" name="cliente" value="<?php echo $obj->nomcliente; ?>" class="ui-widget-content ui-corner-all text" title="Razon Social" style="width:250px;" placeholder="Nombre / Razon social" />        
                <input type="text" id="direccion" name="direccion" value="<?php echo $obj->direccion; ?>" class="ui-widget-content ui-corner-all text" title="Direccion"  style="width:326px;" placeholder="Direccion" />        
                <br/>
                <label class="labels">Forma de Pago: </label>
                <?php echo $formapago; ?>
                <label class="labels" style="width:60px">Moneda: </label>
                <?php echo $moneda; ?>
                <label class="labels">Tipo de Cambio: </label>
                <input name="tipo_cambio" value="0.00" id="tipo_cambio" title="Tipo de Cambio" type="text" class="ui-widget-content ui-corner-all text text-num" readonly="readonly"/>
                <label class="labels" style="width:60px">Dscto: </label>
                <input type="text" name="monto_descuento" id="monto_descuento" value="<?php if($obj->descuento!="") echo number_format($obj->descuento,2); else echo "0.00";  ?>"  title="Monto del descuento" class="ui-widget-content ui-corner-all text text-num" onkeypress="return permite(event,'num')" />
                <?php 
                $s1 = "selected";
                $s2 = "";
                if($obj->tipodescuento==2) { $s2 = "selected"; $s1 = ""; }
                ?>
                <select name="tipod" id="tipod">
                    <option value="1" <?php echo $s1; ?> >S/.</option>
                    <option value="2" <?php echo $s2; ?> >%</option>
                </select>                
                <br/>
                <label class="labels">&nbsp; </label>
                <textarea name="observacion" id="observacion" class="ui-widget-content ui-corner-all text"  title="Observaciones" rows="2" placeholder="Observacion" style="width:85%"><?php echo $obj->obs; ?></textarea>
            </fieldset>
        </form>
        <?php $colspan=4; if($obj->idmovimiento!=""){ $style = "display:none";  $colspan=4; }?>
         <fieldset id="box-melamina" class="ui-corner-all" style="padding: 2px 10px 7px; <?php echo $style; ?>">  
                <legend>Detalle de la venta</legend>                
                <div id="box-1"  >
                    <label class="labels" for="producto">Producto: </label>            
                    <div class="ui-widget" style="display:inline-block">
                        <?php echo $subproductosemi; ?>
                    </div>                    
                    <span>
                        <label class="labels" for="precio" style="">Precio: </label>                    
                        <input type="text" name="precio" id="precio" value="0.00" class="ui-widget-content ui-corner-all text text-num" />                    
                        <label class="labels" for="stock" style="width:50px">Stock: </label>                    
                        <input type="text" name="stock" id="stock" value="0.00" class="ui-widget-content ui-corner-all text text-num" readonly="readonly" />                
                        <img id="load-stock" src="images/loader.gif" style="display:none" />
                        <label class="labels" for="cantidad" style="width:70px">Cantidad: </label>                    
                        <input type="text" name="cantidad" id="cantidad" value="0.00" class="ui-widget-content ui-corner-all text text-num" onkeypress="enter(event);return permite(event,'num');" />
                        <a href="javascript:" id="btn-add-ma" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a>                         
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
                                <th align="center" width="80">Precio</th>
                                <th align="center" width="80">Cantidad</th> 
                                <th align="center" width="80px">Importe S/.</th>
                                <?php if($obj->idmovimiento==""){ ?>
                                <th width="40px" align="center"><p style="font-size:8px">EDITAR</p></th>
                                <th width="40px" align="center"><p style="font-size:8px">QUITAR</p></th>
                                <?php } ?>
                            </tr>
                        </thead>  
                        <tbody>                                             
                        </tbody>

                         <tfoot>
                            <tr>
                                <td colspan="<?php echo $colspan; ?>" align="right"><b>SUB TOTAL S/.</b></td>
                                <td align="right"><b>0.00</b></td>
                                <?php if($obj->idmovimiento==""){ ?><td>&nbsp;</td> <?php } ?>
                            </tr>
                            <tr>
                                <td colspan="<?php echo $colspan; ?>" align="right" id="label_dscto"><b>Dscto S/.</b></td>
                                <td align="right"><b>0.00</b></td>
                                <?php if($obj->idmovimiento==""){ ?><td>&nbsp;</td> <?php } ?>
                            </tr>
                            <tr>
                                <td colspan="<?php echo $colspan; ?>" align="right"><b>IGV (18%) S/.</b></td>
                                <td align="right"><b>0.00</b></td>
                                <?php if($obj->idmovimiento==""){ ?><td>&nbsp;</td> <?php } ?>
                            </tr>
                            <tr>
                                <td colspan="<?php echo $colspan; ?>" align="right"><b>TOTAL S/.</b></td>
                                <td align="right"><b>0.00</b></td>
                                <?php if($obj->idmovimiento==""){ ?><td>&nbsp;</td> <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div> 
      </div>
    <?php if($obj->idmovimiento==""){ ?>

      <div id="tabs-2">
            <fieldset>
            <legend>Datos de Generacion de Cronograma de Pago</legend>  
            <label class="labels">Total Venta: </label>
            <label class="text-super" id="tventatext">S/. 0.00</label>
            <label class="labels">N&deg; de Cuotas: </label>
            <select name="nrocuota" id="nrocuota">
                <option value="1">1 Cuota</option>
                <option value="2">2 Cuotas</option>
                <option value="3">3 Cuotas</option>
                <option value="4">4 Cuotas</option>
                <option value="5">5 Cuotas</option>
                <option value="6">6 Cuotas</option>
            </select>            
            <label for="monto_inicial" class="labels">Inicial: </label>
            <input type="text" name="monto_inicial" id="monto_inicial" value="0.00"  class="ui-widget-content ui-corner-all text text-num" /> S/.
            <label class="labels" >Interes: </label>
            <input type="text" name="interes" id="interes" value="50.00"  class="ui-widget-content ui-corner-all text text-num" />
            <select name="tipoi" id="tipoi">
                <option value="1">S/.</option>
                <option value="2">%</option>
            </select>
            <br/>
            <label for="periodo" class="labels">Periodo Pago: </label>
            <select name="periodo" id="periodo">
                <option value="">-Seleccione-</option>
                <option value="1">Diario</option>
                <option value="2">Semanal</option>
                <option value="3">Mensual</option>
            </select>
            <label for="fechai" class="labels">Fecha de inicio: </label>
            <input type="text" name="fechai" id="fechai" value="<?php echo date('d/m/Y'); ?>" class="ui-widget-content ui-corner-all text text-date"  />
            <a id="gen-cronograma" href="#" style="color:green">Generar</a>
        </fieldset>
        <div class="contain" style="">
            <table id="table-detalle-cuotas" class="ui-widget ui-widget-content" border="0" >
                <thead>
                    <tr class="ui-widget-header">
                        <td width="50px" align="center">N&deg;</td>
                        <td width="100">Fecha de pago</td>
                        <td width="100" align="center">Monto</td>
                        <td width="100" align="center">Interes</td>
                        <td width="100" align="center">Total</td>                        
                        <td>&nbsp;</td>
                    </tr>
                </thead> 
                <tbody>
                </tbody>
            </table>  
          </div>
          
      </div>
      <div id="tabs-3">
            <fieldset>
            <legend>Datos de Pago</legend>            
            <label class="labels" id="text_totale_venta">Total Venta: </label>
            <label class="text-super" id="total_pago">S/. 0.00</label>
            <span id="box-pay-doc" style="display:none">
                <label class="labels">Documento: </label>
                <!-- <input type="text" name="document_recibo" id="document_recibo" value="RECIBO DE INGRESO" class="ui-widget-content ui-corner-all text" disabled="disabled" style="width:120px"/>             -->
                <label class="text-super">RECIBO DE INGRESO</label>
                <!-- <input name="seriep" value="" id="seriep" title="Serie" type="text" class="ui-widget-content ui-corner-all text" style="width:40px;"  />-
                <input name="numerop" value="" id="numerop" title="N&uacute;mero" type="text" class="ui-widget-content ui-corner-all text" style="width:70px;"  /> -->
            </span> 
            <br/>
            <span>
                <label class="labels">Forma de pago: </label>
                <?php echo $formapago2; ?>
            </span>
            <span id="box-pay-tarjeta" style="display:none">
             <input type="text" name="nrotarjeta" id="nrotarjeta" value="" class="ui-widget-content ui-corner-all text" placeholder="N&deg; de Tarjeta" />
             <input type="text" name="nrovoucher" id="nrovoucher" value="" class="ui-widget-content ui-corner-all text" placeholder="N&deg; de Voucher" style="width:200px" />
            </span>
            <span id="box-pay-cheque" style="display:none">
                <input type="text" name="nrocheque" id="nrocheque" value="" class="ui-widget-content ui-corner-all text" placeholder="N&deg; de Cheque" />
                <input type="text" name="banco" id="banco" value="" class="ui-widget-content ui-corner-all text" placeholder="Banco del cheque" style="width:200px" />                                
                <input type="text" name="fechav" id="fechav" value="" class="ui-widget-content ui-corner-all text text-date"  placeholder="Fecha Vencimiento" />
            </span>
            <br/> 
                 <label class="labels">Monto: </label>
                 <input type="text" name="monto_efectivo" id="monto_efectivo" value="0.00"  class="ui-widget-content ui-corner-all text text-num" />
                 S/. <a href="javascript:" id="add-fp" style="color:green">Agregar</a>
          </fieldset>
           <div class="contain" style="">
            <table id="table-detalle-pagos" class="ui-widget ui-widget-content" border="0" >
                <thead>
                    <tr class="ui-widget-header">
                        <td width="100px" align="center">Forma de Pago</td>                             
                        <td >Descripcion</td>
                        <td width="100" align="center">Monto</td>
                        <td width="30px">&nbsp;</td>
                    </tr>
                </thead> 
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td align="right" colspan="2"><b>Total de Pago:</b></td>
                        <td align="right"><b>0.00</b></td>
                        <td>&nbsp;</td>                        
                    </tr>
                    <tr>
                        <td align="right" colspan="2">Monto Faltante:</td>
                        <td align="right">0.00</td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
            </table>  
          </div>        
      </div>  
      <?php } ?>    
    </div>
</form>
</div>
