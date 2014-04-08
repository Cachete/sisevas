<table border="0" style=" margin: 0 auto;  background: transparent; " >
    <tbody>
        <tr style=" background: transparent;">      
            <td style="padding: 0; border: 0;  background: transparent;">
                <label style="margin-right: 10px; font-size: 9px;"><?php echo $trows; ?> Registros</label>
            </td>
            <?php if($_GET['p']>1) {?>
            <td style="padding: 0; border: 0;  background: transparent;">
                <a href="<?php echo $url;?>&q=<?php echo $query;?>&p=1" title="Primer Grupo"><img src="images/page-first.png" style="border:0; margin-top: 4px; margin-right: 4px" /></a>
            </td>
            <?php } ?>
            <?php                 
                $last_value = "";
                foreach ($rows as $key => $value) { ?>
                <?php if ($value['active']==0) { ?>
                <td style="padding: 0; border: 0">            
                        <?php
                            switch ($value['type']) {
                                case 1:
                                    ?>
                                    <!-- <a href="<?php echo $url;?>&q=<?php echo $query;?>&p=<?php echo $value['value'];?>" class="links"> -->
                                    <a javascript: class="links paginator">
                                    <?php                                    
                                    echo $value['value'];
                                    $last_value = $value['value'];
                                    ?>
                                    </a>
                                    <?php
                                    break;
                                case 2:
                                    echo '<a href="'.$url.'&q='.$query.'&p='.$value['value'].'" title="Anterior Grupo"><img src="images/page-prev.png" style="border:0; margin-top: 4px; margin-right: 4px" /></a>';
                                    break;
                                case 3:
                                    echo '<a href="'.$url.'&q='.$query.'&p='.$value['value'].'" title="Siguiente Grupo"><img src="images/page-next.png" style="border:0; margin-top: 4px; margin-left: 4px" /></a>';
                                    break;
                                default:
                                    break;
                            }
                        ?>
                    
                </td>
                <?php } else { ?>
                <td style="padding: 0; border:0">
                    <span class="links activo" ><?php echo $value['value']; ?></span>
                 </td>
                <?php }  ?>

            <?php } 
            if($_GET['p']!=round($trows/$nrows))
            {
            ?>
                 
            <td style="padding: 0; border: 0;">
                <a href="<?php echo $url;?>&q=<?php echo $query;?>&p=<?php echo round($trows/$nrows);?>" title="Ultimo Grupo"><img src="images/page-last.png" style="border:0; margin-top: 4px; margin-left: 4px" /></a>
            </td>
            <?php
            }
            ?>
        </tr>
    </tbody>
</table>
