/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

////////////////////////////////////////////////////////////////////////////
//creación del plugin generaMenu.
//envío el menú de opciones como parámetro
////////////////////////////////////////////////////////////////////////////
var id;
(function($) {
$.fn.generaMenu = function(menu) {
   this.each(function(){
      var retardo;
      var capaMenu = $(this);      
      var listaPrincipal = $('<ul></ul>');
      capaMenu.append(listaPrincipal);
      var arrayEnlaces = [];
      var arrayCapasSubmenu = [];
      var arrayLiMenuPrincipal = [];      
      jQuery.each(menu, function(i,j) 
      {         
         var elementoPrincipal = $('<li id="m'+i+'" class="items-p"></li>');
         listaPrincipal.append(elementoPrincipal);         
         var enlacePrincipal;
         if(this.url=="#")
         {
             enlacePrincipal = $('<a title="'+this.texto+'">' + this.texto + '</a>');
         }
         else 
         {
             enlacePrincipal = $('<a title="'+this.texto+'" href="' + this.url + '">' + this.texto + '</a>');
         }
         elementoPrincipal.append(enlacePrincipal);
         t = this.enlaces.length;         
         var capaSubmenu = $('<div class="submenu ui-corner-bl ui-corner-br ui-corner-tr "></div>');         
         if(t>0)
         {
            enlacePrincipal.data("capaSubmenu",capaSubmenu);                 
            var subLista = $('<ul></ul>');         
            capaSubmenu.append(subLista);                  
            jQuery.each(this.enlaces, function() 
            {
               var subElemento = $('<li class="ui-corner-all"></li>');            
               subLista.append(subElemento);            
               var subEnlace = $('<a title="" href="' + this.url + '">' + this.texto + '</a>');            
               subElemento.append(subEnlace);

            });
            //inserto la capa del submenu en el cuerpo de la página
             $(document.body).append(capaSubmenu);
         }
         /////////////////////////////////////////
         //EVENTOS
         /////////////////////////////////////////
         enlacePrincipal.mouseover(function(e)
         {
            var enlace = $(this),
                li = enlace.parent();                        
            clearTimeout(retardo)
            ocultarTodosSubmenus();            
            var submenu = enlace.data("capaSubmenu");
            if(submenu!=undefined)
            {
               if(!li.hasClass('home'))
               {
                  $('li.items-p').removeClass('home');
                  li.addClass('home');   
               }
               id = li.attr("id");
               submenu.css("display","block");               
            }
         });
         enlacePrincipal.click(function(e)
         {
            var enlace = $(this),
                li = enlace.parent();                        
            clearTimeout(retardo)
            ocultarTodosSubmenus();            
            var submenu = enlace.data("capaSubmenu");
            if(submenu!=undefined)
            {
               if(!li.hasClass('home'))
               {
                  $('li.items-p').removeClass('home');
                  li.addClass('home');   
               }
               id = li.attr("id");
               submenu.css("display","block");               
            }            
         });
         //defino el evento para el enlace principal
         enlacePrincipal.mouseout(function(e)
         {
            var enlace = $(this);            
                li = enlace.parent();
            //recupero la capa de submenu asociada
            submenu = enlace.data("capaSubmenu");
            //la oculto
            if(submenu!=undefined)
                {
                    clearTimeout(retardo);
                    retardo = setTimeout("oculta_submenu1()",500);
                }
         });
         //evento para las capa del submenu
         capaSubmenu.mouseover(function()
         {
            clearTimeout(retardo);
         });
         //evento para ocultar las capa del submenu
         capaSubmenu.mouseout(function(){
            clearTimeout(retardo);
            submenu = $(this);
            retardo = setTimeout("oculta_submenu()",500);            
         });
         //evento para cuando se redimensione la ventana
         if(arrayEnlaces.length==0){
            //Este evento sólo lo quiero ejecutar una vez
            $(window).resize(function(){
               colocarCapasSubmenus();               
            });
            $(window).scroll(function () {
                colocarCapasSubmenus();
            });
         }
         /////////////////////////////////////////
         //FUNCIONES PRIVADAS DEL PLUGIN
         /////////////////////////////////////////
         //una función privada para ocultar todos los submenus
         function ocultarTodosSubmenus(){
            $.each(arrayCapasSubmenu, function(){
               this.css("display", "none");
            });            
         }

         //función para colocar las capas de submenús al lado de los enlaces
         function colocarCapasSubmenus(){
            $.each(arrayCapasSubmenu, function(i){
               //coloco la capa en el lugar donde me interesa
               var posicionEnlace = arrayLiMenuPrincipal[i].offset();
               this.css({
                  left: posicionEnlace.left,
                  top: posicionEnlace.top + 18
               });
            });
         }


         //guardo el enlace y las capas de submenús y los elementos li en arrays
         arrayEnlaces.push(enlacePrincipal);
         arrayCapasSubmenu.push(capaSubmenu);
         arrayLiMenuPrincipal.push(elementoPrincipal);

         //coloco inicialmente las capas de submenús
         colocarCapasSubmenus();
      });

   });

   return this;
};

})(jQuery);
function oculta_submenu1()
{
   $('#'+id).removeClass('home');
   submenu.css('display', 'none');
}
function oculta_submenu()
{   
   $('#'+id).removeClass('home');
   submenu.css('display', 'none');
}