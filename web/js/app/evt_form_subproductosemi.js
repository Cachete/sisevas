$(function() 
{   
    $("#tabs").tabs();
    $( "#descripcion" ).focus();
    $( "#idproductos_semi" ).css({'width':'210px'});
    $( "#idunidad_medida" ).css({'width':'210px'});
    $("#estados").buttonset();
    
    $("#table_precios").on('keyup','#flete',function(){PrecioC();});
    $("#table_precios").on('keyup','#impuesto',function(){CostoN();});
    $("#table_precios").on('keyup','#utilidadneta',function(){PrecioS();});
    $("#table_precios").on('keyup','#dcstoventa',function(){DsctV();});
});

function PrecioC()
{
    bval = true;
    bval = bval && $("#valorventa" ).required();  
    bval = bval && $("#dsctocompra" ).required();
    //bval = bval && $("#flete" ).required();

    if ( bval ) 
    {
        var vv=$("#valorventa").val()
        var dsct=$("#dsctocompra").val()
        var fle=$("#flete").val()
        
        vv= vv.replace(",","");
        dsct= dsct.replace(",","");
        PrecioCom=(parseFloat(vv)) - (parseFloat(dsct)) + (parseFloat(fle));
        
        $("#preciocompra").val(parseFloat(PrecioCom).toFixed(2));
        return PrecioCom.toFixed(2);
    }
}

function CostoN()
{
    bval = true;
    bval = bval && $("#preciocompra" ).required();  
    //bval = bval && $("#dsctocompra" ).required();
    //bval = bval && $("#flete" ).required();

    if ( bval ) 
    {
        var pc=$("#preciocompra").val()
        var imp=$("#impuesto").val()
        //var fle=$("#flete").val()
        
        pc= pc.replace(",","");
        imp= imp.replace(",","");
        CostoNe=(parseFloat(pc)) + (parseFloat(imp));
        
        $("#costoneto").val(parseFloat(CostoNe).toFixed(2));
        return CostoNe.toFixed(2);
    }
}

function PrecioS()
{
    bval = true;
    bval = bval && $("#costoneto" ).required();  
    
    if ( bval ) 
    {
        var cn=$("#costoneto").val()
        var uti=$("#utilidadneta").val()
        
        cn= cn.replace(",","");
        uti= uti.replace(",","");
        PrecioSu=(parseFloat(cn)) * (parseFloat(uti)) /100 ;
        
        $("#preciosasugerido").val(parseFloat(PrecioSu).toFixed(2));
        return PrecioSu.toFixed(2);
    }
}

function DsctV()
{
    bval = true;
    bval = bval && $("#preciosasugerido" ).required();  
    
    if ( bval ) 
    {
        var PS=$("#preciosasugerido").val()
        var dsV=$("#dcstoventa").val()
        
        PS= PS.replace(",","");
        dsV= dsV.replace(",","");
        PrecioVe=(parseFloat(PS)) - (parseFloat(dsV)) ;
        
        $("#preciosasugerido").val(parseFloat(PrecioVe).toFixed(2));
        return PrecioVe.toFixed(2);
    }
}

function save()
{
  bval = true;        
  
  bval = bval && $( "#idproductos_semi" ).required();
  bval = bval && $( "#descripcion" ).required();
  bval = bval && $( "#factor" ).required();
  bval = bval && $( "#idunidad_medida" ).required();
  bval = bval && $( "#precio" ).required();
  
  var str = $("#frm_SubProductoSemi").serialize();
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