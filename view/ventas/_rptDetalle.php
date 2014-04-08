<br />
<br />
<div>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:540px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px"> 
                <th width="230">Producto</th>
                <th width="90">Precio</th>
                <th width="90">Cantidad</th>
                <th width="90">importe</th>               
            </tr>
        </thead>  
        <tbody>
            <?php 
                if(count($rowsd)>0)
                {    $subt=0;
                    foreach ($rowsd as $i => $r) 
                    {       
                        $imp= $r['importe'];                       
                            
                        ?>
                        <tr class="tr-detalle">
                            <td><?php echo $r['producto']; ?></td>
                            <td  align="right"><?php echo $r['precio']; ?></td>
                            <td align="right"><?php echo $r['cantidad']; ?></td>
                            <td align="right"><?php echo number_format($r['importe'],2); ?></td>                            
                            
                        </tr>
                        <?php
                        $subt= $subt + $imp;   
                    }  
                }
             ?>                      
        </tbody>
         <tfoot>
            <tr>
                <td colspan="2" align="right">&nbsp;</td>
                <td align="right">Total (S/.) :</td>
                <td align="right"><?php echo number_format($subt,2); ?></td>
            </tr>
            
        </tfoot>
    </table>
</div>