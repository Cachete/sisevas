$(function() 
{    
    $( "#descripcion" ).focus();
    $( "#idtipo_documento, #idsede" ).css({'width':'210px'});   
    $("#estados").buttonset();
});

function save()
{
  bval = true;        
  bval = bval && $( "#descripcion" ).required();        
  bval = bval && $( "#idtipo_documento" ).required();
  var str = $("#frm_tipodoc").serialize();
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