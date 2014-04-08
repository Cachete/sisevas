$(function() 
{    
    $( "#descripcion" ).focus();
    $("#idubigeo").css({'width':'210px'});
    $("#idzona").css({'width':'95px'});
    $("#idrutas").css({'width':'130px'});
    $("#fechareg").datepicker({dateFormat:'dd/mm/yy','changeMonth':false,'changeYear':false});
    $("#idubigeo").change(function(){load_zona($(this).val());});

    $("#table-cli").on('click','#addDetail',function(){addDetail();});
    $("#table-detalle").on('click','.boton-delete',function(){var v = $(this).parent().parent().remove();})

    //Personal

    $("#dni").autocomplete({        
        minLength: 0,
        source: 'index.php?controller=personal&action=get&tipo=1',
        focus: function( event, ui ) 
        {
            $( "#dni" ).val( ui.item.dni );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idpersonal").val(ui.item.idpersonal);
            $( "#dni" ).val( ui.item.dni );
            $( "#personal" ).val( ui.item.nompersonal );
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.dni +" - " + item.nompersonal + "</a>" )
            .appendTo(ul);
      };

    //Clinte
    $("#dnicli").autocomplete({        
        minLength: 0,
        source: 'index.php?controller=clientes&action=get&tipo=1',
        focus: function( event, ui ) 
        {
            $( "#dnicli" ).val( ui.item.dni );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idcliente").val(ui.item.idcliente);
            $( "#dnicli" ).val( ui.item.dni );
            $( "#nombres" ).val( ui.item.nombres );
            $( "#apematerno" ).val( ui.item.apematerno );
            $( "#apepaterno" ).val( ui.item.apepaterno );
            $( "#direccion" ).val( ui.item.direccion );
            $( "#telefono" ).val( ui.item.telefono );                                   
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.dni +" - " + item.nomcliente + "</a>" )
            .appendTo(ul);
      };
      
      //buscar prodcto
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
            //$( "#precio" ).val( ui.item.precio );                                    
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.producto + "</a>" )
            .appendTo(ul);
      };

    /*----New Cliuente*/
    $("#box-frm-cliente").dialog({
      modal:true,
      autoOpen:false,
      width:'auto',
      height:'auto',
      resizing:true,
      title:'Formulario de Clientes',
      buttons: {'Registrar Cliente':function(){save_cliente();}}
    });

    $("#frm_hojaruta").on('click','#newCliente',function(){
        $.get('index.php?controller=clientes&action=create',function(html)
        {           
           $("#box-frm-cliente").empty().append(html);
           $("#box-frm-cliente").dialog("open");
           $("#descripcion").focus();
           
        });
    })
    /*------- FIN --------*/
});

function save_cliente()
{
    bval = true;        
    bval = bval && $( "#descripcion" ).required();
    if(bval)    
    {
       var str = $("#frm").serialize();       
       $.post('index.php',str,function(res)
       {
          if(res[0]==1)
          {
             $("#idlinea").append('<option value="'+res[2]+'">'+$("#descripcion").val()+'</option>');
             $("#box-frm-linea").dialog('close');
             $("#idcliente").val(res[2]);
          }
       },'json');
    }
}

function load_zona(IdZo)
{
  if(IdZo!="")
  {    
    $("#idzona").empty().append('<option value="">Cargando...</option>');
    $.get('index.php','controller=zona&action=getList&IdZo='+IdZo,function(r){    
      html = '<option value="">Seleccione...</option>';
      $.each(r,function(i,j){
        html += '<option value="'+j.idzona+'">'+j.descripcion+'</option>';
      });      
      $("#idzona").empty().append(html);
    },'json');
  }
}

function addDetail()
{
    
    bval = true;
    bval = bval && $("#dnicli").required();
    bval = bval && $("#cliente").required();
    bval = bval && $("#producto").required();
    bval = bval && $("#cantidad").required();
    if(!bval) return 0;
      id= $("#idcliente").val(),
      dni= $("#dnicli").val(),
      nomcli=$("#nombres").val(),
      appa= $("#apepaterno").val();
      apmat= $("#apematerno").val();
      dir=$("#direccion").val(),
      tel=$("#telefono").val(),
      prod=$("#producto").val(),
      idprod= $("#idsubproductos_semi").val(),
      obs=$("#observacion").val(),
      cant=$("#cantidad").val()
    
    addDetalle(dni,id,nomcli,appa,apmat,dir, tel, prod, idprod, obs,cant);
    clearPer();    
}

function addDetalle(dni,id,nomcli,appa,apmat,dir, tel, prod, idprod, obs,cant)
{
    if( idprod=='')
    {
      idprod=1;
      var html = '';
      html += '<tr class="tr-detalle">';
      html += '<td>'+dni+'<input type="hidden" name="idcliente[]" value="'+id+'" /></td>';   
      html += '<td>'+nomcli+' '+appa+' '+apmat+'<input type="hidden" name="nombres[]" value="'+nomcli+'" />';
      html += '<input type="hidden" name="apepaterno[]" value="'+appa+'" />';
      html += '<input type="hidden" name="apematerno[]" value="'+apmat+'" /></td>';
      html += '<td>'+dir+'<input type="hidden" name="direccion[]" value="'+dir+'" /></td>';
      html += '<td>'+tel+'<input type="hidden" name="telefono[]" value="'+tel+'" /></td>';
      html += '<td>'+prod+'<input type="hidden" name="idsubproductos_semi[]" value="'+idprod+'" />';
      html += '<input type="hidden" name="producto[]" value="'+prod+'" /></td>';
      html += '<td>'+cant+'<input type="hidden" name="cantidad[]" value="'+cant+'" /></td>';
      html += '<td>'+obs+'<input type="hidden" name="observacion[]" value="'+obs+'" /></td>';   
      html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
      html += '</tr>';  
    }else
      {
        var html = '';
        html += '<tr class="tr-detalle">';
        html += '<td>'+dni+'</td>';   
        html += '<td>'+nomcli+'<input type="hidden" name="idcliente[]" value="'+id+'" /></td>';
        html += '<td>'+dir+'<input type="hidden" name="direccion[]" value="'+dir+'" /></td>';
        html += '<td>'+tel+'<input type="hidden" name="telefono[]" value="'+tel+'" /></td>';
        html += '<td>'+prod+'<input type="hidden" name="idsubproductos_semi[]" value="'+idprod+'" />';
        html += '<input type="hidden" name="producto[]" value="'+prod+'" /></td>';
        html += '<td>'+cant+'<input type="hidden" name="cantidad[]" value="'+cant+'" /></td>';
        html += '<td>'+obs+'<input type="hidden" name="observacion[]" value="'+obs+'" /></td>';   
        html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
        html += '</tr>';  

      }
    
    $("#table-detalle").find('tbody').append(html);
}

function clearPer()
{
  $("#idcliente").val(''),
  $("#dnicli").val(''),
  $("#cantidad").val(''),
  $("#cliente").val(''),
  $("#direccion").val(''),
  $("#telefono").val(''),
  $("#producto").val(''),
  $("#observacion").val('')  
  $("#idubigeo").focus();
}

function nItems()
{
  var c = 0;
  $("#table-detalle tbody tr").each(function(idx,j){c += 1;});
  return c;
}

function save()
{
  bval = true;        
  bval = bval && $( "#descripcion" ).required();        

  var str = $("#frm_hojaruta").serialize();
  if ( bval ) 
  {
      $.post('index.php',str,function(res)
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