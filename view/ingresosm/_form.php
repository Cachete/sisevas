<?php  include("../lib/helpers.php"); include("../view/header_form.php"); ?>
<div style="padding:10px 20px">
<form id="frm-ingresom" >
    <fieldset class="ui-corner-all" style="padding: 2px 10px 7px">
    <legend>Datos Compra - <?php if($obj->idmovimiento!="") {echo '['.$obj->idmovimiento.']';} echo '    Fecha: '; ?><b><?php echo date('d/m/Y'); ?></b></legend>
        <input type="hidden" name="controller" value="ingresom" />
        <input type="hidden" name="action" value="save" />             
        <input type="hidden" id="idmovimiento" name="idmovimiento" class="text ui-widget-content ui-corner-all" style=" width: 50px; text-align: left;" value="<?php echo $obj->idmovimiento; ?>" readonly />                
        <input type="hidden" name="igv_val" id="igv_val" value="<?php if($obj->igv!="") echo $obj->igv; else echo "18"; ?>" />
        <label for="idtipodocumento" class="labels">Documento:</label>
        <?php echo $tipodocumento; ?>        
        <label for="" class="labels" style="width:50px;">Nro:</label>
        <input type="text" id="serie" name="serie" class="text ui-widget-content ui-corner-all" style=" width: 50px; text-align: left;" value="<?php echo $obj->serie; ?>" />                
        <input type="text" id="numero" name="numero" class="text ui-widget-content ui-corner-all" style=" width: 80px; text-align: left;" value="<?php echo $obj->numero; ?>" />                
        <label for="fecha" class="labels">Fecha Emision:</label>
        <input type="text" name="fechae" id="fechae" class="ui-widget-content ui-corner-all text" value="<?php if($obj->fechae!=""){echo fdate($obj->fechae,'ES');} else {echo date('d/m/Y');} ?>" style="width:70px; text-align:center" />
        <label for="fecha" class="labels">Tipo Ingreso:</label>
        <?php echo $tipomov; ?>
        <br/>
        <label for="idproveedor" class="labels">Proveedor:</label>
        <input type="hidden" name="idproveedor" id="idproveedor" value="<?php echo $obj->idproveedor ?>" />
        <input type="text" name="ruc" id="ruc" class="ui-widget-content ui-corner-all text" style="width:80px" value="<?php echo $obj->ruc; ?>" maxlength="11" onkeypress="return permite(event,'num')" />
        <input type="text" name="proveedor" id="proveedor" class="ui-widget-content ui-corner-all text" style="width:300px" value="<?php echo $obj->razonsocial; ?>" />
        <label for="idformapago" class="labels">Forma Pago:</label>
        <?php echo $formapago; ?>
        <br/>
        <label for="guiaserie" class="labels">Guia Nro:</label>
        <input type="text" id="guiaserie" name="guiaserie" class="text ui-widget-content ui-corner-all" style=" width: 50px; text-align: left;" value="<?php echo $obj->guiaserie; ?>" />                
        <input type="text" id="guianumero" name="guianumero" class="text ui-widget-content ui-corner-all" style=" width: 80px; text-align: left;" value="<?php echo $obj->guianumero; ?>" />                
        <label for="fechaguia" class="labels" style="width:80px">Fecha Guia:</label>
        <input type="text" name="fechaguia" id="fechaguia" class="ui-widget-content ui-corner-all text" value="<?php if($obj->fechaguia!=""){echo fdate($obj->fechaguia,'ES');} else {echo date('d/m/Y');} ?>" style="width:70px; text-align:center" />
        <label for="igv" class="labels" style="width:80px;">Afecto IGV:</label>
        <?php $ck = ""; if($obj->afecto==1) $ck = "checked"; ?>
        <input type="checkbox" name="aigv" id="aigv" value="1" <?php echo $ck; ?> />
        <label for="idalmacen" class="labels" style="width:80px">Almacen:</label>
        <?php echo $almacen; ?>
        <br/>
        <label for="referencia" class="labels">Referencia:</label>
        <input type="text" name="referencia" id="referencia" class="ui-widget-content ui-corner-all text" style="width:500px" value="<?php echo $obj->referencia; ?>" />        
    </fieldset>
    <?php if($obj->idmovimiento=="") { ?>
    <div id="box-tipo-ma" class="ui-widget-header ui-state-hover" style="text-align:center">
        <label class="labels" for="tipo1" style="width:50px">Madera</label>
        <input class="tipo_material" type="radio" name="tipo" id="tipo1" value="1" checked="" />
        <label style="margin-left:20px;" for="tipo2">Melamina</label>
        <input class="tipo_material" type="radio" name="tipo" id="tipo2" value="2" />
    </div>
    <fieldset id="box-madera" class="ui-corner-all" style="padding: 2px 10px 7px">  
        <legend>Madera</legend>      
        <div id="box-1">
            <table id="table-ma" class="table-form" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td><label for="idmadera" class="labels" style="width:auto">Tipo de Madera</label></td>
                    <td><label for="largo_ma" class="labels" style="width:auto">Largo <label class="text-backinfo">Pul</label></label></td>                    
                    <td><label for="alto_ma" class="labels" style="width:auto">Ancho <label class="text-backinfo">Pul</label></label></td>                    
                    <td><label for="espesor_ma" class="labels" style="width:auto">Espesor <label class="text-backinfo">Pul</label></label></td>                    
                    <td><label for="volumen_ma" class="labels" style="width:auto">Vol. <label class="text-backinfo">Pies</label></label></td>                    
                    <td><label for="cantidad_ma" class="labels" style="width:auto">Cant. <label class="text-backinfo">Unid.</label></label></td>                 
                    <td><label for="volumen_ma" class="labels" style="width:auto">Vol. T. <label class="text-backinfo">Pies</label></label></td>                                           
                    <td><label for="precio_ma" class="labels" style="width:auto">Precio <label class="text-backinfo">(S/.)</label></label></td>
                    <td><label for="total_ma" class="labels" style="width:auto">Total <label class="text-backinfo">(S/.)</label></label></td>
                    <td rowspan="2"><a href="javascript:" id="addDetail_ma" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a> </td>                    
                </tr>
                <tr>
                    <td><?php echo $idmadera; ?></td>
                    <td><input type="text" name="largo_ma" id="largo_ma" value="0.00" class="ui-widget-content ui-corner-all text input_ma" style="width:55px; text-align:center" onkeypress="return permite(event,'num')" tabindex="1" /> </td>                    
                    <td><input type="text" name="alto_ma" id="alto_ma" value="0.00" class="ui-widget-content ui-corner-all text input_ma" style="width:55px; text-align:center" onkeypress="return permite(event,'num')" tabindex="2" /> </td>                    
                    <td><input type="text" name="espesor_ma" id="espesor_ma" value="0.00" class="ui-widget-content ui-corner-all text input_ma" style="width:55px; text-align:center" onkeypress="return permite(event,'num')" tabindex="3" /> </td>                    
                    <td><input type="text" name="volumen_ma" id="volumen_ma" value="0.00" class="ui-widget-content ui-corner-all text input_ma" style="width:68px; text-align:center" readonly="" /></td>                    
                    <td><input type="text" name="cantidad_ma" id="cantidad_ma" value="1" class="ui-widget-content ui-corner-all text input_ma" style="width:50px; text-align:center" onkeypress="return permite(event,'num')" tabindex="4" /> </td>                     
                    <td><input type="text" name="volument_ma" id="volument_ma" value="0.00" class="ui-widget-content ui-corner-all text input_ma" style="width:68px; text-align:center" readonly="" /></td>                                       
                    <td><input type="text" name="precio_ma" id="precio_ma" value="0.00" class="ui-widget-content ui-corner-all text input_ma" style="width:68px; text-align:center" onkeypress="return permite(event,'num')" tabindex="5" /> </td>                    
                    <td><input type="text" name="total_ma" id="total_ma" value="0.00" class="ui-widget-content ui-corner-all text" style="width:68px; text-align:center" readonly="" onkeypress="return permite(event,'num')"/> </td>                                 
                </tr>
            </table>                        
        </div>
    </fieldset>
    <fieldset id="box-melamina" class="ui-corner-all" style="padding: 2px 10px 7px; display:none">  
        <legend>Melamina</legend>      
        <div id="box-1">
            <table id="table-me" class="table-form" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td><label for="idmelamina" class="labels" style="width:auto">Tipo de Melamina</label></td>                    
                    <td><label for="cantidad_me" class="labels" style="width:auto">Cant. <label class="text-backinfo">Unid.</label></label></td>                                     
                    <td><label for="precio_me" class="labels" style="width:auto">Precio <label class="text-backinfo">(S/.)</label></label></td>
                    <td><label for="total_me" class="labels" style="width:auto">Total <label class="text-backinfo">(S/.)</label></label></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td rowspan="2"><a href="javascript:" id="addDetail_me" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a> </td>                    
                </tr>
                <tr>
                    <td><?php echo $linea; ?>
                    <select name="idmelamina" id="idmelamina" class="ui-widget-content ui-corner-all text" style="width:200px">
                        <option value="">Seleccione....</option>
                    </select>
                    </td>                    
                    <td><input type="text" name="cantidad_me" id="cantidad_me" value="1" class="ui-widget-content ui-corner-all text input_me" style="width:50px; text-align:center" onkeypress="return permite(event,'num')" tabindex="14" /> </td>                                         
                    <td><input type="text" name="precio_me" id="precio_me" value="0.00" class="ui-widget-content ui-corner-all text input_me" style="width:68px; text-align:center" onkeypress="return permite(event,'num')" tabindex="15" /> </td>                                        
                    <td><input type="text" name="total_me" id="total_me" value="0.00" class="ui-widget-content ui-corner-all text" style="width:68px; text-align:center" readonly="" onkeypress="return permite(event,'num')" /> </td>                    
                    <td style="width:7px">&nbsp;</td>
                    <td style="width:60px">&nbsp;</td>
                    <td style="width:64px">&nbsp;</td>
                    <td style="width:64px">&nbsp;</td>
                    <td style="width:64px">&nbsp;</td>
                </tr>
            </table>                        
        </div>
    </fieldset>
    <?php } ?>
    <div id="div-detalle">
    <div class="contain">
        <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:100% " >
                <thead class="ui-widget ui-widget-content" >
                <tr class="ui-widget-header" style="height: 23px">            
                    <th width="80px">PRODUCTO</th>            
                    <th>DESCRIPCION</th>                             
                    <th width="50px">LARGO</th>
                    <th width="50px">ALTO</th>
                    <th width="50px">ESPESOR</th>
                    <th width="60px">VOL.</th>
                    <th width="50px">CANT.</th>                    
                    <th width="60px">VOL. T.</th>
                    <th width="80px">PREC<label class="text-backinfo">(Unit S/.)</label></th>                                
                    <th width="80px">IMPORTE S/.</th>
                    <th width="20px">&nbsp;</th>
                 </tr>
                 </thead>  
                 <tbody>
                     <?php 
                        if(count($rowsd)>0)
                        {    
                            foreach ($rowsd as $i => $r) 
                            {
                                $tipo = "&nbsp;MELAMINA";
                                if($r['idtipoproducto']==1) $tipo="MADERA";
                                ?>
                                <tr class="tr-detalle">
                                    <td align="left"><?php echo $tipo; ?><input type="hidden" name="tipod[]" value="<?php echo $r['idtipoproducto']; ?>" /></td>
                                    <td><?php echo $r['descripcion'] ?><input type="hidden" name="idtipod[]" value="<?php echo $r['idproducto']; ?>" /></td>
                                    <td align="center"><?php echo number_format($r['largo'],2) ?><input type="hidden" name="largod[]" value="<?php echo $r['cantidad']; ?>" /></td>
                                    <td align="center"><?php echo number_format($r['alto'],2) ?><input type="hidden" name="altod[]" value="<?php echo $r['largo']; ?>" /></td>
                                    <td align="center"><?php echo number_format($r['espesor'],2) ?><input type="hidden" name="espesord[]" value="<?php echo $r['alto']; ?>" /></td>
                                    <td align="center"><?php echo number_format($r['espesor']*$r['alto']*$r['largo']/12,2) ?></td>
                                    <td align="center"><?php echo number_format($r['cantidad'],2) ?><input type="hidden" name="cantd[]" value="<?php echo $r['cantidad']; ?>" /></td>
                                    <td align="center"><?php echo number_format($r['espesor']*$r['alto']*$r['largo']/12*$r['cantidad'],2) ?></td>
                                    <td align="right"><?php echo number_format($r['precio'],2) ?><input type="hidden" name="preciod[]" value="<?php echo $r['precio']; ?>" /></td>
                                    <?php if($r['idtipoproducto']==1) $total = $r['espesor']*$r['alto']*$r['largo']/12*$r['cantidad']*$r['precio'];
                                            else $total = $r['cantidad']*$r['precio']; ?>
                                    <td align="right"><?php echo number_format($total,2) ?></td>
                                    <td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>
                                </tr>
                                <?php    
                            }
                        }
                     ?>             
                 </tbody>
                <tfoot>
                    <tr>
                        <td colspan="9" align="right"><b>SUB TOTAL S/.</b></td>
                        <td align="right"><b>0.00</b></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="9" align="right"><b>IGV S/.</b></td>
                        <td align="right"><b>0.00</b></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="9" align="right"><b>TOTAL S/.</b></td>
                        <td align="right"><b>0.00</b></td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
        </table>
        </div>
        </div>
</form>
</div>