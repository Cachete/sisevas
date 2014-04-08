$(function() 
{    
    $( "#descripcion" ).focus();
    $("#estados").buttonset();
    $("#table-per").on('click','#addDetail',function(){addDetail();});
    $("#table-detalle").on('click','.boton-delete',function(){var v = $(this).parent().parent().remove();})
});
function addDetail()
{
    //alert();
    bval = true;
    bval = bval && $("#nromes").required();
    bval = bval && $("#factor").required();    
    if(!bval) return 0;
      nromes= $("#nromes").val(),
      factor= $("#factor").val(),
    addDetalle(nromes,factor);
    clearFac();    
}

function addDetalle(nromes,factor)
{
    
    var html = '';
    html += '<tr class="tr-detalle">';
    html += '<td>'+nromes+'<input type="hidden" name="meses[]" value="'+nromes+'" /></td>';   
    html += '<td>'+factor+'<input type="hidden" name="factor[]" value="'+factor+'" /></td>';    
    html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
    html += '</tr>';    
    $("#table-detalle").find('tbody').append(html);
}

function clearFac()
{
  $("#nromes").val("");
  $("#factor").val("");  
  $("#nromes").focus();
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