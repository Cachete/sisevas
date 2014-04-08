$(function() 
{
    $("#tabs").tabs();
    
    $("#idtipo_documento" ).focus();
    $("#idtipo_documento, #idpersonal, #idcierre, #idtipo_problema" ).css({'width':'210px'});
    
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
    /*
    
    $("#frm_melamina").on('click','#newLine',function(){
        $.get('index.php?controller=Linea&action=create',function(html)
        {           
           $("#box-frm-linea").empty().append(html);
           $("#box-frm-linea").dialog("open");
           $("#descripcion").focus();
           
        });
    })

    $("#box-frm-maderba").dialog({
      modal:true,
      autoOpen:false,
      width:'auto',
      height:'auto',
      resizing:true,
      title:'Formulario de Maderba',
      buttons: {'Registrar Maderba':function(){save_maderba();}}
    });

    $("#frm_melamina").on('click','#newMaderba',function(){
        $.get('index.php?controller=Maderba&action=create',function(html)
        {           
           $("#box-frm-maderba").empty().append(html);
           $("#box-frm-maderba").dialog("open");
           $("#descripcion").focus();
          
        });
    })
    */
    

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

function save()
{
  bval = true;        
  bval = bval && $( "#idtipo_problema" ).required();
  bval = bval && $( "#idareai" ).required();        
  bval = bval && $( "#idpersonal" ).required();
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