$(function() 
{   
    $("#tabs").tabs();
    $("#mes, #meses, #pagmeses").css({'width':'180px'});

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

    $("#meses").change(function(){
        load_trabajo($(this).val()); 
    });

    $("#pagmeses").change(function(){
        load_pagos($(this).val()); 
    });

});


function load_trabajo(mes)
{
  
  idpersonal= $("#idpersonal").val();
  anio= $("#anios").val();

  $.get('index.php','controller=personal&action=VerTrabajo&idpersonal='+idpersonal+'&mes='+mes+'&anio='+anio,function(r){

    $("#divLoadTrabajo").empty().append(r);

  });
}

function load_pagos(mes)
{
  
  idpersonal= $("#idpersonal").val();
  anio= $("#aniospag").val();
  
  $.get('index.php','controller=personal&action=VerPagos&idpersonal='+idpersonal+'&mes='+mes+'&anio='+anio,function(r){

    $("#divLoadPagos").empty().append(r);

  });
}

function save()
{
  bval = true;        
  bval = bval && $( "#dni" ).required();
  bval = bval && $( "#montopag" ).required();
  bval = bval && $("#mes").required();
  bval = bval && $("#anio").required();
  
  var str = $("#frm_pagoxpersonal").serialize();
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