var materia = 
  { 
      nitem       : 0,
      idmaterial  : new Array(), //Id tipo de producto                  
      material    : new Array(),
      idunidad    : new Array(),
      unidad      : new Array(),
      cantidad    : new Array(),
      estado      : new Array(),
      nuevo      : function(idmaterial,material,idunidad,unidad,cantidad)
                    { 

                      this.idmaterial[this.nitem] = idmaterial;
                      this.material[this.nitem] = material;
                      this.idunidad[this.nitem] = idunidad;
                      this.unidad[this.nitem] = unidad;                                            
                      this.cantidad[this.nitem]  = cantidad;                      
                      this.estado[this.nitem] = true;
                      this.nitem += 1;

                    },
      //Mostrar en el detalle todos los items agregados
      listar      : function()
                    {
                       var html = "";
                       var cont = 0;
                       for(i=0;i<this.nitem;i++)
                       {                          
                          if(this.estado[i])
                          {
                            html += '<tr>';
                            html += '<td align="center">'+(cont+1)+'</td>';                            
                            html += '<td>'+this.material[i]+'</td>';                            
                            html += '<td align="center">'+this.unidad[i]+'</td>';                              
                            c = parseFloat(this.cantidad[i]);
                            html += '<td align="center">'+c.toFixed(2)+'</td>';
                            html += '<td><a id="item-'+i+'" class="item-mp box-boton boton-anular" href="#" title="Quitar" ></a></td>';
                            html += "</tr>";
                            cont += 1;

                          }
                       }                                            
                       $("#table-detalle-materia").find('tbody').empty().append(html);
                    },
      eliminar    : function(i)
                    {
                      this.estado[i] = false;                        
                    },
      limpiar     : function()
                    {   
                        this.idmaterial.clear();
                        this.material.clear();
                        this.idunidad.clear();
                        this.unidad.clear();
                        this.cantidad.clear();
                        this.estado.clear();
                        this.nitem = 0;
                    },
      //Obtenemos la cantidad total agregada en el detalle por producto
      getTotalP   : function(idmaterial,idunidad)
                    {
                       var t = 0;
                       for(i=0;i<this.nitem;i++)
                       {
                          if(this.estado[i]&&this.idproducto[i]==idproducto&&this.idalmacen[i]==idalmacen)
                          {
                             t += parseFloat(this.cantidad[i]);
                          }
                        }
                        return t;
                    },
      getNumItems : function()
                    {
                      var n = 0;
                      for(i=0;i<this.nitem;i++)
                      {
                        if(this.estado[i])
                          n += 1;
                      }
                      return n;
                    }
  };
$(function() 
{     
    //Basic events

    $("input[type=text]").focus(function(){this.select();});
    $("#fechai,#fechaf").datepicker({dateFormat:'dd/mm/yy','changeMonth':true,'changeYear':true});    
    $("#dni").autocomplete({
        minLength: 0,
        source: 'index.php?controller=personal&action=get&tipo=1',
        focus: function( event, ui ) 
        {
            $( "#dni" ).val( ui.item.dni );
            return false;
        },
        select: function( event, ui ) 
        {
            $("#idpersonal").val(ui.item.idpersonal);
            $( "#dni" ).val( ui.item.dni );
            $( "#personal" ).val( ui.item.nompersonal );                                    
            return false;
        }
     }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>"+ item.dni +" - " + item.nompersonal + "</a>" )
            .appendTo(ul);
      };

    getDetails();

    $("#cantidad").change(function(){
      valida_cant();
    });

    //Eventos dentro de fildset produccion
    $("#btn-add-mp").click(function(){ });
    $("#idmateriales").change(function(){getUnidad($(this).val());});    
    $("#idunidad_medida").change(function(){
      $("#cant_ma").focus();
    });

    $("#btn-add-ma").click(function(){addNewMaterial();});
    $("#table-detalle-materia").on('click','.item-mp',function(){
      var i = $(this).attr("id");
      i = i.split("-");
      materia.eliminar(i[1]);
      materia.listar();
    });

    $("#btn-add-detalle-prod").click(function(){addDetalleProd();});

    //Eventos para detalle acabado
    $("#div-detalle").on('click','.edit-produccion',function(){
        var i = $(this).attr("id"); i = i.split("-");
        editProduccion(i[2]);
    });

    $("#div-detalle").on('click','.delete-produccion',function(){
        var i = $(this).attr("id"); i = i.split("-");
        produccion.eliminar(i[2]);
        produccion.listar();
    });

    $("#div-detalle").on('click','.delete-produccion-mp',function(){
        var i = $(this).attr("id"); i = i.split("-");
        produccion.eliminar_mp(i[2],i[3]);
        produccion.listar();
    });
});

function valida_cant()
{  
  var stk = $("#stock").val(),
      cant = $("#cantidad").val();
      if(stk!="")
      {
        stk = stk.replace(",","");
        stk = parseFloat(stk);
      }
      else
      {
        alert("Debe seleccionar una produccion.");
        return false;
      }
  if(cant>0)
  {  
      if(cant>stk)
      {
         alert("Alerta: La cantidad supera el stock maximo.");         
         $("#cantidad").focus();         
         return false;
      }
  }     if(cant)
  {

  }
  else 
  {
     alert("La cantidad debe ser mayo que cero (0)");     
     $("#cantidad").focus();
     return false;
  }
  return true;  
}

function getDetails()
{
   var i = $("#idacabado").val();
   if(i!="")
   {
     $.get('index.php','controller=acabado&action=getDetails&id='+i,function(r){
          $.each(r,function(i,j){
              materia.nuevo(j.idmaterial,j.material,j.idunidad,j.unidad,j.cantidad);
              //function(idmaterial,material,idunidad,unidad,cantidad)
          });
          materia.listar();
     },'json');
   }
}
function addDetalleProd()
{
  bval = true;
  bval = bval && $("#dproducto").required();
  bval = bval && $("#dresponsable").required();
  bval = bval && $("#tprod").required();
  bval = bval && $("#dresponsable").required();
  bval = bval && $("#stock").required();
  bval = bval && $("#cantidad").required();
  if(bval)
  {
      var idpd = $("#idproduccion_detalle").val();
      if(idpd!="")
      { 

          var producto = $("#dproducto").val(),
              responsable = $("#dresponsable").val(),
              totalp  = $("#tprod").val(),
              stock  = $("#stock").val(),
              cant   = $("#cantidad").val(),
              items = materia.getNumItems();
          if(cant>0)
          {
            if(items>0)
            {
               //clonamos materia para enviar para enviar una copia más nó una referencia
               var clon_materia = clone(materia);
               produccion.nuevo(idpd,producto,responsable,totalp,stock,cant,clon_materia);
               materia.limpiar();
               materia.listar();
               produccion.listar();
               //limpiar_ps(); //Limpiamos cabecera de produccion
            }
            else
            {
              alert("Debe agregar los materiales que se emplearán para el acabado de esta produccion");
              $("#idmadera").focus();
              return 0;
            }
          }
          else
          {
             alert("la cantidad de la produccion debe ser mayor que cero (0)");
             $("#cantidad").focus();
          }
      }  
      else
      {
        alert("Porfavor busque una produccion para poder agregar al proceso de acabados.");
      }    
  }
}

function addNew()
{
  bval = true;
  bval = bval && $("#idmadera").required();
  bval = bval && $("#cant_ma").required();
  bval = bval && $("#stock_ma").required();

  if(bval) 
  {
      var ida = $("#idalmacenma").val(),
            a = $("#idalmacenma option:selected").html(),
          idm = $("#idmadera").val(),
            m = $("#idmadera option:selected").html(),
          stk = $("#stock_ma").val(),
            c = $("#cant_ma").val();

      if(valida_cant(c,1))
      {
        if(c>0)
        {
          materia.nuevo(1,ida,a,idm,m,stk,c);
          materia.listar();                        
          getStock(idm,1);
          $("#cant_ma").val('0.00').focus();
        }
      }
  }
}

function getUnidad(id)
{  
   $.get('index.php','controller=unidadmedida&action=getUnidades&id='+id,function(r){
        var html = "<option value=''>Seleccione undiad de Medida...</option>";
        $.each(r,function(i,j)
        {
            html += '<option value="'+j.id+'">'+j.descripcion+'</option>';
        })
        $("#idunidad_medida").empty().append(html);
   },'json');
}


function save()
{
   var c = $( "#controller" ).val(),
       a = $( "#action" ).val();

  if(typeof(c)=="undefined"||typeof(a)=="undefined")
  {
     alert("No se puede realizar esta operacion.");
     return 0;
  }

  bval = true;
  fl = true;  
  bval = bval && $( "#fechai" ).required();
  bval = bval && $( "#fechaf" ).required();
  bval = bval && $( "#dni" ).required();

  if ( bval ) 
  {      
    valida_cant();  
    var str = $("#frm-acabado").serialize();
    var materiales = json_encode(materia);
    $.post('index.php',str+'&materiales='+materiales,function(res)
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
        addNewMaterial();
    }
}

function getData(obj)
{
   $("#dproducto").val(obj.Producto);
   $("#dresponsable").val(obj.Responsable);
   $("#tprod").val(obj.Cantidad);
   $("#stock").val(obj.Stock);   
   $("#idproduccion_detalle").val(obj.Codigo);
   $("#cantidad").focus();

}
function addNewMaterial()
{
  bval = true;
  bval = bval && $("#idmateriales").required();
  bval = bval && $("#idunidad_medida").required();
  bval = bval && $("#cant_ma").required();
  
  if(bval) 
  {
      var idm   = $("#idmateriales").val(),
            m   = $("#idmateriales option:selected").html(),
          idme  = $("#idunidad_medida").val(),
            me  = $("#idunidad_medida option:selected").html(),          
            c   = $("#cant_ma").val();
        if(c>0)
        {
          materia.nuevo(idm,m,idme,me,c);
          materia.listar();          
          $("#cant_ma").val('0.00').focus();
        }      
        else 
        {
          alert("Cantidad debe ser mayor a cero (0)");
          $("#cant_ma").focus();
        }
  }
}