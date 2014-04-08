<script type="text/javascript">
  $(document).ready(function(){
    updateHW();
    $(window).resize(function(){
       updateHW();
    })
  })
  function updateHW()
  {
    var hc = $(".contFrm").height(),
          hm = $("#div-more-options").height(),
          constt = 69;      
      if((hc+constt)>hm)
      {
         $("#div-more-options").css("height",(hc+constt));
      }
      else 
      {
        $(".contFrm").css("height",(hm-constt))
      }   
  }
</script>
<div id="div-more-options" class="ui-widget-content">    
    <h3 class="ui-widget-header ui-state-default" id="title-more-options" >MAS OPCIONES</h3>
    <ul id="list-more-options">
        <?php          
         foreach($rows as $k => $r)
         {
             $class="";
          if($r['controlador']==$_GET['controller'])
          {
              $class=" active-more-options ";              
              ?>
            <li class="<?php echo $class; ?>">
                <a  href="javascript:"><?php echo $r['descripcion']; ?></a>
            </li>
            <?php 
          }
          else {
              ?>
            <li class="<?php echo $class; ?>">
                <a  href="<?php echo $r['url'] ?>?controller=<?php echo $r['controlador'] ?>&action=<?php if($r['accion']==""){echo "index";}else{ echo $r['accion']; }?>"><?php echo $r['descripcion']; ?></a>
            </li>
            <?php 
          }
        
          }
        ?>
    </ul>
</div>