<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>
<script type="text/javascript" src="js/app/evt_form_user.js" ></script>
<script type="text/javascript" src="js/validateradiobutton.js"></script>
<style>
    .labels { width: 130px;}
</style>
<?php 
    $readonly = "";
    $op=0;
    if($obj->idempleado!="")
        {
            $readonly="readonly";
            $op=1;
        }        
?>
<div class="div_container">
<h6 class="ui-widget-header">&nbsp;</h6>
<?php echo $more_options; ?>     
<div class="contain2"> 
<?php header_form('Mantenimiento de Empleados'); ?>   
    <form id="frm" action="index.php" method="POST">
    <input type="hidden" name="controller" value="user" />
    <input type="hidden" name="action" value="save" />
    <div class="contFrm" style="background: #fff;">
        <div style="margin:0 auto; ">                
                <label for="idusuario" class="labels">DNI:</label>
                <input type="text" id="idempleado" name="idempleado" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php echo $obj->idempleado; ?>" <?php echo $readonly; ?> title="Ingrese su DNI" maxlength="8" onkeypress="return permite(event,'num')" />
                <br/>
                <label for="idperfil" class="labels">Perfil:</label>
                <?php echo $perfil; ?>
                <br/>
                <label for="idtipo_empleado" class="labels">Tipo Empleado:</label>
                <?php echo $tipo_empleado; ?>
                <br/>
                <label for="idoficina" class="labels">Oficina:</label>
                <?php echo $oficina; ?>
                <br/>                
                <label for="nombre" class="labels">Nombres:</label>
                <input type="text" id="nombre" maxlength="100" name="nombre" class="text ui-widget-content ui-corner-all" style=" width: 300px; text-align: left;" value="<?php echo $obj->nombre; ?>" />
                <br/>
                <label for="apellidos" class="labels">Apellidos:</label>
                <input type="text" id="apellidos" maxlength="100" name="apellidos" class="text ui-widget-content ui-corner-all" style=" width: 300px; text-align: left;" value="<?php echo $obj->apellidos; ?>" />
                <br/>
                <label for="aleas" class="labels">Apodo:</label>
                <input type="text" id="aleas" maxlength="100" name="aleas" class="text ui-widget-content ui-corner-all" style=" width: 300px; text-align: left;" value="<?php echo $obj->aleas; ?>" />
                <br/>                
                <label for="aleas" class="labels">RUC:</label>
                <input type="text" id="ruc" maxlength="100" name="ruc" class="text ui-widget-content ui-corner-all" style=" width: 300px; text-align: left;" value="<?php echo $obj->ruc; ?>" />
                <br/>
                <label for="fecha_nacimiento" class="labels">Fecha Nacimiento:</label>
                <input type="text" id="fecha_nacimiento" maxlength="10" name="fecha_nacimiento" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php if($obj->fecha_nacimiento!="") {echo fdate($obj->fecha_nacimiento,'ES');} else {echo date('d/m/Y');} ?>" />
                <br/>
                <label for="fecha_registro" class="labels">Fecha Ingreso:</label>
                <input type="text" id="fecha_registro" maxlength="10" name="fecha_registro" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php if($obj->fecha_registro!="") {echo fdate($obj->fecha_registro,'ES');} else {echo date('d/m/Y');} ?>" />
                <br/>
                <label for="login" class="labels">Login:</label>
                <input id="login" maxlength="100" name="login" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->login; ?>" />
                <br/>
                <label for="password" class="labels">Password:</label>
                <input type="password" id="password" maxlength="100" name="password"  class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->password; ?>" />
                <br/>
                <label for="celular" class="labels">Celular:</label>
                <input type="text" id="celular" maxlength="100" name="celular" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->celular; ?>" />                                
                <br/>                
                <input type="hidden" name="oper" id="oper" value="<?php echo $op; ?>"/>
                <label for="estado" class="labels">Activo:</label>                
                
                <?php                   
                    if($obj->estado==true || $obj->estado==false)
                            {
                             if($obj->estado==true){$rep=1;}
                                else {$rep=0;}
                            }
                     else {$rep = 1;}                    
                     activo('activo',$rep);
                ?>
                <div style="clear: both; padding: 10px; width: auto;text-align: center">
                    <a href="#" id="save" class="button">GRABAR</a>
                    <a href="index.php?controller=user" class="button">ATRAS</a>
                </div>
            </div>
        </div>
    </form>
</div>    
</div>