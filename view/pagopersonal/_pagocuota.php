<script type="text/javascript" src="../web/js/app/evt_index_ventas.js"></script>

<br />
<fieldset class="resports">
    <legend>Datos de la Venta</legend>            
    <br />
    <fieldset style="width:560px; float:left; margin-left:20px; padding:10px;">
        <legend>Pago de cuotas</legend>

        <span id="box-pay-doc">
            <label class="labels">Documento: </label>            
            <label class="text-super">RECIBO DE INGRESO</label>
        </span> 
        <br/>
        <span>
            <label class="labels">Forma de pago: </label>
            <?php echo $formapago2; ?>
        </span>
        <br />
        <span id="box-pay-tarjeta" style="display:none;margin-left:80px;">
         <input type="text" name="nrotarjeta" id="nrotarjeta" value="" class="ui-widget-content ui-corner-all text" placeholder="N&deg; de Tarjeta" />
         <input type="text" name="nrovoucher" id="nrovoucher" value="" class="ui-widget-content ui-corner-all text" placeholder="N&deg; de Voucher" style="width:200px" />
        </span>
        <span id="box-pay-cheque" style="display:none;margin-left:80px;">
            <input type="text" name="nrocheque" id="nrocheque" value="" class="ui-widget-content ui-corner-all text" placeholder="N&deg; de Cheque" />
            <input type="text" name="banco" id="banco" value="" class="ui-widget-content ui-corner-all text" placeholder="Banco del cheque" style="width:200px" />                                
            <input type="text" name="fechav" id="fechav" value="" class="ui-widget-content ui-corner-all text text-date"  placeholder="Fecha Vencimiento" />
        </span>
        <br/>

        <label class="labels">Monto: </label>
        <input type="text" name="monto_efectivo" id="monto_efectivo" value="0.00"  class="ui-widget-content ui-corner-all text text-num" />
        S/. <a href="javascript:" id="add-fp" style="color:green">Agregar</a>

        <div class="contain" style="">
            <table id="table-detalle-pagos" class="ui-widget ui-widget-content" border="0" >
                <thead>
                    <tr class="ui-widget-header">
                        <td width="120px" align="center">Forma de Pago</td>                             
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
                    <!-- <tr>
                        <td align="right" colspan="2">Monto Faltante:</td>
                        <td align="right">0.00</td>
                        <td>&nbsp;</td>
                    </tr> -->
                </tfoot>
            </table>  
        </div>

    </fieldset>

    <fieldset style="width:300px; float:right; margin-right:20px;">
        <legend>Cuotas de la venta</legend>
        <table class="ui-widget ui-widget-content" border="0" style="margin-left:20px;" width="300px" >
            <thead>
                <tr class="ui-widget-header">
                    <th>Monto a pagar</td>
                    <th>Monto pagado</td>
                    <th>Monto Pendiente</td>
                    <th>Fecha de pago</td>
                </tr>
            </thead>
            <tbody>
            <?php 
                if(count($rowsd)>0)
                {    
                    foreach ($rowsd as $i => $r) 
                    {       
                        $m= (float)$r['monto'];
                        $ms= (float)$r['monto_saldado'];
                        $p= $m - $ms;
                        ?>
                    <tr>
                        <td align="right"><?php echo number_format($r['monto'],2); ?></td>
                        <td align="right"><?php echo number_format($r['monto_saldado'],2); ?></td>
                        <td align="right"><?php echo number_format($p,2); ?></td>
                        <td align="center"><?php echo strtoupper($r['fechapago']); ?></td>
                    </tr>
                     <?php    
                    }  
                }
            ?>
            </tbody>
            
        </table>
    </fieldset>
</fieldset>