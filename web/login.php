<?php
    session_start();
    session_destroy();
    global $n;
    if(!isset($_GET['key'])) { $n=rand(1000,9999); } else { $n = base64_decode($_GET['key']); }    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>SISTEMA DE EVALUACION DE DESEMPEÑO</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="expires" content="0" />
    <link type="text/css" href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />
    <link type="text/css" href="css/login.css" rel="stylesheet" />    
    <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
    
</head>
    <script type="text/javascript">
        $(document).ready(function(){ 
            $("#usuario").focus();
            $("#frmlogin").submit(function(){
                var cv = 1;                
                if(cv==cv)
                {
                   var user = $("#usuario").val();
                   if(user!="")
                   {
                        return true;
                   }
                   else {
                        alert("Ingrese el usuario.");
                        return false;
                        }
                 }
                 else {
                     alert("Codigo de verificacion incorrecto.");
                     return false;
                 }                 
            });
        });
    </script>
<body>    
    <?php 
        if(isset($_GET['error']))
        {
            echo '<div style="color:red; text-align:center; margin-top:20px">¡Vaya!, al parecer ha olvidado sus datos, intentelo de nuevo</div>';
        }
    ?>
    <div id="contenedor"> 
        <div id="content">
            <div id="cont-left">
                <div style="margin:100px 0;">
                    <span id="logo-acces"><img src="images/logo.png"/></span>
                    <span id="titlenom">SISTEMA DE EVALUACION DE DESEMPEÑO</span>
                </div>
            </div>
            <div id="cont-right">
                <div id="formulario">
                    <form id="frmlogin" method="post"  action="process.php" >
                    <div id="logo" class="header_cont">                        
                        <h1>
                            <img src="images/seguridad.png" width="30px" height="30px" />Acceso al Sistema
                        </h1> 
                    </div>                    
                    <div class="box-item-form"><label class="labels">Usuario:</label></div>
                    <div class="box-item-form"><input id="usuario" name="usuario" class="ui-widget-content ui-corner-all text" style=" width: 80%; text-align: left; " value=""  /></div>
                    <div class="box-item-form"><label class="labels">Password:</label></div>
                    <div class="box-item-form"><input type="password" id="password" name="password" class="ui-widget-content ui-corner-all text" style=" width: 80%; text-align: left;" value=""/></div>
                    <div class="box-item-form"><input type="submit" id="ingresar" value="Iniciar Sessión" class="ui-button"  /></div>
                    
                    </form>
                </div>
            </div>
        </div>       
        <div id="foot">
            <span>2013 - NTCsystem</span>
            <span style="float:right">SISTEMA DE EVALUACION DE DESEMPEÑO</span>
        </div>
    </div>               
</body>
</html>
