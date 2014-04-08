<div>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:840px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="120">Tipo Pago</th>
                <th>Producto</th>
                <th align="center" width="80">Precio</th>
                <th align="center" width="80">Cantidad</th>
                <th align="center" width="80">Inicial</th>
                <th align="center" width="80">Mensual</th>
                <th align="center" width="90">N° Meses</th>
                <th align="center" width="80px">IMPORTE S/.</th>
                <th width="20px">&nbsp;</th>
            </tr>
        </thead>  
        <tbody>
            <?php 
                if(count($rowsd)>0)
                {    
                    foreach ($rowsd as $i => $r) 
                    {       
                        $nro= $r['nromeses'];
                        $men= $r['cuota'];                                        
                        $ini= $r['inicial'];
                        $subt= (floatval($nro) * floatval($men))+ $ini;
                            
                        ?>
                        <tr class="tr-detalle">
                            <td align="left"><?php echo $r['descripcion']; ?><input type="hidden" name="idtipopago[]" value="<?php echo $r['tipo']; ?>" /></td>
                            <td><?php echo $r['producto']; ?>
                                <input type="hidden" name="idproducto[]" value="<?php echo $r['idproducto']; ?>" />
                                <input type="hidden" name="producto[]" value="<?php echo $r['producto']; ?>" />
                                <input type="hidden" name="idfinanciamiento[]" value="<?php echo $r['idfinanciamiento']; ?>" />
                            </td>
                            <td align="rigth">
                                <?php echo $r['preciocash']; ?><input type="hidden" name="precio[]" value="<?php echo $r['preciocash']; ?>" />
                            </td>
                            <td align="rigth">
                                <?php echo $r['cantidad']; ?><input type="hidden" name="cantidad[]" value="<?php echo $r['cantidad']; ?>" />
                            </td>
                            <td>
                                <?php echo $r['inicial']; ?><input type="hidden" name="inicial[]" value="<?php echo $r['inicial']; ?>" />
                            </td>
                            <td>
                                <?php echo $r['cuota']; ?><input type="hidden" name="mensual[]" value="<?php echo $r['cuota']; ?>" />
                            </td>
                            <td>
                                <?php echo $r['nromeses']; ?><input type="hidden" name="nromeses[]" value="<?php echo $r['nromeses']; ?>" />
                            </td>
                            <td><?php echo $subt; ?></td>
                            <td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>
                        </tr>
                        <?php    
                        }  
                }
             ?>                      
        </tbody>
         <tfoot>
            <tr>
                <td colspan="7" align="right"><b>SUB TOTAL S/.</b></td>
                <td align="right"><b>0.00</b></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="7" align="right"><b>IGV S/.</b></td>
                <td align="right"><b>0.00</b></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="7" align="right"><b>TOTAL S/.</b></td>
                <td align="right"><b>0.00</b></td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
</div>