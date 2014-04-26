<script type="text/javascript">
	$(document).ready(function(){
		loading();
		$( "#sortable" ).sortable();
    	$( "#sortable" ).disableSelection();
		$("#idcompetencia").change(function(){loadAspectos($(this).val());});
		$("#add").click(function(){ addItem(); });
		$("#idaspecto").change(function(){getValores();});
		$("#save_change").click(function(){save();})
		$("#idconsultorio").change(function(){getValores();});
		$( "#parametro" ).autocomplete({
        minLength: 0,
        source: 'index.php?controller=parametros&action=get',
        focus: function( event, ui ) 
        {
            $( "#parametro" ).val( ui.item.ruc );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#parametro").val(ui.item.descripcion);
            $( "#idparametro" ).val( ui.item.idparametro );       
            $("#valor").focus();
            return false;
        }
	    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
	        return $( "<li></li>" )
	            .data( "item.autocomplete", item )
	            .append( "<a>" + item.descripcion + "</a>" )
	            .appendTo(ul);
	      };
	});
	function loading()
	{
		$("#idcompetencia,#idconsultorio").css("width","auto");
		$("#idaspecto").css("width","250px");
	}
	function loadAspectos(idc)
	{
		$.get('index.php','idc='+idc+'&controller=aspectos&action=getAspectos',function(r){
			var options = "<option value=''>Seleccione....</option>";
			$.each(r,function(i,j){
				options += "<option value='"+j['id']+"'>"+j['descripcion']+"</option>";
			});						
			$("#idaspecto").empty().append(options);
		},'json');
		$("#sortable").empty();
	}
	function addItem()
	{
		var newItem = '',
			param = $("#parametro").val(),
			idparam = parseInt($("#idparametro").val()),
			val = $("#valor").val();
        if(!verifar_item(idparam))
        {        	
        	$.get('index.php','controller=valores&action=vParametro&idparametro='+idparam,function(d)
				{						
					if(d==0)
					{
						newItem += '<li class="ui-state-default"><span class="box-boton-x"><a class="boton-x">X</a></span>';
						newItem += '<p style="height:70px;">'+param+'</p>';
						newItem += '<input type="text" name="valor_item[]" class="valor-item text-item" value="'+val+'" onkeypress="return permite(event,\'num\')" />';
						newItem += '<input type="hidden" name="id_parametro_item[]" class="id-param-item text-item" value="'+idparam+'"  />';
						newItem += '<input type="hidden" name="id_valor_item[]" class="id-valor-item" value=""  />';
						newItem += '</li>';
						$("#sortable").append(newItem);
						$("#sortable li span").on('click','a',function(){ $(this).parent().parent().fadeOut('500',function(){$(this).remove();});});
						clearParam();
					}
					else
		        	{
		        		alert("Este parametro ya esta siendo usado.");
		        	}
				}
			);
        	
        }
        else
        {
        	alert("Este Parametro ya fue agregado.");
        }
	}
	function verifar_item(ii)
	{
		var flag = false;
		$('.id-param-item').each(function(i,j){x=parseInt($(j).val());if(x==ii)flag=true;});
		return flag;
	}
	function clearParam()
	{
		$("#parametro,#idparametro,#valor").val("");
		$("#parametro").focus();
	}
	function save()
	{
        var items = new Array(),
                c = 0;
        $("#sortable li").each(function(i,j){
            var valor = $(j).find('.valor-item').val(),
                    idparam = $(j).find('.id-param-item').val(),
                    idvalor = $(j).find('.id-valor-item').val(),
                    order = (i+1),
                    idconsultorio = $("#idconsultorio").val(),
                    idaspecto = $("#idaspecto").val();
                items[c] = {	'idvalor':idvalor,
                                'idparam':idparam,
                                'valor':valor,
                                'order':order,
                                'idconsultorio':idconsultorio,
                                'idaspecto':idaspecto };
                c += 1;
        });		
        var sendi = json_encode(items);		
        $.post('index.php','controller=valores&action=save&items='+sendi,function(data){
                if(data[0]=='1')
                	alert("Se ha grabado los cambios satisfactoriamente.");
                else
                	alert("Ha ocurrido un error, intentelo nuevamente.");
        },'json');
	}
	function getValores()
	{
		$("#sortable").empty();
		var idconsultorio = $("#idconsultorio").val(),
        	idaspecto = $("#idaspecto").val(),
        	newItem = '';
       	if(idconsultorio!="")
       	{
       		$.get('index.php','controller=valores&action=getValores&idconsultorio='+idconsultorio+'&idaspecto='+idaspecto,function(rows){
	        	$.each(rows,function(i,j)
	        	{        		
					newItem += '<li class="ui-state-default"><span class="box-boton-x"><a class="boton-x">X</a></span>';
					newItem += '<p style="height:70px;">'+j.parametro+'</p>';
					newItem += '<input type="text" name="valor_item[]" class="valor-item text-item" value="'+j.valor+'" onkeypress="return permite(event,\'num\')" />';
					newItem += '<input type="hidden" name="id_parametro_item[]" class="id-param-item text-item" value="'+j.idparametro+'"  />';
					newItem += '<input type="hidden" name="id_valor_item[]" class="id-valor-item" value="'+j.idvalor+'"  />';
					newItem += '</li>';
	        	});
	        	$("#sortable").append(newItem);
	        	$("#sortable li span").on('click','a',function(){ $(this).parent().parent().fadeOut('500',function(){$(this).remove();});});
	        },'json');
       	}
	}
</script>
<style type="text/css">
	.box-form, .box-param {  padding:10px 0; }
	.box-form div { float: left; margin-left: 15px;}
	#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	#sortable li { margin: 3px 3px 3px 0;  float: left; width: 217px; height: 130px; }
	#sortable li p { padding: 10px; font-size: 10px; text-align: justify; }
	.box-boton-x { position: relative; left: 203px; top:1px;}
	.box-boton-x a { color:#FFF !important; font-weight: bold; padding: 0px 3px; background: #E75547; }
	.boton-x { cursor: pointer; }
	.text-item { border:0; font-size: 18px; color: #666; text-align: center; width: 100%; background: transparent;}
</style>
<div class="div_container" style="padding:0px 2px">
<h6 class="ui-widget-header ui-state-hover">Asignacion de Valores</h6>
<div style=" width: 100%;">
    <div style="padding:20px; ">
		<div class="box-form ui-corner-all ui-widget-content">
			<div>
				<label class="labels" style="width:auto;">Competencia :</label> <br/>
				<?php echo $competencias; ?>
			</div>
			<div>
				<label class="labels" style="width:auto;">Aspectos :</label><br/>
				<select name="idaspecto" id="idaspecto" class="ui-widget-content text ui-corner-all">
					<option value="">...</select>
				</select>
			</div>
			<div>
				<label class="labels" style="width:auto;">Consultorio :</label><br/>
				<?php echo $consultorios; ?>
			</div>
			<div style="clear:both; float:none"></div>
			<div>
				<label class="labels" style="width:auto;">Parametros :</label>
				<input type="hidden" name="idparametro" id="idparametro" value="" />
				<input type="text" name="parametro" id="parametro" value="" class="ui-widget-content ui-corner-all text" style="width:670px" />
				<input type="text" name="valor" id="valor" value="" class="ui-widget-content ui-corner-all text" style="width:60px;text-align:center; font-size:14px;" placeholder="Valor"  />
			</div>
			<div>
				<a href="#" class="myButton" id="add">Agregar</a>
			</div>
			<div style="clear:both; float:none"></div>
		</div>
		<div class="box-param" id="box-param">
			<ul id="sortable"></ul>
		</div>
		<div style="clear:both;"></div>
		<div class="ui-corner-all" style="text-align:center; padding:10px 0 5px 0; background:#dadada; margin-top:8px; ">
			<a href="#" id="save_change" class="myButton">Grabar Cambios</a>
		</div>
    </div>
</div>
<div style="clear:both"></div>
<div id="modulos" style="clear: both; margin-left: 115px;"></div>
</div>