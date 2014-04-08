<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
       
?>

<div style="padding:10px 20px">
<form id="frm_proveedor" >
        <input type="hidden" name="controller" value="Proveedor" />
        <input type="hidden" name="action" value="save" />
                     
        <label for="idproveedor" class="labels">Codigo:</label>
        <input type="text" id="idproveedor" name="idproveedor" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->idproveedor; ?>" readonly />
        
        <label for="dni" class="labels">DNI:</label>
        <input id="dni" name="dni" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->dni; ?>"  />
        
        <br/>
        
        <label for="ruc" class="labels">RUC:</label>
        <input id="ruc" name="ruc" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->ruc; ?>"  />
        
        <label for="razonsocial" class="labels">Razon Social:</label>
   		<input id="razonsocial" name="razonsocial" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->razonsocial; ?>"  />
        <br/>

        <label for="replegal" class="labels">Rep. legal:</label>
        <input type="text" id="replegal" maxlength="100" name="replegal" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->replegal; ?>" />
        
        <label for="direccion" class="labels">Dirección:</label>
        <input id="direccion" name="direccion" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->direccion; ?>" />
        <br/>    

        <label for="telefono" class="labels">Telefono:</label>
        <input type="text" id="telefono" name="telefono" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->telefono; ?>" />
        
        <label for="contacto" class="labels">Contacto:</label>
        <input type="text" id="contacto" name="contacto" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->contacto; ?>" />
        <br/>
        
        <label for="email" class="labels">Email:</label>
   	<input id="email" name="email" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->email; ?>" />
        
        <label for="obs" class="labels">Observacion:</label>         
        <input id="obs" name="obs" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->obs; ?>" />
        <br/>
        
        <label for="Departamento" class="labels">Departamento:</label>
        <?php echo $Departamento; ?>

        <label for="Provincia" class="labels">Provincia:</label>
        <select id="idprovincia" name="idprovincia" class="ui-widget-content ui-corner-all">            
        </select>
        <br/>
        <input type="hidden" name="idubigeo" id="idubigeo" value="<?php echo $obj->idubigeo; ?>" />
        <label for="distrito" class="labels">Distrito:</label>        
        <select id="iddistrito" name="iddistrito" class="ui-widget-content ui-corner-all">            
        </select>
        <input type="hidden" name="iddist" id="iddist" value="<?php echo $obj->idubigeo; ?>" />
        
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
</form>
</div>