$(function() 
{       
    $("#dnicliprof").focus();
    $("#idtipopago").val('2');
    $("#IngresosPare").on('keyup','#ingresocon',function(){CalcTotalIng(); });
    $("#idfinanciamiento").css({'width':'170px'});
    $("#idtipopago").css({'width':'150px'});
    $("#idtipopago").attr('disabled','disabled');
    $("#sexo, #idestado_civil,#idgradinstruccion, #idtipovivienda").css({'width':'210px'});
    $("#fecha,#fechavenc,#fechanac").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});
    $("#tabs").tabs();

    $("#desproducto").on('keyup','#valorcuota',function(){CalcTotalCre(); });
    
    $("#idfinanciamiento").change(function(){
        load_finaciamiento($(this).val()); 
    });

    verifAfecto();
    $("#aigv").click(function(){
       verifAfecto();
    });

    $("#table_solicitud").on('click','#calcularfi',function(){CalcularFinanc(); });
    $("#table_solicitud").on('click','#addDetail',function(){addDetail();});
    $("#table-detalle").on('click','.boton-delete',function(){var v = $(this).parent().parent().remove();caltotal();})

    $( "#divFinanciamiento" ).dialog({
      autoOpen: false,
      width: 400,
      
    });

    idsol = $("#idsolicitud").val();
    $("#nrorecibo").val('0000'+idsol);

    Estado =$("input[name='estadosol']:checked").val();
    
    // Buscar Cliente con DNI
    $("#dni").autocomplete({
        minLength: 0,
        source: 'index.php?controller=clientes&action=get&tipo=1',
        focus: function( event, ui ) 
        {
            $( "#dni" ).val( ui.item.dni );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idcliente").val(ui.item.idcliente);
            $("#dni" ).val( ui.item.dni );
            $("#nomcliente" ).val( ui.item.nomcliente );
            $("#sexo").val(ui.item.sexo);
            $("#direccion" ).val( ui.item.direccion );
            $("#referencia" ).val( ui.item.referencia ); 
            $("#telefono").val(ui.item.telefono);            
            $("#ocupacion" ).val( ui.item.ocupacion );
            $("#cargo" ).val( ui.item.cargo ); 
            $("#idestado_civil").val(ui.item.idestado_civil);
            $("#idgradinstruccion" ).val( ui.item.idgradinstruccion );
            $("#idtipovivienda" ).val( ui.item.idtipovivienda );
            $("#trabajo").val(ui.item.trabajo);
            $("#dirtrabajo").val(ui.item.dirtrabajo);
            $("#teltrab").val(ui.item.teltrab);
            $("#ingresocli").val(ui.item.ingreso);
            $("#cargafam").val(ui.item.carga_familiar);
            $("#dnicon").val(ui.item.con_dni);
            $("#nomconyugue").val(ui.item.nomconyugue);
            $("#con_ocupacion").val(ui.item.con_ocupacion);
            $("#con_trabajo").val(ui.item.con_trabajo);
            $("#con_dirtrabajo").val(ui.item.con_dirtrabajo);
            $("#con_cargo").val(ui.item.con_cargo);
            $("#ingresocon").val(ui.item.con_ingreso);
            $("#con_teltrab").val(ui.item.con_teltrab);

            return false;
        }
      }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.dni +" - " + item.nomcliente + "</a>" )
            .appendTo(ul);
      };

    // Buscar Cliente con nombres
    $("#nomcliente").autocomplete({
        minLength: 0,
        source: 'index.php?controller=clientes&action=get&tipo=2',
        focus: function( event, ui ) 
        {
            $( "#nomcliente" ).val( ui.item.nomcliente );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idcliente").val(ui.item.idcliente);
            //alert
            $("#dni" ).val( ui.item.dni );
            $("#nomcliente" ).val( ui.item.nomcliente );
            $("#sexo").val(ui.item.sexo);
            $("#direccion" ).val( ui.item.direccion );
            $("#referencia" ).val( ui.item.referencia ); 
            $("#telefono").val(ui.item.telefono);            
            $("#ocupacion" ).val( ui.item.ocupacion );
            $("#cargo" ).val( ui.item.cargo ); 
            $("#idestado_civil").val(ui.item.idestado_civil);
            $("#idgradinstruccion" ).val( ui.item.idgradinstruccion );
            $("#idtipovivienda" ).val( ui.item.idtipovivienda );
            $("#trabajo").val(ui.item.trabajo);
            $("#dirtrabajo").val(ui.item.dirtrabajo);
            $("#teltrab").val(ui.item.teltrab);
            $("#ingresocli").val(ui.item.ingreso);
            $("#cargafam").val(ui.item.carga_familiar);
            $("#dnicon").val(ui.item.con_dni);
            $("#nomconyugue").val(ui.item.nomconyugue);
            $("#con_ocupacion").val(ui.item.con_ocupacion);
            $("#con_trabajo").val(ui.item.con_trabajo);
            $("#con_dirtrabajo").val(ui.item.con_dirtrabajo);
            $("#con_cargo").val(ui.item.con_cargo);
            $("#ingresocon").val(ui.item.con_ingreso);
            $("#con_teltrab").val(ui.item.con_teltrab);

            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.nomcliente +" - " + item.dni + "</a>" )
            .appendTo(ul);
      };

    //buscar producto
    $("#producto").autocomplete({        
        minLength: 0,
        source: 'index.php?controller=subproductosemi&action=get&tipo=2',
        focus: function( event, ui ) 
        {
            $( "#producto" ).val( ui.item.producto );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idsubproductos_semi").val(ui.item.idsubproductos_semi);           
            $( "#producto" ).val( ui.item.producto );
            $( "#precio" ).val( ui.item.precio );                                    
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.producto + "</a>" )
            .appendTo(ul);
      };

    // Buscar Cliente con proforma - DNI
    $("#dnicliprof").autocomplete({
        minLength: 0,
        source: 'index.php?controller=clientes&action=getProf&tipo=1',
        focus: function( event, ui ) 
        {
            $( "#dni" ).val( ui.item.dni );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idcliente").val(ui.item.idcliente);
            $("#dni" ).val( ui.item.dni );
            $("#nomcliente" ).val( ui.item.nomcliente );
            $("#sexo").val(ui.item.sexo);
            $("#direccion" ).val( ui.item.direccion );
            $("#referencia" ).val( ui.item.referencia ); 
            $("#telefono").val(ui.item.telefono);            
            $("#ocupacion" ).val( ui.item.ocupacion );
            $("#cargo" ).val( ui.item.cargo ); 
            $("#idestado_civil").val(ui.item.idestado_civil);
            $("#idgradinstruccion" ).val( ui.item.idgradinstruccion );
            $("#idtipovivienda" ).val( ui.item.idtipovivienda );
            $("#trabajo").val(ui.item.trabajo);
            $("#dirtrabajo").val(ui.item.dirtrabajo);
            $("#teltrab").val(ui.item.teltrab);
            $("#ingresocli").val(ui.item.ingreso);
            $("#cargafam").val(ui.item.carga_familiar);
            $("#dnicon").val(ui.item.con_dni);
            $("#nomconyugue").val(ui.item.nomconyugue);
            $("#con_ocupacion").val(ui.item.con_ocupacion);
            $("#con_trabajo").val(ui.item.con_trabajo);
            $("#con_dirtrabajo").val(ui.item.con_dirtrabajo);
            $("#con_cargo").val(ui.item.con_cargo);
            $("#ingresocon").val(ui.item.con_ingreso);
            $("#con_teltrab").val(ui.item.con_teltrab);
            $("#idproforma").val(ui.item.idproforma);
            //idpro= 
            load_productos(ui.item.idproforma);
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.dni +" - " + item.nomcliente + "</a>" )
            .appendTo(ul);
      };



    $( "#divFinanciamiento" ).dialog({
      autoOpen: false,
      width: 400,
      
    });


});

function load_nrecibo()
{
  $.get('index.php','controller=tipodocumento&action=Correlativo&idtp='+idtp,function(r){          
        //  $("#Serie").val(r.serie);
          $("#Numero").val(r.numero);

      },'json');
}

function CalcTotalCre()
{
  var Ini=$("#inicial").val()
  var Nro=$("#nrocuota").val()
  var Val=$("#valorcuota").val()
  Val=Val.replace(",","");
  Ini=Ini.replace(",","");
  Total=((Nro) * (parseFloat(Val))) + parseFloat(Ini);

  $("#total").val(parseFloat(Total).toFixed(2));
}

function CalcTotalIng()
{
  var InigC=$("#ingresocon").val()
  var InigI=$("#ingresocli").val()
  
  InigI=InigI.replace(",","");
  InigC=InigC.replace(",","");
  Total= (parseFloat(InigI)) + (parseFloat(InigC));

  $("#totaling").val(parseFloat(Total).toFixed(2));
}

/**/
function load_productos(idproforma)
{
  $.get('index.php','controller=proformas&action=load_productos&idproforma='+idproforma,function(r){
      
      $("#div-detalle").empty().append(r);

    });
}

/**/

function load_finaciamiento(idfinanc)
{
  if(idfinanc!="")
  {    
    $.get('index.php','controller=financiamiento&action=RecFinanciamiento&idfinanc='+idfinanc,function(r){
      
      var html = '';      
      html += '<table align="center" width="263" border="1" cellspacing="0" class="ui-widget ui-widget-content" id="TbFactores">';
      html += '<thead class="ui-widget-header">';
      html += '<tr title="Cabecera">';
      html += '<th scope="col" width="104" align="center">Nro Meses</th>';
      html += '<th scope="col" width="114" align="center">Importe Cuota</th>';           
      html += '</tr>';
      html += '</thead>';
      html += '<tbody>';

      var cont=0;
      $.each(r,function(i,j){
        cont=cont +1;
        
        html += '<tr id="'+cont+'" style="background-color:#FFFFFF" class="factornum" >';
        html += '<td align="center"><label class="Mes">'+j.meses+'</label></td>';
        html += '<td align="right"><label class="Factor">'+j.factor+'</label><label class="Importe"></label></td>';
        html += '</tr>';
            
      });

      html += '<input type="hidden" id="NroFactores" value="'+cont+'" />';
      html += '<input type="hidden" id="Adicional" value="'+r[0].adicional+'" />';
      html += '</tbody>';
      html += '<table>';
      
      $("#divFinanciamiento").empty().append(html);

    },'json');
  }
}

function CalcularFinanc()
{ 
  bval = true;
  bval = bval && $("#idfinanciamiento" ).required();
  bval = bval && $("#producto" ).required();
  bval = bval && $("#precio" ).required();
  bval = bval && $("#cantidad" ).required();  
  bval = bval && $("#inicial" ).required();

  if ( bval ) 
  {
    $("#idfinanciamiento" ).required();
    $("#precio" ).required();
    $("#cantidad" ).required();
    $("#producto" ).required();
    $("#inicial" ).required();
  
    Calcular2()
    $( "#divFinanciamiento" ).dialog( "open" );
    $(".Factor").css({'display':'none'});  
   
    $("#TbFactores").on('click','#factornum',function(){
      var mes=$(this).find('#NroMeses').html();
      var mesual=$(this).find('#Mensual').html();
    });
  } 
}

function Calcular2()
{ 
  bval = true;
  bval = bval && $("#idfinanciamiento" ).required();
  bval = bval && $("#producto" ).required();
  bval = bval && $("#precio" ).required();
  bval = bval && $("#cantidad" ).required();  
  bval = bval && $("#inicial" ).required();
  //bval = bval && $("#inicial" ).required();

  if ( bval ) 
  { 
    var j=$("#NroFactores").val()
    var Precio=$("#precio").val()
    var Cant=$("#cantidad").val()
    Cant=Cant.replace(",","");
    Precio=Precio.replace(",","");
    Precio=(parseFloat(Precio)) * (parseFloat(Cant));

    var Inicial=$("#inicial").val()
    var Inicial=parseFloat(Inicial.replace(",",""))
    
    var Adional=0
    if ( $("#ChkAdicional").is(':checked') )
      Adional = parseFloat($("#Adicional").val())
    
    var Factor,Meses,Importe=0;
    
    Precio=Precio+Adional;
    
    Precio=Precio-Inicial;
    //alert(Precio);
    Precio=(parseFloat(Precio)).toFixed(2);
    for(var i=1; i<=j; i++)
    {
      Factor = parseFloat($("#TbFactores tbody tr#"+i+" label.Factor").text())
      Importe =parseFloat(Precio)*parseFloat(Factor)

      $("#TbFactores tbody tr#"+i+" label.Importe").text(parseFloat(Importe).toFixed(2))
    }
  }
}

function Calcular3()
{  
  bval = true;
  bval = bval && $("#idfinanciamiento" ).required();
  bval = bval && $("#producto" ).required();
  bval = bval && $("#precio" ).required();
  bval = bval && $("#cantidad" ).required();  
  bval = bval && $("#inicial" ).required();
  bval = bval && $("#nromeses" ).required();

  if ( bval ) 
  { 
    var j=$("#NroFactores").val()
    var Precio=$("#precio").val()
    var Cant=$("#cantidad").val()
    Cant=Cant.replace(",","");
    Precio=Precio.replace(",","");
    Precio=(parseFloat(Precio)) * (parseFloat(Cant));

    var Inicial=$("#inicial").val()
    var Inicial=parseFloat(Inicial.replace(",",""))  
    
    var Adional=0
    if ( $("#ChkAdicional").is(':checked') )
      Adional = parseFloat($("#Adicional").val())
    
    var Factor,Meses,Importe=0;
    
    Precio=Precio+Adional;
    Precio=Precio-Inicial;

    Precio=(parseFloat(Precio)).toFixed(2);
    var NroMeses=parseFloat($("#NroMeses").val())
      Factor=0;

    for(var i=1; i<=j; i++)
    {
      Meses = parseInt($("#TbFactores tbody tr#"+i+" label.Mes").text())
        if (Meses==NroMeses)
        {
          Factor = parseFloat($("#TbFactores tbody tr#"+i+" label.Factor").text())
          break;
        }
    }
    Importe =parseFloat(Precio)*parseFloat(Factor)
      if (Factor!=0)
      $("#Mensual").val(parseFloat(Importe).toFixed(2));

  }

}

function verifAfecto()
{
  if($('#aigv').is(':checked')) afecto = true;
   else afecto = false;       
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
      mont = $(j).find('td:eq(7)').html();
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

function addDetail()
{
  
  bval = true;
  bval = bval && $("#idtipopago").required();
  bval = bval && $("#idfinanciamiento").required();
  bval = bval && $("#producto").required();
  bval = bval && $("#percio").required();
  bval = bval && $("#inicial").required();

  if(!bval) return 0;
    id= $("#idtipopago").val(),
    desc = $("#idtipopago option:selected").html(),
    idfinan=$("#idfinanciamiento").val(),
    pro=$("#producto").val(),
    idprod=$("#idsubproductos_semi").val(),        
    ini=$("#inicial").val(),
    mes=$("#NroMeses").val(),
    men=$("#Mensual").val(),
    can=parseFloat($("#cantidad").val()),
    prec=parseFloat($("#precio").val())
    total=calcSubTotalCre();

    if(can<=0) {alert('La cantidad debe ser mayor que 0'); $("#cantidad").focus(); return 0;}  
    if(prec<=0) {alert('La precio debe ser mayor que 0'); $("#precio").focus(); return 0;}
    
  addDetalleCre(id,desc,idfinan,pro,idprod,prec,ini,mes,men,can,total);
  clearCred();    
         
}

function calcSubTotal()
{
  var prec=parseFloat($("#precio").val()),
      cant=parseFloat($("#cantidad").val()),
      total = 0;  
  total = cant*prec;
  return total.toFixed(2);
}

function calcSubTotalCre()
{
    var Mensual = parseFloat($("#Mensual").val()),
        nro = parseFloat($("#NroMeses").val()), 
        ini = parseFloat($("#inicial").val());
        total = 0;
        total = (Mensual*nro) + ini; 
    return total.toFixed(2);
}

function addDetalleCre(id,desc,idfinan,pro,idprod,prec,ini,mes,men,can,total)
{
    if(idprod!='')
      {
        
        html += '<tr class="tr-detalle">';
        html += '<td>'+desc+'<input type="hidden" name="idtipopago[]" value="'+id+'" /></td>';   
        html += '<td>'+pro+'<input type="hidden" name="idfinanciamiento[]" value="'+idfinan+'" />';
        html += '<input type="hidden" name="idproducto[]" value="'+idprod+'" />';
        html += '<input type="hidden" name="producto[]" value="'+pro+'" /></td>';
        html += '<td>'+prec+'<input type="hidden" name="precio[]" value="'+prec+'" /></td>';
        html += '<td>'+can+'<input type="hidden" name="cantidad[]" value="'+can+'" /></td>';
        html += '<td>'+ini+'<input type="hidden" name="inicial[]" value="'+ini+'" /></td>';
        html += '<td>'+men+'<input type="hidden" name="mensual[]" value="'+men+'" /></td>';
        html += '<td>'+mes+'<input type="hidden" name="nromeses[]" value="'+mes+'" /></td>';
        html += '<td>'+total+'</td>';
        html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
        html += '</tr>';    
        $("#table-detalle").find('tbody').append(html);
        caltotal();
      }else
        {
          idprod=0;
          var html = '';
          html += '<tr class="tr-detalle">';
          html += '<td>'+desc+'<input type="hidden" name="idtipopago[]" value="'+id+'" /></td>';   
          html += '<td>'+pro+'<input type="hidden" name="idfinanciamiento[]" value="'+idfinan+'" />';
          html += '<input type="hidden" name="idproducto[]" value="'+idprod+'" />';
          html += '<input type="hidden" name="producto[]" value="'+pro+'" /></td>';
          html += '<td>'+prec+'<input type="hidden" name="precio[]" value="'+prec+'" /></td>';
          html += '<td>'+can+'<input type="hidden" name="cantidad[]" value="'+can+'" /></td>';
          html += '<td>'+ini+'<input type="hidden" name="inicial[]" value="'+ini+'" /></td>';
          html += '<td>'+men+'<input type="hidden" name="mensual[]" value="'+men+'" /></td>';
          html += '<td>'+mes+'<input type="hidden" name="nromeses[]" value="'+mes+'" /></td>';
          html += '<td>'+total+'</td>';
          html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
          html += '</tr>';    
          $("#table-detalle").find('tbody').append(html);
          caltotal();
        }  
}

function clearCred()
{
  //$("#idtipopago").val("");
  $("#idfinanciamiento").val("");
  $("#producto").val("");
  $("#precio").val("");
  $("#inicial").val("");
  $("#NroMeses").val("");
  $("#Mensual").val("");
  $("#cantidad").val("");  
  $("#idtipopago").focus();
}

function nItems()
{
  var c = 0;
  $("#table-detalle tbody tr").each(function(idx,j){c += 1;});
  return c;
}
/**/

function save()
{
  
  bval = true;        
  bval = bval && $( "#fecha" ).required(); 
  bval = bval && $("#fechavenc").required();
  bval = bval && $("#dni").required();
  bval = bval && $("#nomcliente").required();
  bval = bval && $("#idestado_civil").required();
  bval = bval && $("#cargafam").required();
  bval = bval && $("#idgradinstruccion").required();
  bval = bval && $("#idtipovivienda").required();


  Estado =$("input[name='estadosol']:checked").val();
  var str = $("#frm_verificacion").serialize();
  if ( bval ) 
  {
      $.post('index.php',str+'&Estado='+Estado,function(res)
      {
        if(res[0]==1){
          $("#box-frm").dialog("close");
          gridReload(); 
        }
        else
        {
          alert(res[1]);
        }
        
      },'json');
  }
  return false;

}