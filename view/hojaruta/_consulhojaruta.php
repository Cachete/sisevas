<fieldset class="resport">
    <legend>Resultados de la Consulta</legend>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:880px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="110">Zona</th>                
                <th align="center" width="110">Ruta</th>
                <th align="center" width="60">Fecha</th>
                <th align="center" width="130">Personal</th>
                <th align="center" width="30">Ver</th>
                
            </tr>
        </thead>  
        <tbody>
            <?php 
                if(count($rowsd)>0)
                {    
                    foreach ($rowsd as $i => $r) 
                    {       
                        $id= $r['idhojarutas'];
                        ?>
                        <tr class="tr-detalle">
                            <td align="left"><?php echo (strtoupper($r['zona'])); ?></td>
                            <td align="left"><?php echo (strtoupper($r['ruta'])); ?></td>
                            <td align="center"><?php echo $r['fechareg']; ?></td>
                            <td><?php echo (strtoupper($r['nompersonal'])); ?></td> 
                            <td align="center">
                                <a href="javascript:popup('index.php?controller=hojaruta&action=detalle&id=<?php echo $id; ?>',870,350)" class="box-boton boton-search" title="Ver detalle">&nbsp;</a>
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
                
            </tr>
            
        </tfoot>
    </table>
    
</fieldset>
<br />
<br />