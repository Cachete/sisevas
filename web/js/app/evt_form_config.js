$(function() {
    
    $( "#save" ).click(function(){
        bval = true;
        bval = bval && $( "#descripcion" ).required();        
        if ( bval ) {
            $("#frm").submit();
        }
        return false;
    });

    $( "#delete" ).click(function(){
          if(confirm("Confirmar Eliminacion de Registro"))
              {
                  $("#frm").submit();
              }
    });
});

function validarIMG(obj)
	{
		var arrExtensions=new Array("jpg","png","gif","jpeg"),
		objInput = obj,
		strFilePath = objInput.value,
		arrTmp = strFilePath.split("."),
		strExtension = arrTmp[arrTmp.length-1].toLowerCase(),
                flag = false;                
		for (var i=0; i<arrExtensions.length; i++) 
		{                        
			if (strExtension == arrExtensions[i]) 
			{
				flag = true;
			}
		}
                if(!flag)
                    {
                        alert("El Documento adjunto no es imagen...");                                
                        objInput.value="";
                        return false;
                    }
                else {
                    return true;
                }                
	}