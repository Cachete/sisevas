<?php include("../lib/helpers.php"); ?>
<script type="text/javascript" src="js/app/evt_form_config.js" ></script>
<script type="text/javascript" src="js/validateradiobutton.js"></script>
<?php 
    $readonly = "";
    if($obj->idusuario!="")
        {
            $readonly="readonly";
        }
?>
<div class="div_container">
<h6 class="ui-widget-header">Opciones de Configuracion</h6>
<div class="contain" style="margin-top: 10px; width: 550px;"> 
    <div class=" ui-widget-header " >   
     <div style="float: right; display: inline; width: 18px; height: 18px; margin: 3px 5px">
        <a href="index.php" style="border:0" title="Cerrar"><img alt="Cerrar" src="images/close.png"/></a>
     </div>
     <div style="float: right; display: inline; width: 18px; height: 18px; margin: 3px 5px">
        <a href="<?php echo dameURL(); ?>" style="border:0" title="Refrescar Registros"><img alt="Refrescar" src="images/reload.png"/></a>
     </div>     
     <div style="float: right; display: inline; width: 18px; height: 18px; margin: 3px 5px">
        <a href="index.php?controller=<?php echo $_GET['controller'] ?>" style="border:0" title="Atras"><img alt="Atras" src="images/directional_left.png"/></a>
     </div>     
    <div style="clear: both"></div>
    </div>
<form id="frm" action="index.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="controller" value="config" />
    <input type="hidden" name="action" value="save" />
    <div class="contFrm" style="background: #fff;">
        <div style="margin:0 auto; ">   
                <input type="hidden" id="idconfig" name="idconfig" class="text ui-widget-content ui-corner-all" style=" width: 100px; text-align: left;" value="<?php echo $obj->idconfig; ?>" <?php echo $readonly; ?> />
                <br/>
                <label for="descripcion" class="labels">Descripcion:</label>                                
                <input type="text" id="descripcion" maxlength="100" name="descripcion" class="text ui-widget-content ui-corner-all" style=" width: 300px; text-align: left;" value="<?php echo $obj->descripcion; ?>" />
                <br/>
                <label for="logo" class="labels">Logo:</label>
                <input type="file" name="logo" id="logo" value="" onchange="validarIMG(this)" />
                <input type="hidden" name="f" id="f" value="<?php echo $obj->logo; ?>" />
                <br/>
                <br/>
                <?php if($obj->logo!="") { ?>                 
                <div style="width: 60%; margin: 0 auto; background: #fafafa;">
                    <img src="config/logo/<?php echo $obj->logo; ?>" style=""  width="270"  /> 
                    <br/>
                    <strong >Foto Acutal</strong>
                </div>
                <br/>
                <?php } ?>
                <div  style="clear: both; padding: 10px; width: auto;text-align: center">
                    <a href="#" id="save" class="button">GRABAR</a>
                    <a href="index.php?controller=User" class="button">ATRAS</a>
                </div>
        </div>
    </div>
</form>
</div>    
</div>