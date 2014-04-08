<script type="text/javascript">
    $(function(){
        $("#frm").submit(function(){            
            str = $(this).serialize();
            $("#msg").css("display","none");
            $.get('index.php','controller=BaseDatos&action=saveConfig&'+str,function(data){
                if(data.rep=="1")
                    {
                        $("#msg").empty().append("La configuracion a sido guardada correctamente");
                        $("#msg").css("display","block");
                    }
            },'json');
            return false;
        });
    });
</script>
<style type="text/css">
    .text { }
    .param {display: block;clear: both}
</style>
<div>
    <a href="index.php">VOLVER AL SISTEMA</a>
</div>
<div class="ui-state-active" style="margin: 20px auto; padding: 10px; width: 200px">
    <div id="msg" style="width:497px; margin-top: 10px; margin-bottom: 10px;display:none; text-align: center; border:1px solid #ccc;"></div>
    <form name="frm" id="frm" action="" >
    <span class="text">Host :</span><span class="param"><input type="text" name="namehost" id="namehost" value="" /></span><br>
    <span class="text">Driver :</span>
    <span class="param">
        <select name="driver" id="driver">
            <option value="PGSQL">PostgreSQL</option>
            <option value="MYSQL">MySQL</option>
            <option value="MSSQL">Microsoft SQL Server</option>
<!--        <option value="DBLIB">Sybase / FreeTDS</option>
            <option value="FIREBIRD">Firebird/Interbase 6</option>
            <option value="IBM">IBM DB2</option>
            <option value="INFORMIX">IBM Informix Dynamic Server</option>
            <option value="OCI">Oracle Call Interface</option>
            <option value="ODBC">ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</option>
            <option value="SQLITE">SQLite 3 and SQLite 2</option>
            <option value="4D">4D</option>-->
        </select>
    </span><br>
    <span class="text">Nombre de Base de Datos :</span><span class="param"><input type="text" name="dbname" id="dbname" /></span><br>
    <span class="text">Puerto :</span><span class="param"><input type="text" name="port" id="port" /></span><br>
    <span class="text">Usuario de Base de Datos:</span><span class="param"><input type="text" name="username" id="username" /></span><br>
    <span class="text">Password :</span><span class="param"><input type="password" name="password" id="password" /></span><br>
    <span><input type="submit" name="Grabar" id="Grabar" value="Guardar Configuracion" /></span>
    </form>
</div>     