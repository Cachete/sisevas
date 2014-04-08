var producto = 
  { 
      nitem       : 0,
      idproducto  : new Array(), //Id tipo de producto                  
      producto    : new Array(),
      precio      : new Array(),
      stock       : new Array(),
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
                           idv = $("#idproduccion").val();
                       for(i=0;i<this.nitem;i++)
                       {                 

                          if(this.estado[i])
                          {

                            html += '<tr>';
                            html += '<td align="center">'+(cont+1)+'</td>';                            
                            html += '<td>'+this.producto[i]+'</td>';                            
                            //p = parseFloat(this.precio[i]);
                            //html += '<td align="center">'+p.toFixed(2)+'</td>';                              
                            c = parseFloat(this.cantidad[i]);
                            html += '<td align="center">'+c.toFixed(2)+'</td>';
                            //t = this.precio[i]*this.cantidad[i];
                            //html += '<td align="right">'+t.toFixed(2)+'</td>';
                            if(idv=="")
                            {
                                //html += '<td align="center"><a id="item-'+i+'-edi" class="item-mp-edi box-boton boton-edit" href="#" title="Editar" ></a></td>';
                                html += '<td align="center"><a id="item-'+i+'-del" class="item-mp-del box-boton boton-anular" href="#" title="Quitar" ></a></td>';
                            }                            
                            html += "</tr>";
                            cont += 1;

                          }
                       }                                            
                       $("#table-detalle-venta").find('tbody').empty().append(html);
                       //this.totales();
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
      getNumItems : function(){var n = 0; for(i=0;i<this.nitem;i++){if(this.estado[i]) n += 1;} return n;}
  };
$(function() 
{   
    $("input[type=text]").focus(function(){this.select();});
    $("#descripcion").focus();
    $("#btn-add-ma").click(function(){addnewproducto();});
    $("#table-detalle-venta").on('click','.item-mp-del',function(){
      var i = $(this).attr("id");
      i = i.split("-");
      producto.eliminar(i[1]);      
    });
});

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
       },'json')
    }
}

function save()
{
   bval = true;
   if ( bval )
      {
          productos = json_encode(producto);          
          var str = $("#frm-produccion").serialize();       
           $.post('index.php',str+'&producto='+productos,function(res)
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
                     $("#cantidad").val("0.00");
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
function clea_frm_producto()
{
   $("#producto,#idsubproductos_semi, #text_subproductosemi").val("");
   $("#stock,#precio,#cantidad").val("0.00");
   $("#producto").focus();
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
