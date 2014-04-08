<?php  
include("../lib/helpers.php"); 
include("../view/header_form.php"); 
?>
<div style="padding:10px 20px; width:950px">
<form id="frm_verificacion" >
       
    <fieldset id="box-solicitud" class="ui-corner-all" style="padding: 2px 10px 7px;">  
        <!-- <legend>Produccion</legend> -->      
        <div id="box-1">
            <input type="hidden" name="controller" value="Verificacion" />
            <input type="hidden" name="action" value="save" />             
            <input type="hidden" id="idsolicitud" name="idsolicitud" class="text ui-widget-content ui-corner-all" style=" width: 50px; text-align: left;" value="<?php echo $obj->idsolicitud; ?>" readonly />                
            
            <label for="fechas" class="labeles" style="width:120px;">Fecha de solicitud:</label>
            <input type="text" name="fechasolicitud" id="fechasolicitud" class="ui-widget-content ui-corner-all text" value="<?php echo (isset($obj->fechasolicitud)?$obj->fechasolicitud:  date('d/m/Y')); ?>" style="width:70px; text-align:center" />        
            
            <label for="fechasve" class="labeles" style="width:135px;">Fecha de vencimiento:</label>
            <input type="text" name="fechavenc" id="fechavenc" class="ui-widget-content ui-corner-all text" value="<?php echo (isset($obj->fechavenc1)?$obj->fechavenc1:  date('d/m/Y')); ?>" style="width:70px; text-align:center" />        
            
            <label for="nrorecibo" class="labeles">N° Recibo:</label>
            <input type="text" name="nrorecibo" id="nrorecibo" class="ui-widget-content ui-corner-all text" value="<?php echo $obj->nrorecibo; ?>" style="width:70px; text-align:center" />        
            <br/>
            
            <label for="fechas" class="labeles" style="width:120px;">Buscar proforma:</label>
            <input type="text" name="dni" id="dnicliprof" value="" class="ui-widget-content ui-corner-all text" style="width:80px;" />
            <input type="text" name="clienteprof" id="clienteprof" value="" class="ui-widget-content ui-corner-all text" style="width:250px;" />
            <input type="hidden" name="idclienteprof" id="idclienteprof" value="" />
            <input type="hidden" name="idproforma" id="idproforma" value="<?php echo $obj->idproforma; ?>" />

            <div class="ui-widget-content ui-corner-all" style="padding:10px">
                <h4 id="title-produccion" style="text-align:center">HOJA DE SOLICITUD</h4>
                <br/>
                <div id="tabs">
                    <ul style="background:#DADADA !important; border:0 !important">
                        <li><a href="#tabs-1">Datos del Cliente</a></li>
                        <li><a href="#tabs-2">Datos del Conyugue</a></li>
                        <li><a href="#tabs-3">Ingresos Familiares</a></li>
                        <li><a href="#tabs-4">Referencias Personales</a></li>
                        <li><a href="#tabs-5">Descripcion del Producto</a></li>
                        <?php
                            $idsol=$obj->idsolicitud;
                            if($idsol!='')
                            {
                        ?>
                            <li><a href="#tabs-6">Observacion</a></li>
                        <?php
                            }
                        ?>
                    </ul>
                    <div id="tabs-1">
                        <p id="">Datos Generales del <b>Cliente :</b></p>
                        <br />
                        <label for="dni" class="labeles">DNI:</label>
                        <input type="text" name="dni" id="dni" class="ui-widget-content ui-corner-all text" style="width:200px;" value="<?php echo $obj->cli_dni; ?>" />
                        <input id="idcliente" name="idcliente" value="<?php echo $obj->idcliente; ?>" type="hidden" />

                        <label for="cleinte" class="labeles">Nombres y Ap:</label>                        
                        <input id="nomcliente" name="nomcliente" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nomcliente; ?>"  />
                        
                        <br/>

                        <label for="sexo" class="labeles">Sexo:</label>        
                        <select id="sexo" name="sexo" class="ui-widget-content ui-corner-all">
                            <?php $var="";
                                if($obj->sexo=='M')
                                {$var="selected";}               
                            ?>
                            <option <?php echo $var; ?> value="M">Masculino</option>

                            <?php $var="";
                                if($obj->sexo=='F')
                                {$var="selected";}               
                            ?>
                            <option <?php echo $var; ?> value="F">Femenino</option>
                        </select>

                        <label for="fechanaci" class="labeles">Fecha de Nac:</label>
                        <input type="text" name="fechanac" id="fechanac" class="ui-widget-content ui-corner-all text" value="<?php echo (isset($obj->fechanac)?$obj->fechanac:  date('d/m/Y')); ?>" style="text-align:center" />
                        <br/>

                        <label for="estcivil" class="labeles">Estado civil:</label>        
                        <?php echo $EstadoCivil; ?>

                        <label for="cargafam" class="labeles">Carga Familiar:</label>                        
                        <input type="text" id="cargafam" name="cargafam" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->cargafam; ?>" />
                        <br/>

                        <label for="nivel" class="labeles">Nivel Educacion:</label>        
                        <?php echo $NivelEducacion; ?>

                        <label for="telefono" class="labeles">Telefono:</label>                        
                        <input type="text" id="telefono" name="telefono" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->telefono; ?>" />
                        <br/>

                        <label for="tivovivienda" class="labeles">Tipo Vivienda:</label>
                        <?php echo $tivovivienda; ?>
                        
                        <label for="direccion" class="labeles">Dirección:</label>                        
                        <input type="text" id="direccion" name="direccion" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->direccion; ?>" />
                        <br/>
                        
                        <label for="direccion" class="labeles">Ref. Ubicación:</label>                        
                        <input type="text" id="referencia_ubic" name="referencia_ubic" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->referencia_ubic; ?>" />
                        
                        <label for="ocupacion" class="labeles">Actividad Econ.:</label>                        
                        <input type="text" id="ocupacion" name="ocupacion" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->ocupacion; ?>" />
                        <br/>
                        
                        <label for="trabajo" class="labeles">Empresa que trabaja:</label>                        
                        <input type="text" id="trabajo" name="trabajo" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->trabajo; ?>" />
                        
                        <label for="cargo" class="labeles">Cargo Actual:</label>                        
                        <input type="text" id="cargo" name="cargo" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->cargo; ?>" />
                        <br/>
                        
                        <label for="teltrab" class="labeles">Telefono del trabaja:</label>                        
                        <input type="text" id="teltrab" name="teltrab" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->teltrab; ?>" />
                        
                        <label for="dirtrabajo" class="labeles">Direccion del trabajo:</label>                        
                        <input type="text" id="dirtrabajo" name="dirtrabajo" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->dirtrabajo; ?>" />
                        <br/>
                    </div>
                    <div id="tabs-2">
                        <p>Datos Generales del <b>Conyugue :</b></p>
                        <br />
                        <label for="dni" class="labeles">DNI:</label>
                        <input type="text" name="dnicon" id="dnicon" class="ui-widget-content ui-corner-all text" style="width:200px;" value="<?php echo $obj->con_dni; ?>" />
                        <input id="idconyugue" name="idconyugue" value="" type="hidden" />

                        <label for="cleinte" class="labeles">Nombres y Apellidos:</label>                        
                        <input id="nomconyugue" name="nomconyugue" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nomconyugue; ?>"  />
                        <br/>
                        
                        <label for="trabajocon" class="labeles">Empresa que trabaja:</label>                        
                        <input type="text" id="con_trabajo" name="con_trabajo" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->con_trabajo; ?>" />
                        
                        <label for="cargos" class="labeles">Cargo Actual:</label>                        
                        <input type="text" id="con_cargo" name="con_cargo" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->con_cargo; ?>" />
                        <br/>
                        
                        <label for="conteltrab" class="labeles">Telefono del trabaja:</label>                        
                        <input type="text" id="con_teltrab" name="con_teltrab" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->con_teltrab; ?>" />
                        
                        <label for="condirtrabajo" class="labeles">Direccion del trabajo:</label>                        
                        <input type="text" id="con_dirtrabajo" name="con_dirtrabajo" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->con_dirtrabajo; ?>" />
                        <br/>
                    </div>
                    <div id="tabs-3">
                        <table id="IngresosPare">
                            <tr>
                                <td>
                                     <p>Registre los ingresos de la pareja:</p>
                                        <br/>
                                        <label for="dni" class="labeless">Ingreso del cliente:</label>
                                        <input type="text" name="ingresocli" id="ingresocli" class="ui-widget-content ui-corner-all text" value="<?php echo $obj->ingresocli; ?>" style="width:80px;" />
                                        &nbsp;&nbsp;&nbsp;
                                        <label for="dni" class="labeless">Ingreso del conyugue:</label>
                                        <input type="text" name="ingresocon" id="ingresocon" class="ui-widget-content ui-corner-all text" value="<?php echo $obj->ingresocon; ?>" style="width:80px;" />
                                        &nbsp;&nbsp;&nbsp;
                                        <?php 
                                            $cli= $obj->ingresocli;
                                            $con= $obj->ingresocon;
                                            $totaling= $cli + $con;
                                        ?>
                                        <label for="dni" class="labeless">Total de ingresos:</label>
                                        <input type="text" name="totaling" id="totaling" class="ui-widget-content ui-corner-all text" value="<?php echo $totaling; ?>" style="width:80px;" />

                                </td>
                            </tr>
                        </table>
                       
                    </div>
                    <div id="tabs-4">
                        
                            <p>Ingrese alguna referencia:</p>
                            <br/>
                            <label for="nomgarant" class="labeles">Nombres y Apellidos:</label>                        
                            <input id="nomgarant" name="nomgarant" onkeypress="return permite(event,'car');" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->nombreref; ?>"  />
                                                    
                            <label for="relacion" class="labeles">Relación:</label>                        
                            <input type="text" id="relacion" name="relacion" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->relacionref; ?>" />
                            <br/>
                            
                            <label for="telefono" class="labeles">Telefono:</label>                        
                            <input type="text" id="gar_telefono" name="gar_telefono" class="text ui-widget-content ui-corner-all" style=" width: 200px; text-align: left;" value="<?php echo $obj->telefonoref; ?>" />
                            
                    </div>
                    <div id="tabs-5">
                        
                        <fieldset id="box-melamina" class="ui-corner-all" style="padding: 2px 10px 7px;">  
                        <legend>Detalle de la proforma</legend>
                        <div id="box-1">
                            <table id="table_solicitud" class="table-form" border="0" cellpadding="0" cellspacing="0" width="650px" >
                                <tr>
                                    <td width="100px"><label for="tipopago" class="labels">Tipo pago:</label></td>
                                    <td><?php echo $tipopago; ?></td>
                                    <td>                                        
                                        <label for="financiamiento" class="labels">Financiamiento:</label>
                                        <?php echo $Financiamiento; ?>
                                        <input type="checkbox" title="Considerar Adicional" checked="checked" id="ChkAdicional">                                        
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><label for="productos" class="labels">Buscar Producto:</label></td>
                                    <td colspan="2">
                                        <input type="text" name="producto" id="producto" value="" class="ui-widget-content ui-corner-all text" style="width:240px;" />
                                        <input type="hidden" name="idsubproductos_semi" id="idsubproductos_semi" value="" />
                                        <label for="igv" class="labels" style="width:80px;">Afecto IGV:</label>
                                        <?php $ck = ""; if($obj->afecto==1) $ck = "checked"; ?>
                                        <input type="checkbox" name="aigv" id="aigv" value="1" <?php echo $ck; ?> />
                                        <input type="hidden" name="igv_val" id="igv_val" value="<?php if($obj->igv!="") echo $obj->igv; else echo "18"; ?>" />
                                    </td>
                                    <td rowspan="2" align="center">
                                        <a href="javascript:" id="addDetail" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-plusthick"></span>Agregar</a> 
                                    </td>                    
                                </tr>
                                <tr>
                                    <td><label for="preciocashh" class="labels">Precio Cash:</label></td>
                                    <td>
                                        <input type="text" name="precio" id="precio" value="0.00" class="ui-widget-content ui-corner-all text" style="width:80px;" />
                                    </td>                    
                                    <td>
                                        <label for="Cantidad" class="labels">Cantidad:</label> 
                                        <input type="text" name="cantidad" id="cantidad" onkeypress="return permite(event,'num')" value="" class="ui-widget-content ui-corner-all text" style="width:80px;" /> 
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="Iniciales" class="labels">Inicial:</label></td>
                                    <td><input type="text" name="inicial" id="inicial" value="0.00" class="ui-widget-content ui-corner-all text" style="width:80px;" /></td>                    
                                    <td>
                                        <label for="nromes" class="labels">N° Meses:</label>
                                        <input id="NroMeses" class="ui-widget-content ui-corner-all text" type="text" style="width:40px;text-align:right" size="2" onkeypress="Calcular3(event)">
                                        <img id="calcularfi" src="../web/images/calculadora.png" width="18" height="18" />
                                        Mensual:
                                        <input id="Mensual" class="ui-widget-content ui-corner-all text" type="text" style="width:60px;text-align:right" size="8" onkeypress="">
                                    </td>
                                    <td>&nbsp;</td> 
                                </tr>
                            </table>                     
                        </div>

                    </fieldset>
                    <div id="div-detalle">
                        <div>
                            <table id="table-detalle" class="ui-widget ui-widget-content" style="margin: 0 auto; width:840px" border="0" >
                                <thead class="ui-widget ui-widget-content" >
                                    <tr class="ui-widget-header" style="height: 23px">          
                                        <th align="center" width="120">Tipo Pago</th>
                                        <th>Producto</th>
                                        <th align="center" width="80">Precio</th>
                                        <th align="center" width="80">Cantidad</th>
                                        <th align="center" width="80">Inicial</th>
                                        <th align="center" width="80">Mensual</th>
                                        <th align="center" width="90">N° Meses</th>
                                        <th align="center" width="80px">IMPORTE S/.</th>
                                        <th width="20px">&nbsp;</th>
                                    </tr>
                                </thead>  
                                <tbody>
                                    <?php 
                                        if(count($rowsd)>0)
                                        {    
                                            foreach ($rowsd as $i => $r) 
                                            {       
                                                $nro= $r['nromeses'];
                                                $men= $r['cuota'];                                        
                                                $ini= $r['inicial'];
                                                $subt= (floatval($nro) * floatval($men))+ $ini;
                                                    
                                                ?>
                                                <tr class="tr-detalle">
                                                    <td align="left"><?php echo $r['descripcion']; ?><input type="hidden" name="idtipopago[]" value="<?php echo $r['idtipopago']; ?>" /></td>
                                                    <td><?php echo $r['producto']; ?>
                                                        <input type="hidden" name="idproducto[]" value="<?php echo $r['idproducto']; ?>" />
                                                        <input type="hidden" name="producto[]" value="<?php echo $r['producto']; ?>" />
                                                        <input type="hidden" name="idfinanciamiento[]" value="<?php echo $r['idfinanciamiento']; ?>" />
                                                    </td>
                                                    <td align="rigth">
                                                        <?php echo $r['preciocash']; ?><input type="hidden" name="precio[]" value="<?php echo $r['preciocash']; ?>" />
                                                    </td>
                                                    <td align="rigth">
                                                        <?php echo $r['cantidad']; ?><input type="hidden" name="cantidad[]" value="<?php echo $r['cantidad']; ?>" />
                                                    </td>
                                                    <td>
                                                        <?php echo $r['inicial']; ?><input type="hidden" name="inicial[]" value="<?php echo $r['inicial']; ?>" />
                                                    </td>
                                                    <td>
                                                        <?php echo $r['cuota']; ?><input type="hidden" name="mensual[]" value="<?php echo $r['cuota']; ?>" />
                                                    </td>
                                                    <td>
                                                        <?php echo $r['nromeses']; ?><input type="hidden" name="nromeses[]" value="<?php echo $r['nromeses']; ?>" />
                                                    </td>
                                                    <td><?php echo $subt; ?></td>
                                                    <td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>
                                                </tr>
                                                <?php    
                                                }  
                                        }
                                     ?>                      
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" align="right"><b>SUB TOTAL S/.</b></td>
                                        <td align="right"><b>0.00</b></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" align="right"><b>IGV S/.</b></td>
                                        <td align="right"><b>0.00</b></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" align="right"><b>TOTAL S/.</b></td>
                                        <td align="right"><b>0.00</b></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                    $idsol=$obj->idsolicitud;
                    if($idsol!='')
                        {
                    ?>
                    <div id="tabs-6">
                        <label class="labels">Observaciones:</label><br />
                        <textarea name="obs" id="obs" class="ui-widget-content ui-corner-all text" rows="3" style="margin-left: 10px; width: 300px;" title="Observaciones" ><?php echo $obj->obs; ?></textarea>
                        <br />

                        <span class="inputtext2 ui-corner-all">                            
                            <label style="padding-right:14px">Aprovado&nbsp;<input name="estadosol" type="radio" value="2"  id="Estado1" <?php if ($obj->estado==2) echo "checked='checked'"?> /></label>
                            <label style="padding-right:14px">Rechazado&nbsp;<input name="estadosol" type="radio" value="3"  id="Estado2" <?php if ($obj->estado==3) echo "checked='checked'"?> /></label>
                            <input type="hidden" name="estadosolicitud" id="estadosolicitud" value="" />
                        </span>
                    </div>
                    <?php
                    }
                ?>
                
            </div>           
        </div>
    </fieldset>
    <!-- <div id="div-detalle">
    
    </div> -->
</form>
</div>

<div id="divFinanciamiento" style="display: none;">
</div>