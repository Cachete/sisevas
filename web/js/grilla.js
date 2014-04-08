/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    var color_selected = "#FCF1AA";
    var color_normal = "#FFFFFF";
    var index_last = "";
    var index_current = "";
    var Id = "";
    var IdTr = "";
    $(function(){
        
        var contextra = $("#addbotones").html();
        $("#addbotones").empty();        
        $(".operaciones").append(contextra);
        $(".operaciones").buttonset();        
        $('#tgrid tbody').delegate('tr', 'click', function(){
            seleccionable = $(this).hasClass('no-selectable');
            if(!seleccionable)
            {
            txt = $(this).find('td:eq(0)').html();   
            IdTr = $(this).attr("id");            
            if(txt!=null)
            {
                var colorRGB = $(this).css("background-color");             
                index_current = $(this).index();
                colorRGB = colorRGB.substr(4,13);
                colores = colorRGB.split(',');            
                if (parseInt(colores[0])==255 && parseInt(colores[1])==255 && parseInt(colores[2])==255)
                {
                    if(index_last==""){ 
                                        index_last = index_current;
                                        nor('#tgrid tbody tr:eq(0)');
                                        Id="";
                                      }
                        else { nor('#tgrid tbody tr:eq('+index_last+')');
                                index_last = index_current; }
                    sel(this);
                    Id = $('#tgrid tbody tr:eq('+index_current+') td:eq(0)').html();                
                }
                else {nor(this);Id="";}
            }
        }
        });   
        $( "#q" ).focus();     
    });
    function sel(obj)
    {
        $(obj).css({"background-color":color_selected,"font-weight":"bold"});
    }
    function nor(obj)
    {
        $(obj).css({"background":color_normal,"font-weight":"normal"});
    }