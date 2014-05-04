$(document).ready(function() {           
      str = 'controller=config&action=get';
      $("#title-banner").css("display","none");
      $("#title-banner").show("slide",750);            
      maxwindow();
      $(window).resize(function(){maxwindow();});
      $.get('index.php','controller=index&action=Menu',function(menu){
          $("#menu").empty();                    
          var opciones_menu = menu;
          w = $(document).width();                
          $(".head_nav").generaMenu(opciones_menu);                
      },'json');
      newexp();

           var $floatingbox = $('#site_head'); 
           if($('#body').length > 0)
           {
              var bodyY = parseInt($('#body').offset().top);
              var originalX = $floatingbox.css('margin-left');
              $(window).scroll(function () 
              {                        
                   var scrollY = $(window).scrollTop();
                   var isfixed = $floatingbox.css('position') == 'fixed';
                   if($floatingbox.length > 0){
                      if ( scrollY > bodyY && !isfixed ) 
                      {                                
                                $floatingbox.stop().css({
                                  position: 'fixed',                                  
                                  marginLeft: 0,
                                  top:0
                                });
                        } else if ( scrollY < bodyY && isfixed ) {
                                  $floatingbox.css({
                                  position: 'absolute',
                                  top:0,
                                  marginLeft: originalX
                        });
                     }		
                   }
              });
            }             
       
  });
//document.oncontextmenu = function(){ return false; }
function maxwindow()
{
  var h = $(window).height();    
  $(".div_container").css('minHeight',(h-117));
  $("#left").css('minHeight',(h-117));  
}

/**/
//setInterval(newexp,3000);
  function newexp()
  {
     $.get('index.php','controller=envio&action=nuevos',function(data){
         var nnew = parseInt(data[0]);               
         if(nnew>0)
             {
              //alert(nnew);
                 $("#icon-notifications").removeClass('icon-none');
                 $("#icon-notifications").addClass('icon-dato');
                 $("#icon-notifications").attr("href","index.php?controller=envio&action=recibir");
                 $(".indicator-notification").find("#indicator-notification").empty().append(nnew);
                 if(data>1){title = "Tiene "+nnew+" Nuevos Expedientes por Recibir";}
                  else { title = "Tienes 1 nuevo Expediente por Recibir";}
                 $("#indicator-notification").attr("title",title);                       
                 $("#indicator-notification").show("slow");
             }
          else {
              $("#icon-notifications").removeClass('icon-dato');
              $("#icon-notifications").addClass('icon-none');
              $("#indicator-notification").css("display","none");
              $("#indicator-notification").removeAttr('title');        
              $("#icon-notifications").attr("href","#");
          }
     },'json');
  }