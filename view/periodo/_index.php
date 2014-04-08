<script type="text/javascript">    
    $(document).ready(function(){
        $('.activar').click(function(){
            var id = $(this).attr("id");
            $.get('index.php','controller=periodo&action=change&id='+id,function(data){
                window.location = "index.php?controller=periodo&action=index";
            })
        });
    });
</script>
<div class="div_container">
<h6 class="ui-widget-header">PERIODOS</h6>
<?php echo $grilla; ?>
</div>