$(function() 
{   
    $("#fechad, #fechah").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});
    $( "#idpersonal, #idalmacen" ).css({'width':'180px'});    
    $( "#descripcion" ).focus();    
    $("#estados").buttonset();    
    $("#proform").on('click','#reporte_prof',function(){ReporteProf(); });    
    $("#hojar").on('click','#reporte_hr',function(){ReporteHojaR(); });
    $("#ingreso").on('click','#reporte_ing',function(){ReporteIngreso(); });
    $("#produccion").on('click','#reporte_pro',function(){ReporteProd(); });
    $("#stock").on('click','#reporte_stock',function(){ReporteStock(); });
    $("#ventas").on('click','#reporte_vent',function(){ReporteVent(); });

    //PROFORMAS - MOSTRAR DETALLE QUE SALE EN EL REPORTE
    $("#proform").on('click','#print_rpt',function(){
        
        fechai=$("#fechad").val();
        fechaf=$("#fechah").val();
        if($("#idpersonal").val()=='')
        {
          idper=0
        }
        else
        {
            idper=$("#idpersonal").val();
        }
        var ventana=window.open('index.php?controller=proformas&action=print_rpt&fechad='+idper+'&fechai='+fechai+'&fechaf='+fechaf, 'Imprimir Proforma, width=600, height=600, resizable=no, scrollbars=yes, status=yes,location=yes'); 
        ventana.focus();        
    });
});

//PROFORMAS
function ReporteProf()
{
    fechai=$("#fechad").val();
    fechaf=$("#fechah").val();
    if($("#idpersonal").val()=='')
    {
      idper=0
    }
    else
    {
      idper=$("#idpersonal").val();
    }
    
    $.get('index.php','controller=proformas&action=load_proformas&idper='+idper+'&fechai='+fechai+'&fechaf='+fechaf,function(r){      
      $("#load_resultado").empty().append(r);
    });
}

//HOOJA DE RUTAS
function ReporteHojaR()
{
  fechai=$("#fechad").val();
  fechaf=$("#fechah").val();  
    //alert('');
    if($("#idpersonal").val()=='')
        {
          idper=0
        }else
          {
            idper=$("#idpersonal").val();
          }
    //alert(idper);
    $.get('index.php','controller=hojaruta&action=load_hojarutas&idper='+idper+'&fechai='+fechai+'&fechaf='+fechaf,function(r){
      
      $("#load_resultado").empty().append(r);

    });
}

//INGRESOS DE MATERIALES
function ReporteIngreso()
{
  fechai=$("#fechad").val();
  fechaf=$("#fechah").val();  
  
    $.get('index.php','controller=ingresom&action=load_ingresos&fechai='+fechai+'&fechaf='+fechaf,function(r){
      
      $("#load_resultado").empty().append(r);

    });
}

//PRODUCCION
function ReporteProd()
{
  fechai=$("#fechad").val();
  fechaf=$("#fechah").val();  
  
    $.get('index.php','controller=produccion&action=load_produccion&fechai='+fechai+'&fechaf='+fechaf,function(r){
      
      $("#load_resultado").empty().append(r);

    });
}

//STOCK DE PRODUCTOS
function ReporteStock()
{

    if($("#idalmacen").val()=='')
        {
          idalm=0;
        }else
          {
            idalm=$("#idalmacen").val();
          }
    //alert(idalm);
    $.get('index.php','controller=subproductosemi&action=load_stockprod&idalm='+idalm,function(r){
      
      $("#load_resultado").empty().append(r);

    });
}

//
function ReporteVent()
{
  fechai=$("#fechad").val();
  fechaf=$("#fechah").val();
    if($("#idpersonal").val()=='')
      {
        idper=0
      }else
        {
          idper=$("#idpersonal").val();
        }
    
    $.get('index.php','controller=ventas&action=load_ventas&idper='+idper+'&fechai='+fechai+'&fechaf='+fechaf,function(r){
      
      $("#load_resultado").empty().append(r);

    });
}