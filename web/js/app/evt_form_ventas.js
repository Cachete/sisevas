var afecto = false;
var producto = 
  { 
      nitem       : 0,
      idproducto  : new Array(), //Id tipo de producto                  
      producto    : new Array(),
      precio      : new Array(),
      stock      : new Array(),
      cantidad    : new Array(),
      estado      : new Array(),
      nuevo      : function(idproducto,producto,precio,stock,cantidad)
                    {                       
                      if(this.valid(idproducto))
                      {
                          this.idproducto[this.nitem] = idproducto;
                          this.producto[this.nitem] = producto;
                          this.precio[this.nitem] = precio;
                          this.stock[this.nitem] = stock;                                            
                          this.cantidad[this.nitem]  = cantidad;                      
                          this.estado[this.nitem] = true;
                          this.nitem += 1;                               
                      }
                      else
                      {
                          alert("Este produccto ya fue agregado al detalle.");
                      }

                    },
      valid       : function(idp){var flag = true;for(i=0;i<this.nitem;i++){if(this.estado[i]){if(this.idproducto[i]==idp) flag = false;}} return flag;},
      listar      : function()
                    {
                       var html = "",
                           cont = 0,          
                           idv = $("#idventa").val();
                       for(i=0;i<this.nitem;i++)
                       {                 

                          if(this.estado[i])
                          {

                            html += '<tr>';
                            html += '<td align="center">'+(cont+1)+'</td>';                            
                            html += '<td>'+this.producto[i]+'</td>';                            
                            p = parseFloat(this.precio[i]);
                            html += '<td align="center">'+p.toFixed(2)+'</td>';                              
                            c = parseFloat(this.cantidad[i]);
                            html += '<td align="center">'+c.toFixed(2)+'</td>';
                            t = this.precio[i]*this.cantidad[i];
                            html += '<td align="right">'+t.toFixed(2)+'</td>';
                            if(idv=="")
                            {
                                html += '<td align="center"><a id="item-'+i+'-edi" class="item-mp-edi box-boton boton-edit" href="#" title="Editar" ></a></td>';
                                html += '<td align="center"><a id="item-'+i+'-del" class="item-mp-del box-boton boton-anular" href="#" title="Quitar" ></a></td>';
                            }                            
                            html += "</tr>";
                            cont += 1;

                          }
                       }                                            
                       $("#table-detalle-venta").find('tbody').empty().append(html);
                       this.totales();
                    },
      loader      : function(i)
                    { 
                      $("#idsubproductos_semi").val(this.idproducto[i]);
                      $("#text_subproductosemi").val(this.producto[i]);
                      $("#precio").val(this.precio[i]);
                      $("#stock").val(this.stock[i]);
                      $("#cantidad").val(this.cantidad[i]).focus();
                      this.eliminar(i);
                    },
      eliminar    : function(i){this.estado[i] = false; this.listar();},
      limpiar     : function(){this.idproducto.clear();this.producto.clear();this.precio.clear();this.stock.clear();this.cantidad.clear();this.estado.clear();this.nitem = 0;},            
      getNumItems : function(){var n = 0; for(i=0;i<this.nitem;i++){if(this.estado[i]) n += 1;} return n;},
      totales     : function()
                    {
                       var st = 0,
                           igv = parseFloat($("#igv_val").val()),
                           tigv = 0,
                           t = 0,
                           dsct = parseFloat($("#monto_descuento").val()),
                           tdsct = $("#tipod").val(),
                           tdsct_text = $("#tipod option:selected").html();

                       if(tdsct==1) $("#label_dscto").html("<b>DSCTO "+tdsct_text+" </b>");
                        else $("#label_dscto").html("<b>DSCTO ("+dsct+") "+tdsct_text+" </b>");

                       for(i=0;i<this.nitem;i++)
                       {
                          if(this.estado[i])
                            st += this.cantidad[i]*this.precio[i];
                       }

                       st_bruto = st;

                       if(tdsct==1) dsct_val = dsct;
                        else dsct_val = st*dsct/100;

                       st = st - dsct_val;

                       if(afecto)
                       {
                          tigv = st*igv/100;
                          t = st+tigv;
                       }
                       else
                       {
                          tigv = 0;
                          t = st+tigv;
                       }                       
                       $("#table-detalle-venta tfoot tr:eq(0) td:eq(1)").empty().append('<b>'+(st_bruto.toFixed(2))+'</b>');
                       $("#table-detalle-venta tfoot tr:eq(1) td:eq(1)").empty().append('<b>'+dsct_val.toFixed(2)+'</b>');
                       $("#table-detalle-venta tfoot tr:eq(2) td:eq(1)").empty().append('<b>'+tigv.toFixed(2)+'</b>');
                       $("#table-detalle-venta tfoot tr:eq(3) td:eq(1)").empty().append('<b>'+t.toFixed(2)+'</b>');

                       //Tipo de venta
                       var tv = $("#idtipopago").val();
                       $("#tventatext").empty().append("S/. "+t.toFixed(2));
                       pagos.limpiar();
                       pagos.total = t;
                       pagos.listar();
                       if(tv==1)
                        {
                           setMontoPago(t);
                        } 
                      return t;
                    }
  };

var pagos = 
{
   nitem        : 0,
   total        : 0,
   idformapago  : new Array(),
   formapago    : new Array(),
   monto        : new Array(),
   nrotarjeta   : new Array(),
   nrovoucher   : new Array(),
   nrocheque    : new Array(),
   banco        : new Array(),
   fechav       : new Array(),
   estado       : new Array(),
   nuevo      : function(idformapago,formapago,monto,nrotarjeta,nrovoucher,nrocheque,banco,fechav)
                    { 
                      total = this.totales();
                      if(total<this.total)
                      {
                          this.idformapago[this.nitem] = parseInt(idformapago);
                          this.formapago[this.nitem] = formapago;
                          this.monto[this.nitem] = parseFloat(monto);
                          this.nrotarjeta[this.nitem] = nrotarjeta;
                          this.nrovoucher[this.nitem] = nrovoucher;
                          this.nrocheque[this.nitem]  = nrocheque;
                          this.banco[this.nitem]  = banco;
                          this.fechav[this.nitem]  = fechav;
                          this.estado[this.nitem] = true;
                          this.nitem += 1;
                      }
                      else
                      {
                        alert("Este pago ya no se puede agregar ya que se ha completado el monto total de la venta.");
                      }
                      
                    },
    listar      : function()
                  {
                   var html = "";
                   var cont = 0;
                   for(i=0;i<this.nitem;i++)
                   {                          
                      if(this.estado[i])
                      {
                        html += '<tr>';                        
                        html += '<td align="center">'+this.formapago[i]+'</td>';
                        descripcion = "";
                        if(this.idformapago==4||this.idformapago==5)
                            descripcion = 'Tarjeta Nro: '+this.nrotarjeta[i]+', Voucher Nro: '+this.nrovoucher[i];                        
                        if(this.idformapago==6)
                            descripcion = 'Cheque Nro: '+this.nrocheque[i]+', Banco: '+this.banco[i]+',  Fecha de Venc.: '+this.fechav[i];
                        html += '<td>'+descripcion+'</td>';
                        p = parseFloat(this.monto[i]);
                        html += '<td align="right">'+p.toFixed(2)+'</td>';
                        html += '<td><a id="item-'+i+'" class="item-fp box-boton boton-anular" href="#" title="Quitar" ></a></td>';
                        html += "</tr>";
                        cont += 1;
                      }
                   }
                  $("#table-detalle-pagos").find('tbody').empty().append(html);
                   this.totales();
                  },
    eliminar    : function(i)
                  {
                    this.estado[i] = false;                        
                  },
    limpiar     : function()
                    {   
                        this.idformapago.clear();this.formapago.clear();this.nrotarjeta.clear();
                        this.nrovoucher.clear();this.nrocheque.clear();this.banco.clear();this.monto.clear();
                        this.fechav.clear();this.estado.clear();this.nitem = 0;this.total = 0;
                        
                    },
    getNumItems : function(){var n = 0; for(i=0;i<this.nitem;i++){if(this.estado[i]) n += 1;} return n;},
    totales     : function()
                  {
                      var st = 0,
                          pendiente=0;
                      for(i=0;i<this.nitem;i++)
                      {
                        if(this.estado[i])
                          st += this.monto[i];
                      }
                      pendiente = this.total - st;
                      $("#table-detalle-pagos tfoot tr:eq(0) td:eq(1)").empty().append('<b>'+st.toFixed(2)+'</b>');
                      $("#table-detalle-pagos tfoot tr:eq(1) td:eq(1)").empty().append('<b>'+pendiente.toFixed(2)+'</b>');
                      return st;
                    }
}

$(function() 
{   
    $("input[type=text]").focus(function(){this.select();});
    $("#fechaemision,#fechai,#fechav").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});
    $("#idalmacen,#idtipodocumento" ).css({'width':'150px'});    
    $("#idtipodocumento").change(function(){load_correlativo($(this).val());});
    $("#idformapago").change(function(){
        $("#idformapago2").val($(this).val());
        change_fp();
      });
    $("#idformapago2").change(function(){change_fp();});
    $("#idtipopago").change(function(){
      change_tp($(this).val());
    });
    $("#add-fp").click(function(){
        addFormaPago();
    })
    $( "#tabs" ).tabs({   
                          activate: function( event, ui ) 
                          { 
                                var i = $(this).tabs( "option", "active" );
                                validar_tabs(i);
                          }
                         });

    $( "#tabs" ).tabs( "option", "disabled", [ 1 ] );

    $("#btn-add-ma").click(function(){addnewproducto();});
    $("#table-detalle-venta").on('click','.item-mp-del',function(){
      var i = $(this).attr("id");
      i = i.split("-");
      producto.eliminar(i[1]);      
    });
    $("#table-detalle-venta").on('click','.item-mp-edi',function(){
      var i = $(this).attr("id");
      i = i.split("-");
      producto.loader(i[1]);
    });
    $("#table-detalle-pagos").on('click','.item-fp',function(){
      var i = $(this).attr("id");
      i = i.split("-");
      pagos.eliminar(i[1]);
      pagos.listar();
    });
    $("#monto_descuento, #tipod").change(function(){
       producto.totales();
    });
    verifAfecto();    
    $("#aigv").click(function(){
       verifAfecto();
    });
    $("#gen-cronograma").click(function(){
      genCronograma();
    });
    $("#ruc").autocomplete({
        minLength: 0,
        source: 'index.php?controller=clientes&action=get&tipo=1',
        focus: function( event, ui ) 
        {
            $( "#dni" ).val( ui.item.dni );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idcliente").val(ui.item.idcliente);
            $( "#ruc" ).val( ui.item.dni );
            $( "#cliente" ).val( ui.item.nomcliente );
            $("#direccion").val(ui.item.direccion);
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.dni +" - " + item.nomcliente + "</a>" )
            .appendTo(ul);
      };
    $("#cliente").autocomplete({
        minLength: 0,
        source: 'index.php?controller=clientes&action=get&tipo=2',
        focus: function( event, ui ) 
        {
            $( "#cliente" ).val( ui.item.nomcliente );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idcliente").val(ui.item.idcliente);
            $( "#ruc" ).val( ui.item.dni );
            $( "#cliente" ).val( ui.item.nomcliente );
            $("#direccion").val(ui.item.direccion);
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {        
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+item.nomcliente + "</a>" )
            .appendTo(ul);
      };    


      //Metodos solo para la opcion ver
      loadDetalles();

});
function load_correlativo(idtp)
{
  if(idtp!="")
  {    
    $.get('index.php','controller=tipodocumento&action=Correlativo&idtp='+idtp,function(r){
          
          $("#serie").val(r.serie);
          $("#numero").val(r.numero);

      },'json');
  }else
    {
      $("#serie").val('');
      $("#numero").val('');
    }
}
function valid_tab1()
{
  bval = true;        
  bval = bval && $( "#idtipopago" ).required();        
  bval = bval && $( "#idtipodocumento" ).required();
  bval = bval && $( "#fechaemision" ).required();
  bval = bval && $( "#ruc" ).required();
  bval = bval && $( "#cliente" ).required();

  var n = producto.getNumItems();

  if(n==0) bval = false;

  return bval;
}

function valid_tab2()
{
  var c = 0;
  $("#table-detalle-cuotas tbody tr").each(function(idx,j){c += 1;});
  if(c==0)
    return false;
  else
    return true;
}

function valid_tab3()
{
  var c = 0;
  $("#table-detalle-pagos tbody tr").each(function(idx,j){c += 1;});
  if(c==0)
    return false;
  else
    return true;
}

function save()
{

  //Validamos tab 01 

  var tab = valid_tab1();
  if(tab)
  {
     idfp = $("#idtipopago").val();
     if(idfp==2)
     {
         var tab = valid_tab2();
         if(!tab)         
            $( "#tabs" ).tabs( "option", "active", 1 );                     
     }
     if(tab)
     {
        var tab = valid_tab3(); 
        if(!tab)  
            $( "#tabs" ).tabs( "option", "active", 2 );
     }
  }
  else
  {
    $( "#tabs" ).tabs( "option", "active", 0 );  
  }

  if(tab) 
  {
      
      if ( bval )
      {
          productos = json_encode(producto);
          pagoss = json_encode(pagos);   
          var str = $("#frm_ventas").serialize();       
           $.post('index.php',str+'&producto='+productos+'&pagos='+pagoss,function(res)
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
  else
  {
     alert("Complete los datos.");
  }
  
}


function validar_tabs(i)
{
  var 
      bval = true,
      idfp = $("#idtipopago").val();
  bval = bval && $("#idtipopago").required();              
  if(!bval&&i!=0)
  {
      alert("Para pasar a la pestaña de Registro de Pagos debe completar los datos en la pestaña Registro de Ventas")
      $( "#tabs" ).tabs( "option", "active", 0 );
  }
  else
  {
    var ni = producto.getNumItems();
    if(ni==0&&i!=0)
    {
        alert("Debe ingresar los producto a vender en el detalle.");
        $( "#tabs" ).tabs( "option", "active", 0 );                
        bval = false;
    }
  }

  switch(i)
  {
    
    case 0: break;
    case 1: break;            
    case 2: 
            if(bval==true&&idfp==2)
            {
                bval = bval && $("#monto_inicial").required();
                // var c = 0;
                // $("#table-detalle-cuotas tbody tr").each(function(idx,j){c += 1;});
                if(bval)
                {
                  var c = 0;
                  $("#table-detalle-cuotas tbody tr").each(function(idx,j){c += 1;});
                  if(c==0)
                  {
                      alert("Debe generar el cronograma de cuotas para realizar un pago (Cuota Inicial).");
                      $( "#tabs" ).tabs( "option", "active", 1 );
                  }
                }
                //bval = bval && $("#periodo").required();
            }
            break;
    default: break;
  }  
  return bval;
}

function change_fp()
{
   var i = $("#idformapago2").val(); 
   if(i!=1)  
   {
      if(i==4||i==5)
      {
         $("#box-pay-cheque").css("display","none");
         $("#box-pay-tarjeta").css("display","inline");
         $("#nrotarjeta,#nrovoucher,#nrocheque,#banco,#fechav").val("");
      }
      else
      {
          if(i==6)
          {
             $("#box-pay-cheque").css("display","inline");
             $("#box-pay-tarjeta").css("display","none");
             $("#nrotarjeta,#nrovoucher,#nrocheque,#banco,#fechav").val("");
          }
          else
          {
             $("#box-pay-tarjeta,#box-pay-cheque").css("display","none");
             $("#nrotarjeta,#nrovoucher,#nrocheque,#banco,#fechav").val("");
          }
      }
      
   }
   else
   {
      $("#box-pay-tarjeta,#box-pay-cheque").css("display","none");
      $("#nrotarjeta,#nrovoucher,#nrocheque,#banco,#fechav").val("");
   }
}
function change_tp(i)
{
  if(i!="")
  {
    if(i==1)
    { 
      $("#box-pay-doc").css("display","none");
      $( "#tabs" ).tabs( "option", "disabled", [ 1 ] );  
      $("#text_totale_venta").empty().append("Total Venta: ");
      clear_cronograma();
      producto.totales();
    }
    if(i==2)
    {
      $("#box-pay-doc").css("display","inline");      
      $( "#tabs" ).tabs( "enable", 1 );
      $("#text_totale_venta").empty().append("Cuota Inicial: ");
    } 
  }
  else
  {    
      $( "#tabs" ).tabs( "option", "disabled", [ 1 ] ); 
      clear_cronograma();
      producto.totales(); 
  }  
  
}

function loadstock()
{
   var a = $("#idalmacen").val(),
        i = $("#idsubproductos_semi").val();        
    if(a!=""&&i!="")
    {
       $("#load-stock").css("display","inline");
       $.get('index.php','controller=subproductosemi&action=getstock&i='+i+'&a='+a,function(data){
            $("#load-stock").css("display","none");
            $("#stock").val(data.stk);
            $("#precio").val(data.price);
            $("#cantidad").focus();
       },'json');
    }
}
function enter(evt)
{
    var keyPressed = (evt.which) ? evt.which : event.keyCode
    if (keyPressed==13)
    {
        addnewproducto();
    }
}
function addnewproducto()
{
  bval = true;
  bval = bval && $("#text_subproductosemi").required();  
  bval = bval && $("#precio").required();
  bval = bval && $("#stock").required();
  bval = bval && $("#cantidad").required();
  
  if(bval) 
  {
      var   p1   = $("#idsubproductos_semi").val(),
            p2   = $("#idsubproductos_semi option:selected").html(),
            p3  = $("#precio").val(),
            p4  = $("#stock").val(),          
            p5   = $("#cantidad").val();
          
        var a = $("#idalmacen").val();            
          if(a!=""&&p1!="")
          {
             $("#load-stock").css("display","inline");
             $.get('index.php','controller=subproductosemi&action=getstock&i='+p1+'&a='+a,function(data){
                  $("#load-stock").css("display","none");
                  stk = parseFloat(data.stk);  
                  if(p5>stk)
                  {
                     alert("Existencias insuficientes! (Stock: "+stk+"), porfavor reconsidere la cantidad a agregar.");
                     $("#cantidad").focus();                     
                  }
                  else
                  {
                    if(p5>0)
                    {
                        producto.nuevo(p1,p2,p3,p4,p5);
                        producto.listar();          
                        clea_frm_producto();
                    }                    
                    else 
                    {
                       alert("La cantidad debe ser mayor que cero (0).");
                       $("#cantidad").focus();
                    }
                  }
             },'json')
             
         }  
        
  }
}
function verifAfecto()
{
  if($('#aigv').is(':checked')) afecto = true;
   else afecto = false;       
  producto.totales();
}

function genCronograma()
{
   var bval = true;
   bval = bval && $("#monto_inicial").required();
   bval = bval && $("#interes").required();
   var f = new Date();
   fechaa = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
   if(bval)
   {
        var ncuotas = $("#nrocuota").val(),
           inicial = parseFloat($("#monto_inicial").val()),
           interes = parseFloat($("#interes").val()),
           tinteres = $("#tipoi").val(),
           periodo = $("#periodo").val(),
           fechai = $("#fechai").val();
           tventa = producto.totales();

           //Inicial
           html = '<tr><td align="center">Inicial</td><td align="center">'+fechaa+'</td>';
           html += '<td align="right"><input type="hidden" name="inicial" id="inicial" value="'+inicial+'" />'+inicial.toFixed(2)+'</td>';
           html += '<td align="right">0.00</td>';
           html += '<td align="right">'+inicial.toFixed(2)+'</td><td>&nbsp;</td>';
           html += '</tr>';

           montoxcuota = (tventa-inicial)/ncuotas;
           if(tinteres==1) interxcuota = interes;
            else interxcuota = interes*montoxcuota/100;
           
           fechas = " "+fechai;
           fechac = fechai;
           html += trCronograma(1,fechai,montoxcuota,0);

           for(ci=1;ci<ncuotas;ci++)
           {              
              var d = 0;
              if(periodo==1) d = 1;
              if(periodo==2) d = 7;
              if(periodo==3) 
              {
                fechac = fechac.toString();
                fecha = fechac.split("/");
                anio = fecha[2];
                mes  = fecha[1];
                d = finMes(mes,anio);
              }          
              prox_fecha =  UpdateFecha(fechac,d)
              if(ci==1) html += trCronograma(ci+1,prox_fecha,montoxcuota,0);
                else html += trCronograma(ci+1,prox_fecha,montoxcuota,interxcuota);
              fechac = prox_fecha;
           }
   }
   $("#table-detalle-cuotas").find('tbody').empty().append(html);
   setMontoPago($("#inicial").val());
}


function trCronograma(i,fecha,monto,interes)
{
  var html = ''  ;
  html = '<tr>';
    html += '<td align="center">'+i+'</td>';
    html += '<td align="center"><input type="text" name="fechacuota[]" value="'+fecha+'" class="ui-widget-content ui-corner-all text text-date" /></td>';
    //html += '<td align="right"><input type="text" name="montocouta[]" value="'+monto+'" class="ui-widget-content ui-corner-all text text-num" /></td>';
    //html += '<td align="right"><input type="text" name="interescouta[]" value="'+interes+'" class="ui-widget-content ui-corner-all text text-num" /></td>';
    monto = parseFloat(monto);
    interes = parseFloat(interes);
    html += '<td align="right"><label>'+monto.toFixed(2)+'</labe></td>';
    html += '<td align="right"><label>'+interes.toFixed(2)+'</labe></td>';
    t = parseFloat(monto)+parseFloat(interes);
    t = t.toFixed(2);
    html += '<td align="right"><input type="text" name="totalcouta[]" value="'+t+'" class="ui-widget-content ui-corner-all text text-num" /></td>';    
    html += '<td></td>';
  html += '</tr>';
  return html;
}

function setMontoPago(mi)
{
  var mi = parseFloat(mi);
  mi = mi.toFixed(2);  
  $("#total_pago").html("S/. "+mi);
  $("#monto_efectivo").val(mi);
  pagos.total = mi;
}

function clear_cronograma()
{
    clear_form_cronograma();
    $("#table-detalle-cuotas tbody").empty();
}
function clear_form_cronograma()
{
  $("#nrocuota").val(1);
  $("#monto_inicial").val('0.00');
  $("#periodo").val("");
}
function clea_frm_producto()
{
   $("#producto,#idsubproductos_semi, #text_subproductosemi").val("");
   $("#stock,#precio,#cantidad").val("0.00");
   $("#producto").focus();
}
function validarCantidad(v)
{

}

function addFormaPago()
{
  var monto = $("#monto_efectivo").val(),
      idfp  = $("#idformapago2").val(),
      fp    = $("#idformapago2 option:selected").html(),
      t     = $("#nrotarjeta").val(),
      v     = $("#nrovoucher").val(),
      c     = $("#nrocheque").val(),
      b     = $("#banco").val(),
      f     = $("#fechav").val(),
      bval = true;
  if(idfp==4||idfp==5)
  {
      bval = bval && $("#nrotarjeta").required();
      bval = bval && $("#nrovoucher").required();
  }
  if(idfp==6)
  {
      bval = bval && $("#nrocheque").required();
      bval = bval && $("#banco").required();
  }
  bval = bval && $("#monto_efectivo").required();
  if(bval)
  {
      monto = parseFloat(monto);
      if(monto>0)
      {
          //alert(idfp+' '+fp+' '+monto+' '+t+' '+v+' '+c+' '+b+' '+f);
          pagos.nuevo(idfp,fp,monto,t,v,c,b,f);
          pagos.listar();
      }
      else
      {
        alert("El monto debe ser mayor que cero (0)");
        return 0;
      }
  }
}

function clear_frm_pagos()
{
  $("#monto_efectivo").val("0.00");
  $("#nrotarjeta").val("");
  $("#nrovoucher").val("");
  $("#nrocheque").val("");
  $("#banco").val("");
  $("#fechav").val("");
}


function loadDetalles()
{
   var idm = $("#idventa").val();
   if(idm!="")
   {
      $.get('index.php?controller=ventas&action=getDetails&idm='+idm,function(r){
        $.each(r,function(k,p)
        {          
          producto.nuevo(p.idproducto,p.descripcion,p.precio,0,p.cantidad);
        });
        producto.listar();
      },'json');
   }
  
}

  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( {"title":"","id":"text_subproductosemi"})
          .css("width","200px")
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )          
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {            
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
            //aki
            loadstock();
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Mostrar todos los producctos" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " no encontró ningún elemento" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.data( "ui-autocomplete" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#idsubproductos_semi" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#combobox" ).toggle();
    });
  });
