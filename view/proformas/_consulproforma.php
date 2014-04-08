<fieldset class="resport">
    <legend>Resultados de la Consulta</legend>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:880px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="120">Sucursal</th>                
                <th align="center" width="50">Serie</th>
                <th align="center" width="60">Número</th>
                <th align="center" width="150">Cliente</th>
                <th align="center" width="50">Fecha</th>
                <th align="center" width="120">Vendedor</th>
                <th align="center" width="80">Estado</th>
            </tr>
        </thead>  
        <tbody>
            <?php 
                if(count($rowsd)>0)
                {    
                    foreach ($rowsd as $i => $r) 
                    {       
                       
                        ?>
                        <tr class="tr-detalle">
                            <td align="left"><?php echo (strtoupper($r['descripcion'])); ?></td>
                            <td align="center"><?php echo $r['serie']; ?></td>
                            <td align="center"><?php echo $r['numero']; ?></td>
                            <td><?php echo (strtoupper($r['nomcliente'])); ?></td>
                            <td align="center"><?php echo $r['fecha']; ?></td>
                            <td align="left"><?php echo (strtoupper($r['vendedor'])); ?></td>
                            <td align="left"><?php echo $r['estado']; ?></td>                            
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