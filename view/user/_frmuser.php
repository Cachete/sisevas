<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<form name="frmuser" id="frmuser" action="javascript:" method="POST">
    <br/>
    <label class="labels" style="width:130px;">Nombre :</label>
    <span style="font-size:12px; font-weight: bold; text-transform: uppercase"><?php echo $obj->nombres; ?></span>
    <input type="hidden" name="iduser" id="iduser" value="<?php echo $obj->idusuario; ?>" />
    <br/>
    <label class="labels" style="width:130px;">Password :</label>
    <input type="password" name="passw" id="passw" value="" class="ui-widget-content ui-corner-all text" title="Ingrese su Password Actual" />    
    <a id="valid_passw" href="#" style="border:0; margin-left: 5px;" title="Validar Password"><img src="images/lock.png" /></a>
    <br/>
    <div id="box-change-passw" style="background: #fff; border: 1px solid #dadada; padding: 5px 0; display:none">
        
    </div>
</form>