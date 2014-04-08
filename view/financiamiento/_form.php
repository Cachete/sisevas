<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>

   
<form id="frm_maderba" >
    <fieldset class="ui-corner-all" style="padding: 2px 10px 7px">
        <input type="hidden" name="controller" value="Financiamiento" />
        <input type="hidden" name="action" value="save" />
    
        <label for="idfinanciamiento" class="labels">Codigo:</label>
        <input id="idfinanciamiento" name="idfinanciamiento" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->idfinanciamiento; ?>" readonly />
                
        <label for="descripcion" class="labels">Descripcion:</label>
        <input id="descripcion" maxlength="100" name="descripcion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
        <br>
        
        <label for="adicional" class="labels">Adicional:</label>
        <input id="adicional" maxlength="100" name="adicional" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->adicional; ?>" />
        <label for="inicial" class="labels">Inicial M&iacute;nima:</label>
        <input id="inicial" maxlength="100" name="inicial" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->inicial; ?>" />
        <br>
        <label for="estado" class="labels">Activo:</label>
        <div id="estados" style="display:inline">
            <?php                   
                if($obj->estado==1 || $obj->estado==0)
                {
                    if($obj->estado==1){$rep=1;}
                    else {$rep=0;}
                }
                else {$rep = 1;}                    
                    activo('activo',$rep);
            ?>
        </div>
    </fieldset>

    <div id="box-tipo-ma" class="ui-widget-header ui-state-hover" style="color: #000000;text-align:center">
        <label for="tipo1">FACTORES</label>        
    </div>
    
    <fieldset id="box-melamina" class="ui-corner-all" style="padding: 2px 10px 7px;">  
        <!-- <legend>Caja por Personal</legend>   -->    
        <div id="box-1">
            <table id="table-per" class="table-form" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Nro Meses</td>                    
                    <td>&nbsp;&nbsp;Factor</td> 
                    <td rowspan="3" align="center">
                        <a href="javascript:" id="addDetail" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a> 
                    </td>                      
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type="text" name="nromes" id="nromes" onkeypress="return permite(event,'num');"  value="" class="ui-widget-content ui-corner-all text" style="width:80px;" /> 
                    </td>                    
                    <td>
                        <input type="text" name="factor" id="factor" onkeypress="return permite(event,'num');"  value="" class="ui-widget-content ui-corner-all text" style="width:250px;" />
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>                    
                    <td>&nbsp;</td> 
                </tr>
            </table>                        
        </div>
    </fieldset>
    <div id="div-detalle">
        <div>
            <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:100% " border="0" >
                <thead class="ui-widget ui-widget-content" >
                    <tr class="ui-widget-header" style="height: 23px">          
                        <th align="center" width="150">Meses</th>
                        <th width="150">Factor</th>
                        <th width="20px">&nbsp;</th>
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
                                    <td align="left"><?php echo $r['meses']; ?><input type="hidden" name="meses[]" value="<?php echo $r['meses']; ?>" /></td>
                                    <td><?php echo number_format($r['factor'],2); ?><input type="hidden" name="factor[]" value="<?php echo $r['factor']; ?>" /></td>                                    
                                    <td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>
                                </tr>
                                <?php    
                            }
                        }
                     ?>                      
                </tbody>
                <tfoot>
                    <tr>
                        <td align="right">&nbsp;</td>  
                        <td align="right">&nbsp;</td>                        
                        <td>&nbsp;</td>
                    </tr>               
                </tfoot>
            </table>
        </div>
    </div> 

</form>
