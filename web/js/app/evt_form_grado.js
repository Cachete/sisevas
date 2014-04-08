$(function() 
{    
    $( "#descripcion" ).focus();
    //$( "#idperfil" ).css({'width':'210px'});
    $("#estados").buttonset();
});

function save()
{
  bval = true;        
  bval = bval && $( "#descripcion" ).required();        
  //bval = bval && $( "#orden" ).required();
  var str = $("#frm_grado").serialize();
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