
<script type="text/javascript" src="js/app/evt_form_derivar.js"></script>

<br />
<form id="box-det">
<div  id="table-per" style="margin:auto 10%; ">
    
    <input type="hidden" name="controller" value="Recepcion" />
    <input type="hidden" name="action" value="SaveDerivar" />    
    <input type="hidden" id="idtramite" name="idtramite" value="<?php echo $obj->idtramite; ?>" />
    
    <label for="tipodoc" class="labels">Tipo documento:</label>
    <?php echo $tipodoc; ?>
    <input type="text" id="correlativo" name="correlativo" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php echo $obj->codigo; ?>" readonly />
    <br />
    <?php    
        if ($obj->idtipo_documento== 1) {
        ?>    
        <label for="destinatario" class="labels">Remitente:</label>
        <?php echo $remitente; ?>
        <br/>
        
        <label for="descripcion" class="labels">Descripcion:</label><br />
            <textarea name="problema" id="problema" style="width: 95%; margin-left:16%;" class="text ui-widget-content ui-corner-all" cols="35" rows="4" ><?php echo $obj->problema; ?></textarea>
            <br />
            
        <label for="destinatario" class="labels">Destinatario:</label>
        <?php echo $personal; ?>
        <a href="javascript:" id="addDetail" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar Destiantarios</a>
        <br />
    <?php
        }
    ?>
    
    <?php
        if ($obj->idtipo_documento== 2 || $obj->idtipo_documento== 3) {
        ?>
        <label for="destinatario" class="labels">Remitente:</label>
        <?php echo $remitentes; ?>
        <br />
        
        <label for="problem">Descripción del problema</label><br />
        <textarea name="problema" id="problema" style="width: 95%; margin-left: 30px;" class="text ui-widget-content ui-corner-all" cols="35" rows="3" ><?php echo $obj->problema; ?></textarea>
        <br />
        
        <label for="resultads">Resultados</label><br />
        <textarea name="resultados" id="resultados" style="width: 95%; margin-left: 30px;" class="text ui-widget-content ui-corner-all" cols="35" rows="3" ><?php echo $obj->resultados; ?></textarea>
        <br />
        
        <label for="destinatario" class="labels">Destinatario:</label>
        <?php echo $personal; ?>
        
        <a href="javascript:" id="addDetail" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar Destiantarios</a>
        <br />
    <?php
        }
    ?>
</div> 
    
    <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:640px" border="0" >
        <thead class="ui-widget ui-widget-content" >
            <tr class="ui-widget-header" style="height: 23px">          
                <th align="center" width="120px">Tipo Docuemnto</th>               
                <th align="center" width="100px">Codigo</th>
                <th align="center" width="200px">Remitente</th>
                <th align="center" width="200px">Destinatario</th>
                <th width="20px">&nbsp;</th>
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
                            <td align="left"><?php echo $r['descripcion']; ?><input type="hidden" name="idtipopago[]" value="<?php echo $r['tipo']; ?>" /></td>
                            <td><?php echo $r['producto']; ?>
                                <input type="hidden" name="idproducto[]" value="<?php echo $r['idproducto']; ?>" />
                                <input type="hidden" name="producto[]" value="<?php echo $r['producto']; ?>" />
                                <input type="hidden" name="idfinanciamiento[]" value="<?php echo $r['idfinanciamiento']; ?>" />
                            </td>
                            <td align="rigth">
                                <?php echo $r['preciocash']; ?><input type="hidden" name="precio[]" value="<?php echo $r['preciocash']; ?>" />
                            </td>
                            <td align="rigth">
                                <?php echo $r['cantidad']; ?><input type="hidden" name="cantidad[]" value="<?php echo $r['cantidad']; ?>" />
                            </td>
                            <td>
                                <?php echo $r['inicial']; ?><input type="hidden" name="inicial[]" value="<?php echo $r['inicial']; ?>" />
                            </td>
                        </tr>
                        <?php    
                        }  
                }
             ?>                      
        </tbody>
         <tfoot>
            <tr>               
                <td colspan="5">&nbsp;</td>
            </tr>
           
        </tfoot>
    </table>
</form>