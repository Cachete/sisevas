<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>
<style type="text/css">
.custom-input-file {
    overflow: hidden;
    position: relative;
    cursor: pointer;
    border: 1px solid #58ACFA;
    border-radius: 5px;
    background-color: #58ACFA;
    color: #000;
    text-align: center;
    font-family: verdana;
    font-size: 12pt;
    width: 200px;
    min-height: 40px;
    margin-left: 35%;
    margin-right: 5%;
}
.custom-input-file:hover {
    background-color: #58ACFA;
    color: #fff;
}
.custom-input-file .input-file {
    margin: 0;
    padding: 0;outline:0;
    font-size: 10000px;
    border: 10000px solid transparent;
    opacity: 0;
    filter: alpha(opacity=0);
    position: absolute;
    right: -1000px;
    top: -1000px;
    cursor: pointer;
}
.custom-input-file .archivo {
    background-color: #000;
    color: #fff;
    font-size: 7pt;
    overflow: hidden;
}
.custom-input-file:hover .archivo {
    background-color: #fff;
    color: #000;   
}

</style>

<div style="padding:10px 20px">
<form id="frm" >

    <input type="hidden" name="controller" value="Personal" />
    <input type="hidden" name="action" value="save" />
    <input type="hidden" id="idpersonal" name="idpersonal" value="<?php echo $obj->idpersonal; ?>" />
    <div id="tabs">
        <ul style="background:#DADADA !important; border:0 !important">
            <li><a href="#tabs-1">Información Personal</a></li>
            <li><a href="#tabs-2">Información Laboral</a></li>
            <li><a href="#tabs-3">Otros</a></li>
            <li><a href="#tabs-4">File</a></li>
        </ul>
        <div id="tabs-1">

            <label for="docidentidad" class="labels">Doc. Identidad:</label>        
            <?php echo $documentoidentidad; ?>
                    
            <label for="dni" class="labels">DNI:</label>
            <input id="dni" name="dni" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->dni; ?>"  />
            <br />

            <label for="nombres" class="labels">Nombres:</label>
            <input type="text" id="nombres" maxlength="100" name="nombres" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nombres; ?>" />
            
            <label for="apellidos" class="labels">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->apellidos; ?>" />
            <br/> 

            <label for="telefono" class="labels">Telefono:</label>
            <input type="text" id="telefono" name="telefono" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->telefono; ?>" />
            
            <label for="fechanaci" class="labels">Fecha Nac:</label>
            <input type="text" id="fechanaci" maxlength="10" name="fechanaci" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php if($obj->fechanaci=='') echo date('d/m/Y'); else echo fdate($obj->fechanaci,'ES'); ?>" />
            <br/>

            <label for="direccion" class="labels">Dirección:</label>
            <input id="direccion" name="direccion" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->direccion; ?>" />
            
            <label for="estcivil" class="labels">Estado civil:</label>        
            <?php echo $EstadoCivil; ?>
            <br/>

            <label for="email" class="labels">Email:</label>  
            <input id="email" name="email" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->mail; ?>" />
            
            <label for="sexo" class="labels">Sexo:</label>        
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

        </div>
        <div id="tabs-2">

            <label for="tipopersonal" class="labels">Tipo Personal:</label>        
            <?php echo $tipopersonal; ?>

            <label for="Especialidad" class="labels">Especialidad:</label>
            <?php echo $especialidad; ?>
            <br/>

            <label for="grado" class="labels">Grado Instruccion:</label>
            <?php echo $grado; ?>

            <label for="idcargo" class="labels">Cargo:</label>
            <?php echo $idcargo; ?>
            <br/>

            <label for="estcivil" class="labels">Consultorio:</label>        
            <?php echo $consultorio; ?>

            <label for="perfil" class="labels">Perfil:</label>
            <?php echo $Perfil; ?>   
            <br/>

            <label for="fechaing" class="labels">Fecha Ingreso:</label>
            <input type="text" id="fechaing" maxlength="10" name="fechaing" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php if($obj->fechareg=='') echo date('d/m/Y'); else echo fdate($obj->fechareg,'ES'); ?>" />
            <br/>

            <label for="user" class="labels">Usuario:</label>
            <input id="usuario" name="usuario" value="<?php echo $obj->usuario; ?>" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;"  />
            
            <label for="clae" class="labels">Clave:</label>
            <input type="password" id="clave" name="clave" value="<?php echo $obj->clave; ?>" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;"  />
            
        </div>

        <div id="tabs-3">

            <label for="ruc" class="labels">RUC:</label>
            <input id="ruc" name="ruc" onkeypress="return permite(event,'num');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->ruc; ?>"  />
            
            <label for="codafp" class="labels">Cod. AFP:</label>
            <input id="codafp" name="codafp" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->codafp; ?>"  />
            <br/>

            <label for="ruc" class="labels">Cod. Essalud:</label>
            <input id="codessalud" name="codessalud" onkeypress="return permite(event,'num_car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->codessalud; ?>"  />
            <br/>
        </div>
        <div id="tabs-4">            
            <input type="hidden" name="archivo" id="archivo" value="<?php echo $obj->file; ?>" />
                <?php
                    if($obj->file!="")
                        $d = "inline";
                    else
                        $d = "none";
                ?>
		<div id="queue"></div>
		<input id="file_upload" name="file_upload" type="file" multiple="true">    
                <a target="_blank" href="files/<?php echo $obj->file ?>" style="display:<?php echo $d; ?>;cursor:pointer; font-size: 11px;" id="VerImagennn"><img src="images/pdf.png" />Abrir Archivo</a>
        </div>

    </div>        
        
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