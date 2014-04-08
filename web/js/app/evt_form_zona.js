$(function() 
{    
    $( "#descripcion" ).focus();
    $( "#idubigeo" ).css({'width':'210px'});
    $("#estados").buttonset();
});

function save()
{
  bval = true;        
  bval = bval && $( "#descripcion" ).required();        
  bval = bval && $( "#idubigeo" ).required();
  
  var str = $("#frm_zona").serialize();
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