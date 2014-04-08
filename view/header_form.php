<?php
 function header_form($title)
 {
    $title = strtoupper($title);
 ?>
<div class="ui-widget-header ui-state-default" >    
     <div style="floar:left; display:inline-block; width:auto; font-size:11px; padding:3px 0 0 5px;">
        <?php echo $title; ?>
     </div>
     <div style="float: right; display: inline; width: 18px; height: 18px; margin: 3px 5px">
         <a href="index.php" style="border:0" title="Cerrar" class="box-boton boton-close"></a>
     </div>
     <div style="float: right; display: inline; width: 18px; height: 18px; margin: 3px 5px">
        <a href="<?php echo dameURL(); ?>" style="border:0" title="Refrescar Registros" class="box-boton boton-refresh"></a>
     </div>     
     <div style="float: right; display: inline; width: 18px; height: 18px; margin: 3px 5px">
        <a href="index.php?controller=<?php echo $_GET['controller'] ?>" style="border:0" title="Atras"></a>
     </div>     
    <div style="clear: both"></div>
</div>
<?php 
} 
?>