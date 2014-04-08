$(function() 
{	
    $("#list").on('click','.pagar',function(){
        var i = $(this).attr("id");
        i = i.split('-');
        id = i[1];        
        var ventana=popup('index.php?controller=ventas&action=pagarcuota&id='+id, 950, 460); 
        ventana.focus();        
    });

    $("#list").on('click','.anular',function()
    {
      var i = $(this).attr("id");
      i = i.split('-');
      i = i[1];
      if(confirm('Realmente deseas anular esta venta con codigo '+i+'?'))
      {     
        $.post('index.php','controller=ventas&action=anular&i='+i,function(r)
        {
          if(r[0]==1) gridReload();
            else alert('Ha ocurrido un error, vuelve a intentarlo.');
        },'json');
      }
    });
    
    $("#idformapago2").change(function(){change_fp();});

    $("#fechav").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});

    $("#add-fp").click(function(){
        addFormaPago();
    });
    $("#btn-gen-d").click(function(){
        var bval = true;
        bval = bval && $("#idtipodocumento").required();
        if(bval)
        {
           var idd = $("#idtipodocumento").val(),
               id = $("#idmov").val();
            $.post('index.php','controller=ventas&action=genDoc&idd='+idd+'&id='+id,function(res)
              {
                 if(res[0]==1)
                  {
                      alert("Se ha generado el comprobante correctamente");
                      location.reload();
                  }
                  else
                  {
                    alert(res[1]);
                  }            
            },'json')

        }
    });
    $("#table-detalle-pagos").on('click','.item-fp',function(){
      var i = $(this).attr("id");
      i = i.split("-");
      pagos.eliminar(i[1]);
      pagos.listar();
    });
    $("#btn-pago").click(function(){save();})
    
});

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
                      /*if(total<this.total)
                      {*/
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
                      /*}
                      else
                      {
                        alert("Este pago ya no se puede agregar ya que se ha completado el monto total de la venta.");
                      }*/
                      
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
                      //$("#table-detalle-pagos tfoot tr:eq(1) td:eq(1)").empty().append('<b>'+pendiente.toFixed(2)+'</b>');
                      return st;
                    }
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

              clear_frm_pagos();
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

   var tab = valid_tab3(); 
  if(!tab)  
    {
      alert("Ingrese los pagos");
      return false;
    }
    else
    {

      pagoss = json_encode(pagos); 
      id = $("#idmov").val();
      $.post('index.php','controller=ventas&action=pay_cuotas&pagos='+pagoss+'&id='+id,function(res)
           {
              if(res[0]==1)
              {
                  alert("Su pago se realizo correctamente");
                  location.reload();
              }
              else
              {
                alert(res[1]);
              }            
          },'json');
    }

}
