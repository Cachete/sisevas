<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>

   
<form id="frm_maderba" >
    <fieldset class="ui-corner-all" style="padding: 2px 10px 7px">
        <input type="hidden" name="controller" value="Caja" />
        <input type="hidden" name="action" value="save" />
    
        <label for="idcaja" class="labels">Codigo:</label>
        <input id="idcaja" name="idcaja" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->idcaja; ?>" readonly />
                
        <label for="nombre" class="labels">Nombre:</label>
        <input id="nombre" maxlength="100" name="nombre" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nombre; ?>" />
        <br>
        
        <label for="descripcion" class="labels">Descripcion:</label>
        <input id="descripcion" maxlength="100" name="descripcion" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
        
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
        <label for="tipo1">ASIGNACIÓN DE CAJA</label>        
    </div>
    
    <fieldset id="box-melamina" class="ui-corner-all" style="padding: 2px 10px 7px;">  
        <!-- <legend>Caja por Personal</legend>   -->    
        <div id="box-1">
            <table id="table-per" class="table-form" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;DNI</td>                    
                    <td>&nbsp;&nbsp;Nomber y Apellido</td> 
                    <td rowspan="3" align="center">
                        <a href="javascript:" id="addDetail" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a> 
                    </td>                      
                </tr>
                <tr>
                    <td>Buscar Personal</td>
                    <td>
                        <input type="text" name="dni" id="dni" value="" class="ui-widget-content ui-corner-all text" style="width:80px;" /> 
                    </td>                    
                    <td>
                        <input type="text" name="personal" id="personal" value="" class="ui-widget-content ui-corner-all text" style="width:250px;" />
                        <input type="hidden" name="idpersonal" id="idpersonal" value="" /> 
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
                        <th align="center" width="150">DNI</th>
                        <th>Nombre y Apellidos</th>
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
                                    <td align="left"><?php echo $r['dni']; ?><input type="hidden" name="idcajaxpersonal[]" value="<?php echo $r['idcajaxpersonal']; ?>" /></td>
                                    <td><?php echo $r['personal']; ?><input type="hidden" name="idpersonal[]" value="<?php echo $r['idpersonal']; ?>" /></td>                                    
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
