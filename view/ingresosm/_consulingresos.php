<fieldset class="resport">
    <legend>Resultados de la Consulta</legend>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:880px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="130">Referencia</th>                
                <th align="center" width="35">Abreviado</th>
                <th align="center" width="50">Serie</th>
                <th align="center" width="50">Número</th>
                <th align="center" width="140">Proveedor</th>
                <th align="center" width="55">RUC</th>
                <th align="center" width="40">Total</th>  
                <th align="center" width="25">Ver</th>
            </tr>
        </thead>  
        <tbody>
            <?php 
                if(count($rowsd)>0)
                {    
                    foreach ($rowsd as $i => $r) 
                    {       
                        $id= $r['idmovimiento'];
                        ?>
                        <tr class="tr-detalle">
                            <td align="left"><?php echo (strtoupper($r['referencia'])); ?></td>
                            <td align="left"><?php echo (strtoupper($r['abreviado'])); ?></td>
                            <td align="center"><?php echo $r['serie']; ?></td>
                            <td align="center"><?php echo $r['numero']; ?></td>
                            <td align="left"><?php echo (strtoupper($r['proveedor'])); ?></td>
                            <td align="center"><?php echo $r['ruc']; ?></td>
                            <td align="right"><?php echo $r['total']; ?></td>
                            <td align="center">
                                <a href="javascript:popup('index.php?controller=ingresom&action=detalle&id=<?php echo $id; ?>',870,350)" class="box-boton boton-search" title="Ver detalle">&nbsp;</a>
                            </td>                           
                        </tr>
                        <?php    
                        }  
                }
             ?>                      
        </tbody>
        <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td> 
                <td>&nbsp;</td>               
            </tr>            
        </tfoot>
    </table>
    
</fieldset>
<br />
<br />