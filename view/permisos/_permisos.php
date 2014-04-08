<script type="text/javascript">
    $(document).ready(function() {
        $("#idperfil").change(function(){
            vv = $(this).val();
            if(vv!=""){
            $("#result").hide("slow");
            $("#modulos").hide("slow");
            $("#modulos").load("index.php","controller=Permisos&action=Modulos&idperfil="+vv,function(){
                $("#modulos").show("slow");
            });
            }
            else {
                $("#result,#modulos").hide("slow");                
            }
        });
        $("#save").click(function(){
            v = $("#idperfil").val();
            if(v!=""){
                str = $("#frmpermisos").serialize();
                $("#result").hide("slow");
                $.get("index.php","controller=Permisos&action=Save&"+str, function(data)
                    {
                        $("#result").empty().append(data.msg);
                        $("#result").show("slow");
                    }
                ,'json');
            }
                else {alert("Porfavor Seleccione un perfil antes de guardar los cambios.");
                      $("#idperfil").focus();}
        });
    });   
</script>
<div class="div_container">
<h6 class="ui-widget-header">Administracion de Accesos</h6>
<div style=" width: 100%;">
    <div style="padding:20px;">
    <span>Seleccione un Perfil :</span>
    <span><?php echo $perfiles; ?></span>    
    <a href="javascript:" id="save"  class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-search"></span>Guardar Cambios</a>
    <span id="result" style="display:none;color:green; font-weight: bold;">Sus Cambios Fueron Registrados</span>
    </div>
</div>
<div style="clear:both"></div>
<div id="modulos" style="clear: both; margin-left: 115px;"></div>
</div>