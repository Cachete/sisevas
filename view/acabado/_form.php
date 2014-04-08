<?php  include("../lib/helpers.php");     ?>
<style>
    .link-oper { margin-left: 10px; color: green !important; }
    span.title-head { text-transform: uppercase; display: block; padding: 5px}
</style>
<div style="padding:10px 20px; width:950px">
<form id="frm-acabado" >
    <fieldset class="ui-corner-all" style="padding: 2px 10px 7px">
    <legend>Datos - <b><?php if($obj->fecha!="") echo fdate($obj->fecha,'ES'); else echo date('d/m/Y'); ?></b></legend>
        <input type="hidden" id="controller" name="controller" value="acabado" />
        <input type="hidden" id="action" name="action" value="save" />             
        <input type="hidden" id="idacabado" name="idacabado" class="text ui-widget-content ui-corner-all" style=" width: 50px; text-align: left;" value="<?php echo $obj->idacabado; ?>" readonly />                                
        <label for="idpersonal" class="labels">Personal Enc.:</label>
        <input type="hidden" name="idpersonal" id="idpersonal" value="<?php echo $obj->idpersonal; ?>" />        
        <input type="text" name="dni" id="dni" class="ui-widget-content ui-corner-all text" style="width:80px" value="<?php echo $obj->dni; ?>" maxlength="11" onkeypress="return permite(event,'num')" />
        <input type="text" name="personal" id="personal" class="ui-widget-content ui-corner-all text" style="width:250px" value="<?php echo $obj->personal; ?>" />
        <label for="fechai" class="labels">Fecha, desde:</label>
        <input type="text" name="fechai" id="fechai" class="ui-widget-content ui-corner-all text" value="<?php echo (isset($obj->fechaini)?fdate($obj->fechaini,'ES'):date('d/m/Y')); ?>" style="width:70px; text-align:center" />        
        Hasta <input type="text" name="fechaf" id="fechaf" class="ui-widget-content ui-corner-all text" value="<?php echo (isset($obj->fechafin)?fdate($obj->fechafin,'ES'):date('d/m/Y')); ?>" style="width:70px; text-align:center" /> 
        <br/>
        <label class="labels">Obser./Refe.: </label>
        <input type="text" name="observacion" id="observacion" value="<?php echo $obj->observacion; ?>" class="ui-widget-content ui-corner-all text" style="width:70%" />
    </fieldset> 
    <?php 
        $c = count($rowsd);
        if($c==0)
        {
    ?>   
    <fieldset id="box-melamina" class="ui-corner-all ui-widget-content" style="padding: 2px 10px 7px;">  
        <legend>Produccion</legend>      
        <div id="box-1">
            <table id="table-me" class="table-form" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <th width="300" align="left">Producto</th>
                    <th width="250" align="left">Responsable</th>
                    <th width="80" align="left">Total Prod.</th>
                    <th width="80" align="left">Stock</th>
                    <th width="80" align="left">Cant.</th>
                    <th rowspan="2"><a href="javascript:popup('index.php?controller=produccion&action=lista',870,350)" id="btn-search-prod" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-search"></span>Buscar</a></th>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="idproduccion_detalle" id="idproduccion_detalle" value="<?php echo $obj->idproduccion_detalle; ?>" readonly="readonly"/>
                        <input type="text" name="dproducto" id="dproducto" value="<?php echo $obj->producto; ?>" readonly="readonly" style="width:100%" class="ui-widget-content ui-corner-all text"/>
                    </td>
                    <td><input type="text" name="dresponsable" id="dresponsable" value="<?php echo $obj->responsable; ?>" readonly="readonly" style="width:100%" class="ui-widget-content ui-corner-all text"/></td>
                    <td><input type="text" name="tprod" id="tprod" value="<?php echo $obj->tprod; ?>" readonly="readonly" style="width:100%" class="ui-widget-content ui-corner-all text" /></td>
                    <td><input type="text" name="stock" id="stock" value="<?php echo $obj->stock; ?>" readonly="readonly" style="width:100%" class="ui-widget-content ui-corner-all text" /></td>
                    <td><input type="text" name="cantidad" id="cantidad" value="<?php if($obj->cantidad!="") echo $obj->cantidad; else echo "0"; ?>" style="width:100%" class="ui-widget-content ui-corner-all text" onkeypress="return permite(event,'num')" /></td>
                </tr>
            </table>  
            <div class=" ui-corner-all" style="padding:0px">                
                <div style="padding:3px 0 0 0">
                    <div class="ui-widget-content" style="padding:3px">
                        <p style="text-align:center">AGREGAR LOS MATERIALES A USAR</p>
                        <br/>
                        <?php echo $idmaterial; ?>
                        <select id="idunidad_medida" name="idunidad_medida" class="ui-widget-content ui-corner-all text" style="width:170px">
                            <option>Seleccion unidad de Medida...</option>
                        </select>
                        <input type="hidden" name="stock_ma" id="stock_ma" value="0" />
                        <input type="text" name="cant_ma" id="cant_ma" value="0.00" class="ui-widget-content ui-corner-all text" style="text-align:center; width:50px" maxlength="7" onkeypress="enter(event);return permite(event,'num');" />
                        <a href="javascript:" id="btn-add-ma" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a>                         
                    </div>
                    <div class="contain" style="">
                        <table id="table-detalle-materia" class="ui-widget ui-widget-content" style="margin: 0 auto; width:100% " border="0" >
                            <thead>
                                <tr>                                
                                    <td width="80" align="center">Item</td>
                                    <td>Materiales</td>                                    
                                    <td width="200" align="center">Unidade de Medida</td>
                                    <td width="100px" align="center">Cantidad</td>
                                    <td width="30px">&nbsp;</td>
                                </tr>
                            </thead> 
                            <tbody>
                            </tbody>
                        </table>
                    </div>                     
                </div>
            </div>
        </div>        
    </fieldset>
    <!-- <div style="text-align:right; ">
        <a href="javascript:" id="btn-clear-detalle-prod" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-minus"></span>Limpiar</a> 
        <a href="javascript:" id="btn-add-detalle-prod" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset ui-state-active"><span class="ui-icon ui-icon-plusthick"></span>Agregar a Detalle de Acabados</a> 
    </div> -->
    <?php } ?>
    <!-- <fieldset class="ui-widget-content ui-corner-all"><legend>Detalle de Acabados</legend>
    <div id="div-detalle" class="ui-corner-all" style="background:#FAFAFA; padding: 0px 15px 10px">
        <?php             
            if($c>0)
            {
                
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
            }            
        ?>
    </div>
    </fieldset> -->
</form>
</div>
<div id="dialogConf"></div>
<div id="box-add-mp"></div>