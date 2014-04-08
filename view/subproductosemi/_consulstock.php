<style type="text/css">
    .cab{

        border: 1px solid #A3A3A3;
        padding: 5px;
        background: #E1E1E1;
        margin-left: 20%;
        margin-bottom: 5px;
        -moz-border-radius: 1em 1em 1em 1em;
        border-radius: 1em 1em 1em 1em;
    }
</style>

<fieldset class="resport">
    <legend>Resultados de la Consulta</legend>
    <?php   
        foreach ($rowsd as $i => $r) 
        {
        
        ?>
        <div class="cab" style="width:200px;">ALMACEN : <b><?php echo $r['almacen']; ?></b></div>
            <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:450px" border="0" >
                <thead class="ui-widget ui-widget-content" >
                    <tr class="ui-widget-header" style="height: 23px">            
                        <th align="left" width="300">Producto</th>
                        <th align="center" width="150">Total Stock</th>                
                    </tr>
                </thead>  
                <tbody>
                <?php 
                    foreach ($r['detalle'] as $f => $d) 
                    {
                    
                    ?>
                    
                    <tr class="tr-detalle">
                        
                        <td align="left"><?php echo strtoupper($d['producto']); ?></td>
                        <td align="center"><?php echo $d['ctotal']; ?></td>                                                    
                    </tr>
                        

                <?php
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>                           
                    </tr>                
                </tfoot>
            </table>
            <br /><br />
        <?php                    
        }
    ?>
</fieldset>
<br />
<br />