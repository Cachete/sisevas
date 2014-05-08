$(function() 
{
    $("#tabs").tabs();
    
    $("#idtipo_documento" ).focus();
    $("#idtipo_documento, #idpersonal, #idcierre, #idtipo_problema, #idperremitente, #idareai" ).css({'width':'210px'});
    
    //Generar correlativo y Cargar Formatos
    $(" #idtipo_documento ").change(function(){
      load_formato($(this).val());
      load_correlativo($(this).val());
    });

    $("#idtipo_problema").change(function(){
        load_problema($(this).val()); 
        $("#idtipo_problema").focus();
    });

    $("#estados").buttonset();

    $("#table-per").on('click','#addDetail',function(){
        addDetail();
    });
    
    $("#table-detalle").on('click','.boton-delete',function(){var v = $(this).parent().parent().remove();})

});

function load_formato(idtipodoc)
{
  if(idtipodoc== 1)
  {    
    $("#load_formato").empty().append('Cargando...');
    $.get('index.php','controller=tipodocumento&action=formatos',function(html){    
       
      $("#load_formato").empty().append(html);
    }); //'json');
  }

  if(idtipodoc== 2)
  {    
    $("#load_formato").empty().append('Cargando...');
    $.get('index.php','controller=tipodocumento&action=formatos1',function(html){    
       
      $("#load_formato").empty().append(html);
    }); //'json');
  }

  if(idtipodoc== 3)
  {    
    $("#load_formato").empty().append('Cargando...');
    $.get('index.php','controller=tipodocumento&action=formatos2',function(html){    
       
      $("#load_formato").empty().append(html);
    }); //'json');
  }

  if(idtipodoc== 4)
  {    
    $("#load_formato").empty().append('Cargando...');
    $.get('index.php','controller=tipodocumento&action=formatos3&id='+idtipodoc,function(html){    
       
      $("#load_formato").empty().append(html);
    }); //'json');
  }

  if(idtipodoc== 5)
  {    
    $("#load_formato").empty().append('Cargando...');
    $.get('index.php','controller=tipodocumento&action=formatos4&id='+idtipodoc,function(html){    
       
      $("#load_formato").empty().append(html);
    }); //'json');
  }
}

function load_correlativo(idtp)
{
    $.get('index.php','controller=tipodocumento&action=Correlativo&idtp='+idtp,function(r){
          
        //$("#serie").val(r.serie);
        //$("#numero").val(r.numero);
        $("#correlativo").val(r.correlativo);
    },'json');
}

function load_problema(idl)
{ 
  if(idl!="")
  {    
    $("#idareai").empty().append('<option value="">Cargando...</option>');
    $.get('index.php','controller=tipoproblema&action=getList&idl='+idl,function(r){    
      html = '<option value="">Seleccione...</option>';
      $.each(r,function(i,j){
        html += '<option value="'+j.idareai+'">'+j.descripcion+'</option>';
      });      
      $("#idareai").empty().append(html);
    },'json');
  }
}


function addDetail()
{
  
      bval = true;
      bval = bval && $("#idtipo_documento").required();
      bval = bval && $("#correlativo").required();
      bval = bval && $("#idpersonal").required();

      if(!bval) return 0;        
        iddest =$("#idpersonal").val(),            
        dest = $("#idpersonal option:selected").html(),
       
        addDetalle(iddest,dest);
        
}

function addDetalle(iddest,dest)
{
        
      var html = '';
      html += '<tr class="tr-detalle">';
      html += '<td>'+dest+'<input type="hidden" name="idpersonaldet[]" value="'+iddest+'" /></td>';
      html += '<td align="center"><a class="box-boton boton-delete" href="#" title="Quitar" ></a></td>';
      html += '</tr>';    
      $("#table-detalle").find('tbody').append(html);

}

function save()
{
  bval = true;        
  bval = bval && $( "#idtipo_problema" ).required();
  bval = bval && $( "#idareai" ).required();        
  //bval = bval && $( "#idpersonal" ).required();
  bval = bval && $( "#idcierre" ).required();

  var str = $("#frm_envio").serialize();
  if ( bval ) 
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
  return false;
}