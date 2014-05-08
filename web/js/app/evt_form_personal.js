$(function() 
{   
    $("#tabs").tabs({ collapsible: false }); 
    $( "#nombres" ).focus();
    $( "#sexo" ).css({'width':'210px'});
    $( "#idgradinstruccion, #idespecialidad,#idtipopersonal, #idperfil" ).css({'width':'210px'});
    $( "#idcargo, #idestado_civil,#idconsultorio, #iddocumento_identidad" ).css({'width':'210px'});
    $("#estados").buttonset();
    $("#fechanaci, #fechaing").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});
    /*$("#ruc").change(function(){
      esrucok($.(this).val());

    });*/
    $(".custom-input-file input:file").change(function(){
          $(this).parent().find(".archivo").html($(this).val());
      }).css('border-width',function(){
          if(navigator.appName == "Microsoft Internet Explorer")
              return 0;
      });
      
    $('#file_upload').uploadify({
            'formData'     : {
                    'timestamp' : '44',
                    'token'     : '33',
                    'controller': 'Personal',
                    'action':'loadfile'
            },
            'buttonText': 'Archivo',
            'swf'      : 'uploadify.swf',
            'uploader' : 'index.php?controller=Personal&action=loadfile',
            onUploadSuccess : function(file, data, response) {
                        if(response)
                        {
                            
                            r = data.split("###");
                            if(r[0]==1)
                            {
                                alert('El archivo fue subido correctamente');
                                $("#archivo").val(r[1]);
                                $("#VerImagennn").attr("href","files/"+r[1]);
                                $("#VerImagennn").css("display","inline");
                            }
                            else 
                            {
                                alert(r[1]+' '+data);
                            }                            
                        }
                        else 
                        {
                            alert("Ha ocurrido un error al intentar subir el archivo "+file.name);
                        }
                        
                    }
    });
    
    $('#file_uploadhc').uploadify({
            'formData'     : {
                    'timestamp' : '44',
                    'token'     : '33',
                    'controller': 'Personal',
                    'action':'loadfilehc'
            },
            'buttonText': 'Archivo',
            'swf'      : 'uploadify.swf',
            'uploader' : 'index.php?controller=Personal&action=loadfilehc',
            onUploadSuccess : function(file, data, response) {
                        if(response)
                        {
                            
                            r = data.split("###");
                            if(r[0]==1)
                            {
                                alert('El archivo fue subido correctamente');
                                $("#archivo_hc").val(r[1]);
                                $("#VerHc").attr("href","files_hc/"+r[1]);
                                $("#VerHc").css("display","inline");
                            }
                            else 
                            {
                                alert(r[1]+' '+data);
                            }                            
                        }
                        else 
                        {
                            alert("Ha ocurrido un error al intentar subir el archivo "+file.name);
                        }
                        
                    }
    });

});

function ValidaPDF(obj)
{
  var arrExtensions=new Array("pdf");
  var objInput = obj;
  var strFilePath = objInput.value;
  var arrTmp = strFilePath.split(".");
  var strExtension = arrTmp[arrTmp.length-1].toLowerCase();
  for (var i=0; i<arrExtensions.length; i++) 
  {
          if (strExtension != arrExtensions[i]) 
          {
                  alert("El Documento adjunto no es PDF...");       
                  objInput.value="";
                  return false;
          }
  }
  return true;
}

function save()
{
  bval = true;
  
  bval = bval && $( "#iddocumento_identidad" ).required();
  bval = bval && $( "#idestado_civil" ).required();        
  bval = bval && $( "#idtipopersonal" ).required();
  
  bval = bval && $( "#idespecialidad" ).required();
  bval = bval && $( "#idgradinstruccion" ).required();        
  bval = bval && $( "#idcargo" ).required();
  bval = bval && $( "#idconsultorio" ).required();        
  bval = bval && $( "#idperfil" ).required();
  
  bval = bval && $( "#dni" ).required();
  bval = bval && $( "#nombres" ).required();        
  bval = bval && $( "#apellidos" ).required();
  var str = $("#frm").serialize();
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