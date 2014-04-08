<script type="text/javascript">
var timeoutHnd;
var flAuto = true;
var cNames = <?php print_r(json_encode($colsNames)); ?>;
var cModel = <?php print_r(json_encode($colsModels)); ?>;
$(document).ready(function()
{
  $("#qry").focus();
  $("#list").jqGrid({
      url:'index.php?controller=<?php echo $controlador; ?>&action=indexGridList',
      datatype: "json",
      colNames:cNames,
      colModel:cModel,
      rowNum:10,
      rowList:[10,20,30],
      pager: '#pager',
      sortname: '1',
      viewrecords: true,             
      sortorder: "desc",
      multiselect: false,
      rownumbers: true,
      caption:"Lista de <?php if($titulo!="") echo $titulo; else echo $controlador; ?> Registrados"
    });
    $("#fltr").change(function(){
        $("#qry").focus();
    });
    $("#box-frm").dialog({
      modal:true,
      autoOpen:false,
      width:'auto',
      height:'auto',
      resizing:true,
      // maxHeight: 600,
      title:'Formulario de <?php if($titulo!="") echo $titulo; else echo $controlador; ?>'      
    });
    $("#a1").click( function(){
      var id = $("#list").jqGrid('getGridParam','selrow');
      if (id) 
      {
        var ret = $("#list").jqGrid('getRowData',id);
          opener.getData(ret);  
          window.close();
      }
      else alert("Porfavor seleccione un regisrto.");
    });
});
function doSearch(ev)
{
  if(!flAuto)
    return;
  if(timeoutHnd)
    clearTimeout(timeoutHnd)
  timeoutHnd = setTimeout(gridReload,500)
}
function gridReload()
{
  var fltr = $("#fltr").val();
  var qry = $("#qry").val();
  $("#list").jqGrid('setGridParam',{url:"index.php?controller=<?php echo $controlador; ?>&action=indexGridList&f="+fltr+"&q="+qry,page:1}).trigger("reloadGrid");
}
function enableAutosubmit(state)
{
  flAuto = state;
}
</script>
<?php 
  if(isset($script)&&$script!="")
  {
    ?>
    <script type="text/javascript" src="js/app/<?php echo $script; ?>" ></script>
    <?php
  }
?>
<div class="div_container">
<h6 class="ui-widget-header ui-state-hover"><?php if($titulo!="") echo strtoupper($titulo); else echo strtoupper($controlador); ?>  </h6>
<div>
  <div style="padding:10px;">
    <div style="padding:10px; border-bottom:0 ">       
        <div style="padding:10px 0 0px; ">
              <label>Buscar por :</label>
              <?php echo $cmb_search; ?>
              <input type="text" name="qry" id="qry" value="" class="ui-widget-content ui-corner-all text" style="width:250px" onkeydown="doSearch(arguments[0]||event)" />
              <a href="javascript:" id="submitButton" onclick="gridReload()" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset"><span class="ui-icon ui-icon-search"></span>Buscar</a>
              <input type="checkbox" id="autosearch" checked="" onclick="enableAutosubmit(this.checked)"> AutoBusqueda
              <a href="javascript:" id="a1" class="fm-button ui-state-default ui-corner-all fm-button-icon-right ui-reset" style="margin-left:20px"><span class="ui-icon ui-icon-circle-arrow-s"></span>Obtener Datos</a>              
        </div>
    </div>
    <div>
      <table id="list">

      </table>
      <div id="pager"></div>
    </div>
  </div>
</div>  
</div>