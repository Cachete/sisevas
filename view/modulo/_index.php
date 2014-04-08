<script type="text/javascript">
var cNames = <?php   print_r(json_encode($colsNames)); ?>;
var cModel = <?php print_r(json_encode($colsModels)); ?>;
$(document).ready(function()
{
	jQuery("#list").jqGrid({
   	  url:'index.php?controller=modulo&action=indexGrid',
	    datatype: "json",
   	  colNames:cNames,
   	  colModel:cModel,
     	rowNum:10,
     	rowList:[10,20,30],
     	pager: '#pager',
     	sortname: 'idmodulo',
      viewrecords: true,
      width:898,
      height:300,
      sortorder: "desc",
      multiselect: false,
      rownumbers: true,
      caption:"Lista de Modulos Registrados"
});
jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,modal:false,sopt:['cn','bw']});
//jQuery("#list").jqGrid('','#pager',{edit:false,add:false,del:false});

$("#bsdata").click(function(){
  jQuery("#list").jqGrid(
            'searchGrid',
            {
                sopt:['cn','bw','eq','ne'],
                caption:'Andres...',
                modal:false
            }
  );
});

});
</script>
<div class="div_container">
<h6 class="ui-widget-header ui-state-hover">MODULOS</h6>

<div class="cont-grid">
  <div style="padding:10px;">
    <div style="padding:10px; border-bottom:0 " class="ui-widget-content ui-corner-top">
        <div style="padding:2px 0 7px">
          <label>Buscar por :</label>
          <select class="ui-widget-content ui-corner-all">
            <option>Descripcion</option>
          </select>
          <input type="text" name="filtro" id="filtro" value="" class="ui-widget-content ui-corner-all" style="width:250px" />
          <input type="button" name="btnSearch" id="btnSearch" value="Search" />
          <!-- <input type="BUTTON" id="bsdata" value="Search" /> -->        
        </div>
        <div class="operaciones">   
          <a class="nuevo" href="index.php?controller=<?php echo $name; ?>&action=create" title="Nuevo Registro">            
              <span  class="box-boton boton-new"></span>
              <label>Nuevo</label>
          </a>
          <a class="editar" onclick="editar();" title="Editar Registro">            
              <span  class="box-boton boton-edit"></span> 
              <label>Editar</label>
          </a>
          <a class="eliminar" onclick="eliminar();" title="Eliminar Registro" style="color:red;">            
              <span  class="box-boton boton-delete"></span> 
              <label>Eliminar</label>
          </a>     
          <a class="ver" onclick="ver()" title="Ver Registro">            
              <span class="box-boton boton-view"></span> 
              <label>Ver</label>
          </a>   
        </div>     
    </div>
    
    <div>
      <table id="list" ></table>
      <div id="pager"></div>
    </div>
  </div>
</div>
</div>