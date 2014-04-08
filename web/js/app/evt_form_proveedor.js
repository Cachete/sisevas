$(function() 
{   
    var idd='',
        idubigeo=$( "#idubigeo" ).val();    

    if(idubigeo!="")    
    {
        idd = idubigeo.substring(0,2)+'0000';        
        load_dep(idd);        
    }
    else
    {
      idd='210000';           
      load_dep(idd);
    }
    
    
    
    $( "#nombres" ).focus();
    $( "#Departamento" ).css({'width':'210px'});
    $( "#iddistrito" ).css({'width':'210px'});
    $( "#idprovincia" ).css({'width':'210px'});
    $("#estados").buttonset();
           
    $("#fechanaci").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});
    $("#Departamento").change(function(){
      idd=$(this).val();      
      alert("A");
      $.get('index.php','controller=Ubigeo&action=Provincia&idd='+idd,function(r)
      {        
          var html = '';
          $.each(r,function(i,j){
            html += '<option value="'+j.codigo+'">'+j.descripcion+'</option>'
            //alert(html);
          })
          $("#idprovincia").empty().append(html);
          IdPro=$("#idprovincia").val();
          loadDistrito(IdPro);
      });

    },'json');

});

function load_dep(idd)
{
    $("#Departamento").val(idd);
    $.get('index.php','controller=Ubigeo&action=Provincia&idd='+idd,function(r){
          var html = '';
          $.each(r,function(i,j){
            html += '<option value="'+j.codigo+'">'+j.descripcion+'</option>'            
          });
          $("#idprovincia").empty().append(html);
          //"210601"

          idubigeo=$( "#idubigeo" ).val();   
          if(idubigeo!="")
          {
              IdPro = idubigeo.substring(0,4)+'00'; 
              
          }
          else
          {
               if(idd=='210000')
                IdPro = '210600';
              else
                IdPro=$("#idprovincia").val();        
          }
            

          $("#idprovincia").val(IdPro);
          loadDistrito(IdPro);
      },'json');
}

$("#idprovincia").change(function(){
      IdPro=$(this).val();
      loadDistrito(IdPro);
});

function loadDistrito(IdPro)
{
      
      $.get('index.php','controller=Ubigeo&action=Distrito&idd1='+IdPro,function(r){
          var html = '';
          $.each(r,function(i,j){
            html += '<option value="'+j.codigo+'">'+j.descripcion+'</option>'            
          })
          $("#iddistrito").empty().append(html);

          idubigeo=$( "#idubigeo" ).val();   

          if(idubigeo!="")
          {              
            $("#iddistrito").val(idubigeo);
          }
          else
          {
            if(IdPro == '210600')
            $("#iddistrito").val("210601")
          }


          

      },'json');
   

}
function save()
{
  bval = true;        
  bval = bval && $( "#dni" ).required();
  /*bval = bval && $( "#nombres" ).required();        
  bval = bval && $( "#apellidos" ).required();*/
  var str = $("#frm_proveedor").serialize();
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