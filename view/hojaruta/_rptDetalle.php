<br />
<br />
<div>
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:840px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="90">DNI</th>
                <th width="190">Nombre y Apellidos</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Observacion</th>                
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
                            <td align="left"><?php echo $r['dni']; ?></td>
                            <td><?php echo $r['cliente']; ?><input type="hidden" name="idcliente[]" value="<?php echo $r['idcliente']; ?>" /></td>
                            <td><?php echo $r['direccion']; ?></td>
                            <td><?php echo $r['telefono']; ?></td>
                            <td><?php echo $r['producto']; ?><input type="hidden" name="idsubproductos_semi[]" value="<?php echo $r['idsubproductos_semi']; ?>" />
                                <input type="hidden" name="producto[]" value="<?php echo $r['producto']; ?>" /></td>
                            <td><?php echo $r['cantidad']; ?><input type="hidden" name="cantidad[]" value="<?php echo $r['cantidad']; ?>" /></td>
                            <td><?php echo $r['observacion']; ?><input type="hidden" name="observacion[]" value="<?php echo $r['observacion']; ?>" /></td>
                            
                        </tr>
                        <?php    
                        }  
                }
             ?>                      
        </tbody>
         <tfoot>
            <tr>
                <td colspan="7" align="right">&nbsp;</td>
                
            </tr>
            
        </tfoot>
    </table>
</div>