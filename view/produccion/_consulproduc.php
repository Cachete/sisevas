<fieldset class="resport">
    <legend>Resultados de la Consulta</legend>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:880px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="120">Almacen</th>                
                <th align="center" width="120">Produccion</th>
                <th align="center" width="130">Personal</th>
                <th align="center" width="60">Fecha Inicial</th>
                <th align="center" width="60">Fecha Final</th>
                <th align="center" width="80">Estado</th>
                <th align="center" width="30">Ver</th>
            </tr>
        </thead>  
        <tbody>
            <?php 
                if(count($rowsd)>0)
                {    
                    foreach ($rowsd as $i => $r) 
                    {       
                        $id= $r['idproduccion'];
                        ?>
                        <tr class="tr-detalle">
                            <td align="left"><?php echo (strtoupper($r['almacen'])); ?></td>
                            <td align="left"><?php echo (strtoupper($r['produccion'])); ?></td>
                            <td align="left"><?php echo (strtoupper($r['personal'])); ?></td>
                            <td align="center"><?php echo $r['fechaini']; ?></td>
                            <td align="center"><?php echo $r['fechafin']; ?></td>
                            <td align="left"><?php echo $r['estado']; ?></td>
                            <td align="center">
                                <a href="javascript:popup('index.php?controller=produccion&action=detalle&id=<?php echo $id; ?>',870,350)" class="box-boton boton-search" title="Ver detalle">&nbsp;</a>
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