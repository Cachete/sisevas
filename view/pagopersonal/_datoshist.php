<fieldset>
    <legend>Datos de produccion:</legend>
    <br />
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:600px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px"> 
                <th width="230">Producción</th>
                <th width="90">Fecha Inicio</th>
                <th width="90">Fecha Fin</th>
                <th width="190">Tipo de Producción</th>               
            </tr>
        </thead>  
        <tbody>
            <?php
                //$cabecera[0]['serie']
                if(count($produccion)>0)
                {    $subt=0;
                    foreach ($produccion as $i => $r) 
                    {       
                         
                        ?>
                        <tr class="tr-detalle">
                            <td><?php echo $r['produccion']; ?></td>
                            <td  align="center"><?php echo $r['fechaini']; ?></td>
                            <td align="center"><?php echo $r['fechafin']; ?></td>
                            <td align="left"><?php echo $r['descripcion']; ?></td>
                        </tr>
                        <?php
                           
                    }  
                }
             ?>                      
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="right">&nbsp;</td>
            </tr>
        </tfoot>
    </table>
</fieldset>
<br />

<fieldset>
    <legend>Datos de acabado de los muebles:</legend>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:540px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px"> 
                <th width="230">Producto</th>
                <th width="90">Cantidad</th>
                <th width="90">Fecha Inicio</th>
                <th width="90">Fecha Fin</th>               
            </tr>
        </thead>  
        <tbody>
            <?php
                //$cabecera[0]['serie']
                if(count($acabado)>0)
                {    $subt=0;
                    foreach ($acabado as $ii => $re) 
                    {       
                         
                        ?>
                        <tr class="tr-detalle">
                            <td><?php echo $re['producto']; ?></td>
                            <td  align="right"><?php echo $re['cantidad']; ?></td>
                            <td align="center"><?php echo $re['fechaini']; ?></td>
                            <td align="center"><?php echo $re['fechafin']; ?></td>
                        </tr>
                        <?php
                           
                    }  
                }
             ?>                      
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="right">&nbsp;</td>
            </tr>
        </tfoot>
    </table>
</fieldset>
