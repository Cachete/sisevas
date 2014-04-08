
<fieldset class="resport">
    <legend>Resultados de la Consulta</legend>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:880px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="150">Cliente</th>                
                <th align="center" width="80">Tipo Documento</th>
                <th align="center" width="60">N° Recibo</th>
                <th align="center" width="100">Tipo Pago</th>
                <th align="center" width="80">Fecha de venta</th>
                <th align="center" width="90">Total</th>
                <th align="center" width="80">Estado</th>
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
                            <td align="left"><?php echo (strtoupper($r['nomcliente'])); ?></td>
                            <td align="left"><?php echo strtoupper($r['tipodoc']); ?></td>
                            <td align="center"><?php echo $r['documentonumero']; ?></td>
                            <td><?php echo (strtoupper($r['tipopag'])); ?></td>
                            <td align="center"><?php echo $r['fechareg']; ?></td>
                            <td align="rigth"><?php echo strtoupper($r['total']); ?></td>
                            <td align="center">
                                <a href="javascript:popup('index.php?controller=ventas&action=detallertp&id=<?php echo $id; ?>',870,350)" class="box-boton boton-search" title="Ver detalle">&nbsp;</a>
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
            </tr>
            
        </tfoot>
    </table>
    
</fieldset>
<br />
<br />
