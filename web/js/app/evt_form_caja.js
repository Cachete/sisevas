$(function() 
{    
    $( "#descripcion" ).focus();
    $("#estados").buttonset();
    $("#table-per").on('click','#addDetail',function(){addDetail();});
    $("#table-detalle").on('click','.boton-delete',function(){var v = $(this).parent().parent().remove();})

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
});

function addDetail()
{
    //alert();
    bval = true;
    bval = bval && $("#dni").required();
    bval = bval && $("#personal").required();    
    if(!bval) return 0;
      id= $("#idpersonal").val(),
      dni= $("#dni").val(),
      nomper=$("#personal").val()
    addDetalle(dni,id,nomper);
    clearPer();    
}

function addDetalle(dni,id,nomper)
{
    
    var html = '';
    html += '<tr class="tr-detalle">';
    html += '<td>'+dni+'</td>';   
    html += '<td>'+nomper+'<input type="hidden" name="idpersonal[]" value="'+id+'" /></td>';    
    html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
    html += '</tr>';    
    $("#table-detalle").find('tbody').append(html);
}

function clearPer()
{
  $("#idpersonal").val("");
  $("#personal").val("");
  $("#dni").val("");  
  $("#dni").focus();
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

  var str = $("#frm_maderba").serialize();
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