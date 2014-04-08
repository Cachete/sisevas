<script type="text/javascript" src="../web/js/grilla.js"></script>
<?php 
    $action = "index";
    if($edit==false && $view==false) {$action = "search";}
?>
<div id="mensaje"></div>
<div class="wrapper-search" style="margin: 0 auto; width: 83%; margin-bottom: 10px;">
    <form action="" method="GET">
        <input type="hidden" name="controller" value="<?php echo $name; ?>" />
        <input type="hidden" name="action" value="<?php echo $_GET['action']; ?>" />
        <input type="hidden" name="p" value="1" />
        <?php echo $combo_search; ?>
        <input type="text" name="q" id="q" class="text ui-widget-content ui-corner-all " value="<?php echo $_GET['q']; ?>" style="width: 50%; margin-left: 3px; margin-top: 5px; margin-bottom: 3px; " /> <input type="submit" value="Buscar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"   />
    </form>
</div>
<div class="contain" style="border: 1px solid #dadada;">
<?php echo $option_op; ?>
<div id="wrap-extra"></div>    
<div class="wrapper-grid ui-corner-all">    
<table id="tgrid" class="ui-widget ui-widget-content" style="width: 100%;">
    <thead class="ui-widget ui-widget-content" >
        <tr class="ui-widget-header tr-head" >
            <?php foreach($cols as $k => $c) { 
                  $width = isset($c['ancho'])?$c['ancho']:'auto';
                  $title = isset($c['titulo'])?$c['titulo']:$k;                  
                  if(isset($c['colspan'])){$cs = $c['colspan'];}
                    else {$cs = 1;}
            ?>
            <th colspan="<?php echo $cs; ?>" title="<?php echo $title; ?>" style="text-align: center; width: <?php echo $width*$cs; ?>%" ><?php echo $k; ?></th>                 
            <?php } 
            if($select) {echo '<th >&nbsp;</th>';}
            ?>  
                  
            </tr>
        </thead>
        <tbody >
        <?php 
        $n = 0;
         foreach ($rows as $value) 
         { 
          $n ++;
           echo '<tr>'; 
           $myselect = "<td align='center' width='5%'><a title='Seleccionar' href='javascript:get(";
           $cont = count($cols);
           $c_ = 0;
              foreach($cols as $key => $c)
              { 
                  if(isset($c['colspan'])){$cs = $c['colspan'];}
                    else {$cs = 1;}
                  for($ii=1;$ii<=$cs;$ii++)
                  {
                    $align = isset($c['align'])?$c['align']:'left';                  
                    echo "<td class='td-".($c_+1)."' style='text-align:".$align.";'>".$value[$c_]."</td>";
                    if($cont==($c_+1)) {$myselect .= '"'.trim($value[$c_]).'"';}
                      else {$myselect .= '"'.trim($value[$c_]).'",';}
                    $c_ ++;                  
                  }
                  
              }           
              $myselect .= ")'><img src='images/front.png' /></a></td>";
               if($select) {
                  echo $myselect;
              }
              echo '</tr>';
          }           
          for($i=0;$i<($nr-$n);$i++)
          {
            echo "<tr>";                    
            foreach($cols as $c) { 
              if(isset($c['colspan'])){$cs = $c['colspan'];}
                    else {$cs = 1;}
                  for($ii=1;$ii<=$cs;$ii++)
                  {
              ?>
              <th >&nbsp;</th>
            <?php }}  
            if($select) {echo '<th >&nbsp;</th>';}
            echo "</tr>";
          }          
       ?>        
    </tbody>    
</table> 
</div> <?php echo $option_op; ?></div>
<!--    
    Autor: Andrés García Macedo (ADZ)
-->