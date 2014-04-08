<?php  
    include("../lib/helpers.php"); 
    include("../view/header_form.php");   
?>

<div style="padding:10px 20px">
<form id="frm_cliente" >
        <input type="hidden" name="controller" value="Clientes" />
        <input type="hidden" name="action" value="save" />
                     
        <label for="idcliente" class="labeles">Codigo:</label>
        <input type="text" id="idcliente" name="idcliente" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->idcliente; ?>" readonly />
        
        <div id="tabs">
            <ul style="background:#DADADA !important; border:0 !important">
                <li><a href="#tabs-1">Datos Básicos</a></li>
                <li><a href="#tabs-2">Datos de Trabajo</a></li>
                <li><a href="#tabs-3">Ingresos</a></li>
                <li><a href="#tabs-4">Conyugue</a></li>
            </ul>   
            <div id="tabs-1">
                <label for="dni" class="labeles">RUC / DNI:</label>
                <input id="dni" name="dni" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->dni; ?>"  />
                
                <label for="tipocliente" class="labeles">Tipo Cliente</label>
                <?php echo $TipoCliente; ?>
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

                <label for="referencia_ubic" class="labeles">Referencia Ubi.:</label>         
                <input id="referencia_ubic" name="referencia_ubic" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->referencia_ubic; ?>" />
                
                <label for="telefono" class="labeles">Telefono:</label>
                <input type="text" id="telefono" name="telefono" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->telefono; ?>" />
                <br/>
                
                <label for="estadocivil" class="labeles">Estado civil:</label>        
                <?php echo $EstadoCivil; ?>

                <label for="fecha" class="labeles">Fecha Nacim.:</label>
                <input type="text" name="fechanac" id="fechanac" class="ui-widget-content ui-corner-all text" value="<?php if($obj->fechanac!=""){echo fdate($obj->fechanac,'ES');} else {echo date('d/m/Y');} ?>" style="width:70px; text-align:right" />
                
                <br/>
                
                <label for="cargafamiliar" class="labeles">Carga Familiar:</label>
                <input id="carga_familiar" name="carga_familiar" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->carga_familiar; ?>" />
                
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
             
                <label for="nivel" class="labeles">Nivel Educacion:</label>        
                <?php echo $NivelEducacion; ?>
                
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
                <label for="Profesion" class="labeles">Profesión:</label>
                <input id="profesion" name="profesion" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->profesion; ?>" />

                <label for="antitrab" class="labeles">Anterior trab. :</label>         
                <input id="antitrab" name="antitrab" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->antitrab; ?>" />
                <br/>

                <label for="trabajo" class="labeles">Trabajo:</label>         
                <input id="trabajo" name="trabajo" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->trabajo; ?>" />

                <label for="dirtrabajo" class="labeles">Direc. trabajo:</label>
                <input id="dirtrabajo" name="dirtrabajo" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->dirtrabajo; ?>" />
                <br/>

                <label for="cargo" class="labeles">Cargo:</label>         
                <input id="cargo" name="cargo" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->cargo; ?>" />

                <label for="teltrab" class="labeles">Telefono trab.:</label>         
                <input id="teltrab" name="teltrab" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->teltrab; ?>" />
                <br/>
            </div>
            <div id="tabs-3">
                <label for="ingreso" class="labeles">Ingreso:</label>         
                <input id="ingreso" name="ingreso" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->ingreso; ?>" />

                <label for="teltrab" class="labeles">Tipo vivienda:</label>         
                <?php echo $TipoVivienda; ?>
                <br/>

                <label for="rlegal" class="labeles">Represent. legal:</label>         
                <input id="rlegal" name="rlegal" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->rlegal; ?>" />

                <label for="nropartida" class="labeles">N° partida.:</label>         
                <input id="nropartida" name="nropartida" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nropartida; ?>" />
                <br/>
            </div>
            <div id="tabs-4">
                <table id="table-per" class="table-form" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;&nbsp;DNI</td>                    
                        <td>&nbsp;&nbsp;Nomber y Apellido</td> 
                        <td rowspan="3" align="center">
                            &nbsp;
                            <!-- <a href="javascript:" id="addDetail" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset">
                            <span class="ui-icon ui-icon-plusthick"></span>Agregar</a> -->
                        </td>                      
                    </tr>
                    <tr>
                        <td>Buscar Conyugue</td>
                        <td>
                            <input type="text" name="dnicon" id="dnicon" value="<?php echo $obj->dnicon; ?>" class="ui-widget-content ui-corner-all text" style="width:80px;" /> 
                        </td>                    
                        <td>
                            <input type="text" name="conyugue" id="conyugue" value="<?php echo $obj->conyugue; ?>" class="ui-widget-content ui-corner-all text" style="width:250px;" />
                            <input type="hidden" name="idpareja" id="idpareja" value="<?php echo $obj->idconyugue; ?>" /> 
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>                    
                        <td>&nbsp;</td> 
                    </tr>
                </table>                
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