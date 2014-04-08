<h6 class="ui-widget-header" style="margin-bottom: 5px;">ERROR EN LA CONEXION</h6>
<div style=" margin: 50px auto; width: 59%; padding: 10px ;color:#000 " class="ui-state-error ui-corner-all">
    <div style="float:left; width: 51px;">
        <img src="images/errorcopia.png" />
    </div>
    <div style="float:left; width: 500px; margin-left: 5px;">
        Mensaje: NO SE HA PODIDO ESTABLECER UNA CONEXION A LA BASE DE DATOS POR LOS SIGUIENTES MOTIVOS: <br/>
        <p><b>"<?php echo utf8_encode($str); ?>"</b></p><br>
        <a href="index.php?controller=BaseDatos&action=config" style="float:right; color:#000">Configurar Conexion</a>
    </div>
    <div style="clear: both"></div>
</div>