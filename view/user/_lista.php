<script type="text/javascript">
    $(function(){
        $( "#q" ).focus();
    });    
    function get(p1,p2,p3,p4,p5,p6)
    {
        opener.document.getElementById('idusuario').value=p1;
        opener.document.getElementById('nombres').value=p2;
        window.close();
    }
</script>
<div class="div_container">
<h6 class="ui-widget-header">Empleados Registrados</h6>
<?php echo $grilla; ?>
</div>