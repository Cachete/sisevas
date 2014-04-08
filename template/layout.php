<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>SISTEMA DE EVALUACION DE DESEMPEÑO</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="expires" content="0" />
    <link type="text/css" href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />
    <link type="text/css" href="css/layout.css" rel="stylesheet" />
    <link href="css/cssmenu.css" rel="stylesheet" type="text/css" />
    <link href="css/style_forms.css" rel="stylesheet" type="text/css" />
    <link href="css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">

    <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>    
    <script type="text/javascript" src="js/menus.js"></script>
    <script type="text/javascript" src="js/session.js"></script>
    <script type="text/javascript" src="js/required.js"></script>
    <script type="text/javascript" src="js/validateradiobutton.js"></script>
    <script type="text/javascript" src="js/utiles.js"></script>
    <script type="text/javascript" src="js/js-layout.js"></script>
    <script type="text/javascript" src="js/pag.js"></script>
    <script type="text/javascript" src="js/jquery.jqGrid.min.js"></script> 
    <!-- <script type="text/javascript" src="js/jquery.jqGrid.src.js"></script> -->
    <script type="text/javascript" src="js/grid.locale-es.js"></script>
    <!-- prefix free to deal with vendor prefixes -->
    <script src="http://thecodeplayer.com/uploads/js/prefixfree-1.0.7.js" type="text/javascript" type="text/javascript"></script>
    
    <script src="js/jquery.uploadify.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="css/uploadify.css"></link>

    <script type="text/javascript">
        /*jQuery time*/
        $(document).ready(function(){
            $("#accordian h3").click(function(){
                //slide up all the link lists
                $("#accordian ul ul").slideUp();
                //slide down the link list below the h3 clicked - only if its closed
                if(!$(this).next().is(":visible"))
                {
                    $(this).next().slideDown();
                }
            })
        })
    </script>
</head>
<body>
    <?php 
        //print_r($_SESSION); 
    ?>
    <header id="site_head">
        <div class="header_cont">
            <h1><a href="#">Romero</a></h1> 
            <nav class="head_nav"></nav>
        </div>       
       
    </header>
    <div id="body">
        <div id="banner"></div>
        
        <div id="left">
            <h6 class="ui-widget-header ui-state-hover">BIENVENIDO  </h6>
            <br />
            <p>USER</p>
            <div id="barra-session">
                <span class="item-top"><?php echo strtoupper($_SESSION['name']); ?></span>
                
            </div>
            <p>SEDE</p>
            <div id="barra-session">
                <span class="item-top"><?php echo strtoupper($_SESSION['sucursal']); ?></span>
                
            </div>
            <p>CONSULTORIO</p>
            <div id="barra-session">                      
                <span class="item-top"><?php echo strtoupper($_SESSION['area']); ?></span>
            </div>
            <p>MENSAJES</p>
            <div id="barra-session">                           
                <a href="#" class="box-item-notification notification-encomienda" title="Encomiendas Pendientes">
                    <span class="indicator-notification"></span>
                </a>
                <!-- <div id="notifications" class="ui-corner-all" style="">
                    <a href="#" id="icon-notifications" class="box-item-notification notification-encomienda">
                        <span id="count-notifications" class="ui-corner-all" style="display: none"></span>
                    </a>                
                </div> -->             
            </div>
            <div id="barra-session">
                <br />             
                <a href="index.php?controller=user&action=logout" class="logout">CERRAR SESION</a>                
            </div>

        </div>        
        <div id="content">
            <?php echo $content; ?>
        </div>
        <div  class="spacer"></div>
        <div id="foot" class="ui-corner-bottom">
            SISTEMA DE EVALUACION DE DESEMPEÑO
            <br/>2013
        </div>
        <div  class="spacer"></div>        
    </div>
    <div id="dialog-session" title="Su sesión ha expirado." style="display:none">
        <div class="ui-state-error" style="padding: 0 .7em; border: 0">
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <strong>Por favor vuelva a iniciar sesión.</strong></p>
        </div>
    </div>
    <div id="dialog"></div>
</body>
</html>