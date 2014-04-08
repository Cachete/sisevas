var afecto = false;
$(function() 
{       
    $("input[type=text]").focus(function(){this.select();});
    $("#idtipodocumento").focus();
    $("#idmadera").change(function(){$("#largo_ma").focus();getPrice($(this).val(),1);});
    $("#idmelamina").change(function(){$("#cantidad_me").focus();getPrice($(this).val(),2);});
    $("#idlinea").change(function(){load_melamina($(this).val());});
    $("#table-ma").on('click','#addDetail_ma',function(){addDetailMa();})
    $("#table-ma").on('keyup','.input_ma',function(){var st = calcSubTotalMa(); $("#total_ma").val(st);})
    $("#fechae,#fechaguia").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});
    $("#table-me").on('click','#addDetail_me',function(){addDetailMe();});
    $("#table-me").on('keyup','.input_me',function(){var st = calcSubTotalMe(); $("#total_me").val(st);});
    $("#table-detalle").on('click','.boton-delete',function(){var v = $(this).parent().parent().remove();caltotal();})
    $("#box-tipo-ma").on('click','input[name|="tipo"]',function(){
        valor = $("input[name='tipo']:checked").val();
        if(valor==1){ $("#box-madera").css("display","block");$("#box-melamina").css("display","none"); }
          else { $("#box-madera").css("display","none");$("#box-melamina").css("display","block"); }
    });    
    $("#idtipodocumento").change(function(){
      $("#serie").focus();
    });
    verifAfecto();
    $("#aigv").click(function(){
       verifAfecto();
    });
    
    $( "#ruc" ).autocomplete({
        minLength: 0,
        source: 'index.php?controller=proveedor&action=get&tipo=1',
        focus: function( event, ui ) 
        {
            $( "#ruc" ).val( ui.item.ruc );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idproveedor").val(ui.item.idproveedor);
            $( "#ruc" ).val( ui.item.ruc );
            $( "#proveedor" ).val( ui.item.razonsocial );                                    
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.ruc +" - " + item.razonsocial + "</a>" )
            .appendTo(ul);
      };

    $( "#proveedor" ).autocomplete({
        minLength: 0,
        source: 'index.php?controller=proveedor&action=get&tipo=2',
        focus: function( event, ui ) 
        {
            $( "#proveedor" ).val( ui.item.razonsocial );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idproveedor").val(ui.item.idproveedor);
            $( "#ruc" ).val( ui.item.ruc );
            $( "#proveedor" ).val( ui.item.razonsocial );
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.razonsocial + " - "+item.ruc+"</a>" )
            .appendTo(ul);
      };

});
function getPrice(id,tipo)
{   
   var c = "madera";
   if(tipo==2) c = "melamina";
   $.get('index.php','controller='+c+'&action=getPrice&id='+id,function(p){
      if(tipo==1) {$("#precio_ma").val(p);}
        else {$("#precio_me").val(p);var tme = calcSubTotalMe(); $("#total_me").val(tme);}
   })
}
function verifAfecto()
{
  if($('#aigv').is(':checked')) afecto = true;
   else afecto = false;       
  caltotal();
}
function load_melamina(idl)
{ 
  if(idl!="")
  {    
    $("#idmelamina").empty().append('<option value="">Cargando...</option>');
    $.get('index.php','controller=melamina&action=getList&idl='+idl,function(r){    
      html = '<option value="">Seleccione...</option>';
      $.each(r,function(i,j){
        html += '<option value="'+j.idproducto+'">'+j.descripcion+'</option>';
      });      
      $("#idmelamina").empty().append(html);
    },'json');
  }
}
function calcSubTotalMa()
{
    var largo = parseFloat($("#largo_ma").val()),
        alto = parseFloat($("#alto_ma").val()),
        espesor = parseFloat($("#espesor_ma").val()),
        vol = largo*alto*espesor/12;        

        $("#volumen_ma").val(vol.toFixed(2));

        var prec=parseFloat($("#precio_ma").val()),
            cant=parseFloat($("#cantidad_ma").val()),
            total = 0;
        var vt = vol*cant;
        $("#volument_ma").val(vt.toFixed(2));
    total = cant*prec*vol;
    return total.toFixed(2);
}
function calcSubTotalMe()
{
    var cant=parseFloat($("#cantidad_me").val()),
        prec=parseFloat($("#precio_me").val()),
        total = 0;
    total = cant*prec;    
    return total.toFixed(2);
}
function calcTotalPeso()
{
    var cant=parseFloat($("#volumen_me").val()),
        peso=parseFloat($("#peso_me").val()),
        total = 0;
    total = cant*peso;
    return total.toFixed(2);
}
function addDetailMa()
{
    bval = true;
    bval = bval && $("#idmadera").required();
    bval = bval && $("#largo_ma").required();
    bval = bval && $("#alto_ma").required();
    bval = bval && $("#espesor_ma").required();
    bval = bval && $("#cantidad_ma").required();    
    bval = bval && $("#volumen_ma").required();
    bval = bval && $("#precio_ma").required();
    if(!bval) return 0;
    var tipo=1,        
        idma=$("#idmadera").val(),
        made=$("#idmadera option:selected").html(),
        largo=parseFloat($("#largo_ma").val()),
        alto=parseFloat($("#alto_ma").val()),
        espesor=parseFloat($("#espesor_ma").val()),
        vol=parseFloat($("#volumen_ma").val()),
        cant=parseFloat($("#cantidad_ma").val()),
        volt=parseFloat($("#volument_ma").val()),
        prec=parseFloat($("#precio_ma").val()),
        total=calcSubTotalMa();
    if(cant<=0) {alert('La cantidad debe ser mayor que 0'); $("#cantidad_ma").focus(); return 0;}   
    if(vol<=0) {alert('La Volumen debe ser mayor que 0'); $("#largo_ma").focus(); return 0;}   
    if(prec<=0) {alert('La precio debe ser mayor que 0'); $("#precio_ma").focus(); return 0;}
    addDetalle(tipo,idma,made,largo.toFixed(2),alto.toFixed(2),espesor.toFixed(2),vol.toFixed(2),cant,volt.toFixed(2),prec.toFixed(2),total);
    clearMa();
}
function addDetailMe()
{
    bval = true;
    bval = bval && $("#idmelamina").required();
    bval = bval && $("#cantidad_me").required();
    bval = bval && $("#precio_me").required();
    if(!bval) return 0;
    var tipo=2,        
        idma=$("#idmelamina").val(),
        mela=$("#idlinea option:selected").html()+', '+$("#idmelamina option:selected").html(),
        cant=parseFloat($("#cantidad_me").val()),
        prec=parseFloat($("#precio_me").val()),
        total=calcSubTotalMe();        
    if(cant<=0) {alert('La cantidad debe ser mayor que 0'); $("#cantidad_me").focus(); return 0;}
    addDetalle(tipo,idma,mela,'','','','',cant,'',prec.toFixed(2),total);
    clearMe();    
}
function addDetalle(tipo,idtipo,dtipo,largo,alto,espesor,vol,cant,volt,precio,total)
{
    ntipo = '';
    if(tipo==1) ntipo = 'MADERA'; 
      else ntipo = 'MELAMINA';
    var html = '';
    html += '<tr class="tr-detalle"><td align="left">'+ntipo+'<input type="hidden" name="tipod[]" value="'+tipo+'" /></td>';
    html += '<td>'+dtipo+'<input type="hidden" name="idtipod[]" value="'+idtipo+'" /></td>';
    
    html += '<td align="center">'+largo+'<input type="hidden" name="largod[]" value="'+largo+'" /></td>';
    html += '<td align="center">'+alto+'<input type="hidden" name="altod[]" value="'+alto+'" /></td>';
    html += '<td align="center">'+espesor+'<input type="hidden" name="espesord[]" value="'+espesor+'" /></td>';
    html += '<td align="center">'+vol+'</td>';
    
    html += '<td align="center">'+cant+'<input type="hidden" name="cantd[]" value="'+cant+'" /></td>';
    html += '<td align="center">'+volt+'</td>';
    html += '<td align="right">'+precio+'<input type="hidden" name="preciod[]" value="'+precio+'" /></td>';    
    html += '<td align="right">'+total+'</td>';
    html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
    html += '</tr>';    
    $("#table-detalle").find('tbody').append(html);
    caltotal();
}
function caltotal()
{
   var st = 0,
       igv = $("#igv_val").val(),
       tigv = 0,
       t = 0;
   $("#table-detalle tbody tr").each(function(idx,j)
   {
      mont = $(j).find('td:eq(9)').html();
      mont = mont.replace(",","");
      mont = parseFloat(mont);
      if(!isNaN(mont)) st += mont;
   });
   if(afecto)
   {
      tigv = st*igv/100;
      t = st+tigv;
   }
   else 
   {
      tigv = 0;
      t = st+tigv;
   }
      
   $("#table-detalle tfoot tr:eq(0) td:eq(1)").empty().append('<b>'+st.toFixed(2)+'</b>');
   $("#table-detalle tfoot tr:eq(1) td:eq(1)").empty().append('<b>'+tigv.toFixed(2)+'</b>');
   $("#table-detalle tfoot tr:eq(2) td:eq(1)").empty().append('<b>'+t.toFixed(2)+'</b>');
}
function clearMa()
{
  $("#idmadera").val("");
  $(".input_ma,#total_ma").val("0.00");
  $("#cantidad_ma").val("1");
  $("#idmadera").focus();
}
function clearMe()
{
  $("#idmelamina").val("");
  $("#cantidad_me,#precio_me,#peso_me,#peso_t_me,#total_me").val("0.00");  
  $("#idmelamina").focus();
}
function nItems()
{
  var c = 0;
  $("#table-detalle tbody tr").each(function(idx,j){c += 1;});
  return c;
}
function save()
{
  bval = validar_frm();  
  if ( bval ) 
  {
      var ni = nItems();
      if(ni<=0) { alert("Aun no a ingresado ningun tipo de producto al detalle"); return 0; }
      var str = $("#frm-ingresom").serialize();      
      if(confirm('Realmente deseas confirmar el registro de la compra?'))
      {
        $.post('index.php',str,function(res)
        {
          if(res[0]==1)
          {
            $("#box-frm").dialog("close");
            gridReload();
          }
          else
          {
            alert(res[1]);
          }
          
        },'json');
      }
  }
  return false;
}
function validar_frm()
{
  var tipo = $("input[name='tipo']:checked").val(),
      bval = true;
  bval = bval && $("#idmovimientosubtipo").required();  
  if(tipo==2)
  {
     bval = bval && $("#idtipodocumento").required();
     bval = bval && $("#serie").required();
     bval = bval && $("#numero").required();
     bval = bval && $("#ruc").required();
     if(bval)
     {
       if($("#idproveedor").val()=="") 
       {
         alert("Ingrese el proveedor correctamente");
         $("#ruc").focus();
         bval = false;
       }
     }
  }
  return bval;
}