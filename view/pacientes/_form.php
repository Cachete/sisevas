<?php  
    include("../lib/helpers.php"); 
    include("../view/header_form.php");   
?>

<div style="padding:10px 20px">
<form id="frm_cliente" >
        <input type="hidden" name="controller" value="Clientes" />
        <input type="hidden" name="action" value="save" />                   
        
        <input type="hidden" id="idcliente" name="idcliente" value="<?php echo $obj->idcliente; ?>" />
        
        <div id="tabs">
            <ul style="background:#DADADA !important; border:0 !important">
                <li><a href="#tabs-1">Datos Básicos</a></li>
                <li><a href="#tabs-2">Datos de Trabajo</a></li>                
            </ul>   
            <div id="tabs-1">

                <label for="tipocliente" class="labeles">Tipo Doc.</label>
                <?php echo $TpDoc; ?>

                <label for="dni" class="labeles">DNI:</label>
                <input id="dni" name="dni" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->dni; ?>"  />
                <br/>

                <label for="nombres" class="labeles">Nombres:</label>
                <input id="nombres" name="nombres" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nombres; ?>"  />

                <label for="apepaterno" class="labeles">Apellido Paterno:</label>
                <input id="apepaterno" name="apepaterno" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->apepaterno; ?>"  />
                <br/>

                <label for="apematerno" class="labeles">Apellido Materno:</label>
                <input type="text" id="apematerno" maxlength="100" name="apematerno" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->apematerno; ?>" />

                <label for="direccion" class="labeles">Dirección:</label>
                <input id="direccion" name="direccion" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->direccion; ?>" />
                <br/>    

                <label for="Sector" class="labeles">Sector:</label>
                <input id="sector" name="sector" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->sector; ?>" />

                <label for="ocupacion" class="labeles">Actividad Economica:</label>         
                <input id="ocupacion" name="ocupacion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->ocupacion; ?>" />
                <br/>

                <label for="referencia_ubic" class="labeles">Celular:</label>         
                <input id="celular" name="celular" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->celular; ?>" />
                
                <label for="telefono" class="labeles">Telefono:</label>
                <input type="text" id="telefono" name="telefono" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->telefono; ?>" />
                <br/>
                
                <label for="estadocivil" class="labeles">Estado civil:</label>        
                <?php echo $EstadoCivil; ?>

                <label for="fecha" class="labeles">Fecha Nacim.:</label>
                <input type="text" name="fechanac" id="fechanac" class="ui-widget-content ui-corner-all text" value="<?php if($obj->fechanac!=""){echo fdate($obj->fechanac,'ES');} else {echo date('d/m/Y');} ?>" style="width:70px; text-align:right" />
                
                <br/>
                
                <label for="cargafamiliar" class="labeles">N° de Hijos:</label>
                <input id="nrohijos" name="nrohijos" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nrohijos; ?>" />
                
                <label for="sexo" class="labeles">Sexo:</label>        
                <select id="sexo" name="sexo" class="ui-widget-content ui-corner-all">
                    <?php $var="";
                        if($obj->sexo=='M')
                        {$var="selected";}               
                    ?>
                    <option <?php echo $var; ?> value="M">Masculino</option>

                    <?php $var="";
                        if($obj->sexo=='F')
                        {$var="selected";}               
                    ?>
                    <option <?php echo $var; ?> value="F">Femenino</option>
                </select>
                <br/>
                
                <label for="nivel" class="labeles">Grupo Sanguineo:</label>        
                <?php echo $gruposanguineo; ?>

                <label for="nivel" class="labeles">Nivel Educacion:</label>        
                <?php echo $NivelEducacion; ?>
                <br/>
                
                <input type="hidden" name="idubigeo" id="idubigeo" value="<?php echo $obj->idubigeo; ?>" />
                <label for="Departamento" class="labeles">Departamento:</label>
                <?php echo $Departamento; ?>
                <br/>

                <label for="Provincia" class="labeles">Provincia:</label>
                <select id="idprovincia" name="idprovincia" class="ui-widget-content ui-corner-all">            
                </select>
                
                <label for="distrito" class="labeles">Distrito:</label>        
                <select id="iddistrito" name="iddistrito" class="ui-widget-content ui-corner-all">            
                </select>
            </div>

            <div id="tabs-2">
                
                <label for="trabajo" class="labeles">Centro Trabajo:</label>         
                <input id="trabajo" name="trabajo" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->trabajo; ?>" />

                
            </div>
            
            
        </div>
        
        <label for="estado" class="labeles">Activo:</label>        
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