<br />
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
        </tr>
    </thead>  
    <tbody>
        <?php 
            if(count($rowsd)>0)
            {   
                $to=0;
                foreach ($rowsd as $i => $r) 
                {


                    if($r['idtipoproducto']==1) 
                    $total = $r['espesor']*$r['alto']*$r['largo']/12*$r['cantidad']*$r['precio'];
                    else $total = $r['cantidad']*$r['precio']; 

                    $tipo = "&nbsp;MELAMINA";
                    if($r['idtipoproducto']==1) $tipo="MADERA";

                    $to= $to +$total;
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
                        <td align="right"><?php echo number_format($total,2) ?></td>
                    </tr>
                    <?php    
                }
            }
        ?>
    </tbody>
    <tfoot>
        <!-- <tr>
            <td colspan="9" align="right"><b>SUB TOTAL S/.</b></td>
            <td align="right"><b>0.00</b></td>
            <td>&nbsp;</td>
        </tr> -->
        <!-- <tr>
            <td colspan="9" align="right"><b>IGV S/.</b></td>
            <td align="right"><b>0.00</b></td>
            <td>&nbsp;</td>
        </tr> -->
        <tr>
            <td colspan="9" align="right"><b>TOTAL S/.</b></td>
            <td align="right"><b><?php echo number_format($to,2); ?></b></td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>