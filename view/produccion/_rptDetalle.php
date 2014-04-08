<fieldset class="ui-widget-content ui-corner-all"><legend>Detalle de Produccion</legend>
    <div id="div-detalle" class="ui-corner-all" style="background:#FAFAFA; padding: 0px 15px 10px">
        <?php             
            
                //print_r($rowsd);
                foreach ($rowsd as $i => $v) 
                {
                    ?>
                    <div class="box-item">
                        <span class="title-head"><strong><?php echo number_format($v['cantidad'],2); ?> <?php echo $v['descripcion'] ?> </strong></span>
                        <div id="materia-">
                            <ul class="ul-items">
                                <?php 
                                    foreach($v['det'] as $d)
                                    {
                                        ?>
                                        <li>(Almacen: <?php echo $d['almacen'] ?>) <?php echo $d['descripcion'] ?>, <?php echo number_format($d['cantidad'],2); if($d['tipo']==1) echo "pies"; else echo "Und."; ?> </li>
                                        <?php
                                    }
                                ?>                                
                            </ul>
                        </div>
                    </div>
                    <?php
                }
                       
        ?>
    </div>
    </fieldset>