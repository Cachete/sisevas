<fieldset>
    <legend>Datos de los pagos:</legend>
    <br />
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:600px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px"> 
                <th width="230">Motivo de pago</th>
                <th width="90">N° Recibo</th>
                <th width="90">Importe</th>
                <th width="90">Fecha Pago</th>  
                <th width="90">Hora Pago</th>             
            </tr>
        </thead>  
        <tbody>
            <?php
                //$cabecera[0]['serie']
                if(count($rowsd)>0)
                {    $subt=0;
                    foreach ($rowsd as $i => $r) 
                    {       
                         
                        ?>
                        <tr class="tr-detalle">
                            <td><?php echo $r['motivo']; ?></td>
                            <td  align="center"><?php echo $r['nrorecibo']; ?></td>
                            <td align="rigth"><?php echo $r['importe']; ?></td>
                            <td align="center"><?php echo $r['fechacancelacion']; ?></td>
                            <td align="center"><?php echo $r['horapago']; ?></td>
                        </tr>
                        <?php
                           
                    }  
                }
             ?>                      
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" align="right">&nbsp;</td>
            </tr>
        </tfoot>
    </table>
</fieldset>
