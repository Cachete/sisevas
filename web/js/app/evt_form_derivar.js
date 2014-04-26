$(function() 
{    
    $("#idtipo_documento, #idpersonal, #idcierre, #idtipo_problema, #idperremitente, #idareai" ).css({'width':'210px'});
 
    $("#table-per").on('click','#addDetail',function(){
        addDetail();
    });
    
    $("#table-detalle").on('click','.boton-delete',function(){var v = $(this).parent().parent().remove();})
});

function addDetail()
{
  
      bval = true;
      bval = bval && $("#idtipo_documento").required();
      bval = bval && $("#correlativo").required();
      bval = bval && $("#idperremitente").required();
      bval = bval && $("#idpersonal").required();

      if(!bval) return 0;
        idtpdoc= $("#idtipo_documento").val(),
        tpdoc = $("#idtipo_documento option:selected").html(),
        cor=$("#correlativo").val(),
        idtram=$("#idtramite").val(),            
        remit= $("#idperremitente option:selected").html(),
        iddest =$("#idpersonal").val(),            
        dest = $("#idpersonal option:selected").html(),
       
        addDetalle(idtpdoc,tpdoc,cor,idtram,remit,iddest,dest);
        
}

function addDetalle(idtpdoc,tpdoc,cor,idtram,remit,iddest,dest)
{
        
      var html = '';
      html += '<tr class="tr-detalle">';
      html += '<td>'+tpdoc+'</td>';   
      html += '<td>'+cor+'<input type="hidden" name="idtramitedoc[]" value="'+idtram+'" /></td>';
      html += '<td>'+remit+'</td>';
      html += '<td>'+dest+'<input type="hidden" name="idpersonal[]" value="'+iddest+'" /></td>';
      html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
      html += '</tr>';    
      $("#table-detalle").find('tbody').append(html);

}

